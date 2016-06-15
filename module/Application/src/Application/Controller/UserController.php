<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class UserController extends BaseController
{
    public function indexAction()
    {
        return [];
    }

    public function addAction()
    {
        return new ViewModel();
    }

    public function editAction()
    {
        return new ViewModel();
    }
    public function usersAction() paginação
    {
        $page = $this->params()->fromRoute('page', 1);
        # move to service
        $limit = 10;
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $pagedUsers = $em->getRepository('Application\Entity\User')->getPagedUsers($offset, $limit);
        # end move to service
        $viewModel = new ViewModel();
        $viewModel->setVariable( 'pagedUsers', $pagedUsers );
        $viewModel->setVariable( 'page', $page );

        return $viewModel;
    }
}
