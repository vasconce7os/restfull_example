<?php
namespace Api\Controller;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use \Application\Model\Usuario;
use \Application\Form\UsuarioForm;
use \Application\Entity\Usuario as UsuarioEntity;
class UsuarioController extends AbstractRestfulController
{
    private $operationOk = false;
    private $errorMessage = "";
    private $successMessage = "";
    protected $_objectManager = null;

    private $setor = null;
    private $user = null;

    public function getList()
    {
        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        $logger->addWriter($writer);

        $logger-> info("\n" . __LINE__ ."aqui  getList() \n Tudo \n");
        $this-> successMessage = "successMessage fh aqui e deve ser alterada, getList";
        //$this-> resolveSetor(array('id' => 33, 'descricao' => "Teste em getList"));

        $logger-> info("\n" . __LINE__ ."  \nsei sim; getList, successMessage: " . $this-> successMessage . "\n");
        return new JsonModel($this-> getArrayReturn());
    }

    private function getArrayReturn()
    {
        return array(
            'ok'=> $this-> operationOk,
            'errorMessage'=> $this-> errorMessage,
            'successMessage'=> $this-> successMessage,
        );
    }

    public function create($data)
    {
        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        $logger->addWriter($writer);

        //$logger-> info("\n" . __LINE__ ."aqui é Controller " .get_class($this) . "; create() \n Tudo que recebomos de parâmetro foi: \n". print_r($data, true));

        $form = new UsuarioForm();
        $usuario = new Usuario();
        $usuario->setEm($this->getObjectManager()); // seting objetc manager doctrine
        $form->setInputFilter($usuario->getInputFilter());
        $form->setData($data);
        //sleep(2);
        if ($form->isValid())
        {
            $usuario-> exchangeArray($form->getData());
            $usuarioArray = $usuario-> getFilteredFields();
            $hydrator = new DoctrineHydrator($this->getObjectManager());
            $usuarioEntity = new UsuarioEntity();
            $usuarioEntity = $hydrator-> hydrate($usuarioArray, $usuarioEntity);
            $usuarioEntity-> setDataCriacao(new \DateTime);
            $this-> getObjectManager()-> persist($usuarioEntity);
            $this-> getObjectManager()-> flush();

            if($this-> getObjectManager()-> contains($usuarioEntity))
            {
                $this-> operationOk = true;
                $this-> errorMessage = null;
            } else
            {
                $this-> errorMessage[0][] = "Houve um erro ao persistir!";
            }
        } else
        {
            //$logger-> info("\n" . __LINE__ ." messages erros: \n \n". print_r($form-> getMessages(), true));
            $this-> errorMessage = $form-> getMessages();
        }
        return new JsonModel($this-> getArrayReturn());
    }

    protected function getObjectManager()
    {
        if (!$this->_objectManager)
        {
            $this->_objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->_objectManager;
    }
}
