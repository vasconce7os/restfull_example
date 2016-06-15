<?php
/**
 * @author: Vasconcelos
 * @create: 2016-06-14
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsuarioController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function inserirAction()
    {
        return new ViewModel();
    }
}
