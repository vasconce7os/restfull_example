<?php
namespace Test\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function generatepdfAction()
    {
        return new ViewModel();
        //$pdf = new PdfModel();
        exit;
        return $pdf;
    }
    public function otherAction()
    {
        return new ViewModel();
    }

}
