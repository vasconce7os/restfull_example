<?php

namespace ZfcUser\Controller;

use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;
use ZfcUser\Service\User as UserService;
use ZfcUser\Options\UserControllerOptionsInterface;

class UserController extends AbstractActionController
{
    const ROUTE_CHANGEPASSWD = 'zfcuser/changepassword';
    const ROUTE_LOGIN        = 'zfcuser/login';
    const ROUTE_REGISTER     = 'zfcuser/register';
    const ROUTE_CHANGEEMAIL  = 'zfcuser/changeemail';

    const CONTROLLER_NAME    = 'zfcuser';

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var Form
     */
    protected $loginForm;

    /**
     * @var Form
     */
    protected $registerForm;

    /**
     * @var Form
     */
    protected $changePasswordForm;

    /**
     * @var Form
     */
    protected $changeEmailForm;

    /**
     * @todo Make this dynamic / translation-friendly
     * @var string
     */
    protected $failedLoginMessage = 'A autenticação falhou. Por favor, tente novamente'; //'Authentication failed. Please try again.';

    /**
     * @var UserControllerOptionsInterface
     */
    protected $options;

    /**
     * User page
     */
    public function indexAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        return new ViewModel();
    }

    /**
     * Login form
     */
    public function loginAction()
    {
        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        $logger->addWriter($writer);
        //$logger->info(__LINE__ ."\nIniciando Login \n");
        //echo "<br />Deve escrever";
        //exit;
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $logger->info(__LINE__ ."\nTem identificação, hasIdentity()\n");
            /**
            * eu substituí a seginte linha:
            * //return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
            * pelo bloco de código seguinte 9até o return) porque quando se acessava
            * a uri de login já estando logado, dava um erro. Fiz isto seguindo
            * ogientações deste link: http://stackoverflow.com/a/17560640
            */

            $route = $this->getOptions()->getLoginRedirectRoute();
            if(is_callable($route)) {
                $route = $route($this->zfcUserAuthentication()->getIdentity());
            }
            return $this->redirect()->toRoute($route);
        }
        //exit;
        $request = $this->getRequest();
        $form    = $this->getLoginForm();
        //$logger->info(__LINE__ ."\nVai checar opções de redirecionamento presentes\n");

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }
        //$logger->info(__LINE__ ."\nVai chacar se é POST\n");

        if (!$request->isPost()) {
            //$logger->info(__LINE__ ."\nSim, NÃO é post \n");
            return array(
                'loginForm' => $form,
                'redirect'  => $redirect,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
            );
            //$logger->info(__LINE__ ."\nRetornou uns troços\n");
        }
        //$logger->info(__LINE__ ."\nVai chamar o setData() com os valoes de getPost() como parâmetro\n");
        $form->setData($request->getPost());

        //$logger->info(__LINE__ ."\nvai chamar isValid() no if\n");
        if (!$form->isValid()) {
            //$logger->info(__LINE__ ."\nSe entrou aqui é porque o login é invádido\n");
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN).($redirect ? '?redirect='. rawurlencode($redirect) : ''));
        }
        //$logger->info(__LINE__ ."\nVai limpar os adaptadores\n");
        // clear adapters
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();
        //$logger->info(__LINE__ ."\nvai retornar o forward()\n");
        return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
    }

    /**
     * Logout and clear the identity
     */
    public function logoutAction()
    {
        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        $logger->addWriter($writer);

        //Iniciando o bloco que gera o histórico de login
        //$logger->info(__LINE__ ."\nVai efetuar logout\n");
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $session = new \Zend\Session\Container('fpPortal');
        $login = $session-> login;
        if(isset($session->login['id']))
        {
            $loginEntity = $em->find('\Beneficiario\Entity\Logins', $login['id']);

            if($loginEntity)
            {
                $loginEntity->setModified(new \DateTime());
                $loginEntity->setLogout(new \DateTime());
                $em->persist($loginEntity);
                $em->flush();
            } else
            {
                $logger-> info(__LINE__ ."\nErro no logout!\n");
                $logger-> info(__LINE__ ."\nAcredito que só ocorre quando os dados do db foram importados via mysqldump!\n");
            }
        }
        // fim do bloco histórico de login

        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthAdapter()->logoutAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();
        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {
            return $this->redirect()->toUrl($redirect);
        }

        return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute());
    }

    /**
     * General-purpose authentication action
     */
    public function authenticateAction()
    {
        //$logger = new \Zend\Log\Logger;
        //$writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        //$logger->addWriter($writer);
        //$logger->info(__LINE__ ."\nIniciando authenticateAction() \n");

        if ($this->zfcUserAuthentication()->hasIdentity()) {
            //$logger->info(__LINE__ ."\n\$this->zfcUserAuthentication()->hasIdentity() é true, vai retornar um redirect\n");
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        //$logger->info(__LINE__ ."\nthis->zfcUserAuthentication()->hasIdentity() não retornar positivo\n");

        $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

        //$logger->info(__LINE__ ."\nPreparando para autenticação\n");
        $result = $adapter->prepareForAuthentication($this->getRequest());

        // Return early if an adapter returned a response
        if ($result instanceof Response) {
            return $result;
        }

        //$logger->info(__LINE__ ."\nSetando auth com o getAuthService\n");
        $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

        //$logger->info(__LINE__ ."\nchama o isValid() de auth e se false retorna um redirect \n");
        if (!$auth->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            $adapter->resetAdapters();
            return $this->redirect()->toUrl(
                $this->url()->fromRoute(static::ROUTE_LOGIN) .
                ($redirect ? '?redirect='. rawurlencode($redirect) : '')
            );
        }

        //Iniciando o bloco que gera o histórico de login
        //$logger->info(__LINE__ ."\nSe chegou até aqui é porque efetuou login, fazer o log agora\n");
        $loginEntity = new \Beneficiario\Entity\Logins();
        $loginEntity->setIp($this->getRequest()->getServer('REMOTE_ADDR'));
        $loginEntity->setUseragent($this->getRequest()->getServer('HTTP_USER_AGENT'));
        $loginEntity->setCreated(new \DateTime());
        $loginEntity->setModified(new \DateTime());
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $user = $em->find('\Beneficiario\Entity\User', $this->zfcUserAuthentication()->getIdentity()->getId());
        $loginEntity->setUser($user); //$this->zfcUserAuthentication()->getIdentity()->getId()
        $em->persist($loginEntity);
        $em->flush();

        $container = new \Zend\Session\Container('fpPortal');
        $login['id'] = $loginEntity->getId();
        $container->login = $login;
        // fim do bloco histórico de login

        //exit;
        //$logger->info(__LINE__ ."\ncheca se redirect existe e redireciona\n");
        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {
            return $this->redirect()->toUrl($redirect);
        }

        //substituindo esta linha pelo seguinte bloco para tentar criar um redirecionamento personalizado devido ao peril de user
        //return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());

        //inicio do bloco de redirecionamento personalizado
        $route = $this->getOptions()->getLoginRedirectRoute();
        if(is_callable($route)) {
            $route = $route($this->zfcUserAuthentication()->getIdentity());
        }
        return $this->redirect()->toRoute($route);
        // fim do bloco personalização do redirect

    }

    /**
     * Register new user
     */
    public function registerAction()
    {
        // if the user is logged in, we don't need to register
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        // if registration is disabled
        if (!$this->getOptions()->getEnableRegistration()) {
            return array('enableRegistration' => false);
        }

        $request = $this->getRequest();
        $service = $this->getUserService();
        $form = $this->getRegisterForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }

        $redirectUrl = $this->url()->fromRoute(static::ROUTE_REGISTER)
            . ($redirect ? '?redirect=' . rawurlencode($redirect) : '');
        $prg = $this->prg($redirectUrl, true);

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
            );
        }

        $post = $prg;
        $user = $service->register($post);

        $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;

        if (!$user) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
            );
        }

        if ($service->getOptions()->getLoginAfterRegistration()) {
            $identityFields = $service->getOptions()->getAuthIdentityFields();
            if (in_array('email', $identityFields)) {
                $post['identity'] = $user->getEmail();
            } elseif (in_array('username', $identityFields)) {
                $post['identity'] = $user->getUsername();
            }
            $post['credential'] = $post['password'];
            $request->setPost(new Parameters($post));
            return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
        }

        // TODO: Add the redirect parameter here...
        return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect='. rawurlencode($redirect) : ''));
    }

    /**
     * Change the users password
     */
    public function changepasswordAction()
    {
        // if the user isn't logged in, we can't change password
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getChangePasswordForm();
        $prg = $this->prg(static::ROUTE_CHANGEPASSWD);

        $fm = $this->flashMessenger()->setNamespace('change-password')->getMessages();
        if (isset($fm[0])) {
            $status = $fm[0];
        } else {
            $status = null;
        }

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'status' => $status,
                'changePasswordForm' => $form,
            );
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'changePasswordForm' => $form,
            );
        }

        if (!$this->getUserService()->changePassword($form->getData())) {
            return array(
                'status' => false,
                'changePasswordForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('change-password')->addMessage(true);
        return $this->redirect()->toRoute(static::ROUTE_CHANGEPASSWD);
    }

    public function changeEmailAction()
    {
        // if the user isn't logged in, we can't change email
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getChangeEmailForm();
        $request = $this->getRequest();
        $request->getPost()->set('identity', $this->getUserService()->getAuthService()->getIdentity()->getEmail());

        $fm = $this->flashMessenger()->setNamespace('change-email')->getMessages();
        if (isset($fm[0])) {
            $status = $fm[0];
        } else {
            $status = null;
        }

        $prg = $this->prg(static::ROUTE_CHANGEEMAIL);
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'status' => $status,
                'changeEmailForm' => $form,
            );
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'changeEmailForm' => $form,
            );
        }

        $change = $this->getUserService()->changeEmail($prg);

        if (!$change) {
            $this->flashMessenger()->setNamespace('change-email')->addMessage(false);
            return array(
                'status' => false,
                'changeEmailForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('change-email')->addMessage(true);
        return $this->redirect()->toRoute(static::ROUTE_CHANGEEMAIL);
    }

    /**
     * Getters/setters for DI stuff
     */

    public function getUserService()
    {
        if (!$this->userService) {
            $this->userService = $this->getServiceLocator()->get('zfcuser_user_service');
        }
        return $this->userService;
    }

    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;
        return $this;
    }

    public function getRegisterForm()
    {
        if (!$this->registerForm) {
            $this->setRegisterForm($this->getServiceLocator()->get('zfcuser_register_form'));
        }
        return $this->registerForm;
    }

    public function setRegisterForm(Form $registerForm)
    {
        $this->registerForm = $registerForm;
    }

    public function getLoginForm()
    {
        if (!$this->loginForm) {
            $this->setLoginForm($this->getServiceLocator()->get('zfcuser_login_form'));
        }
        return $this->loginForm;
    }

    public function setLoginForm(Form $loginForm)
    {
        $this->loginForm = $loginForm;
        $fm = $this->flashMessenger()->setNamespace('zfcuser-login-form')->getMessages();
        if (isset($fm[0])) {
            $this->loginForm->setMessages(
                array('identity' => array($fm[0]))
            );
        }
        return $this;
    }

    public function getChangePasswordForm()
    {
        if (!$this->changePasswordForm) {
            $this->setChangePasswordForm($this->getServiceLocator()->get('zfcuser_change_password_form'));
        }
        return $this->changePasswordForm;
    }

    public function setChangePasswordForm(Form $changePasswordForm)
    {
        $this->changePasswordForm = $changePasswordForm;
        return $this;
    }

    /**
     * set options
     *
     * @param UserControllerOptionsInterface $options
     * @return UserController
     */
    public function setOptions(UserControllerOptionsInterface $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * get options
     *
     * @return UserControllerOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof UserControllerOptionsInterface) {
            $this->setOptions($this->getServiceLocator()->get('zfcuser_module_options'));
        }
        return $this->options;
    }

    /**
     * Get changeEmailForm.
     *
     * @return changeEmailForm.
     */
    public function getChangeEmailForm()
    {
        if (!$this->changeEmailForm) {
            $this->setChangeEmailForm($this->getServiceLocator()->get('zfcuser_change_email_form'));
        }
        return $this->changeEmailForm;
    }

    /**
     * Set changeEmailForm.
     *
     * @param changeEmailForm the value to set.
     */
    public function setChangeEmailForm($changeEmailForm)
    {
        $this->changeEmailForm = $changeEmailForm;
        return $this;
    }
}
