<?php
namespace Test\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\View\Model\JsonModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use \Application\Model\Compra;
use \Application\Form\CompraForm;
use \Application\Entity\Compra as CompraEntity;
class TestcController extends AbstractActionController
{
    private $operationOk = false;
    private $errorMessage = "";
    private $successMessage = "";
    protected $_objectManager = null;

    public function index2Action()
    {
        $data =  array
        (
            'id'=> "",
            'quantidade'=> '2',
            'valueProduct'=> "32,35",
            //'data_criacao'=> "",
            'produto'=> array('id'=> 3),
            'fornecedor'=> array('id'=> 6),
        );


        //echo "<pre>come\n";
        $form = new CompraForm();
        $compra = new Compra();
        $form->setInputFilter($compra->getInputFilter());
        $form->setData($data);
        if ($form->isValid())
        {
            echo "\nvai salvar";
            exit;
            $compra-> exchangeArray($form->getData());

            $compraArray = $compra-> getFilteredFields();

            echo("\nc arr");
            print_r($compraArray);
            $hydrator = new DoctrineHydrator($this->getObjectManager());
            $compraEntity = new CompraEntity();
            $compraEntity = $hydrator-> hydrate($compraArray, $compraEntity);
            $compraEntity-> setDataCriacao(new \DateTime);
            $this-> getObjectManager()-> persist($compraEntity);
            $persistiu = $this-> getObjectManager()-> flush();
            if($this-> getObjectManager()-> contains($compraEntity))
            {
                echo "\nsalvou";
            } else
            {
                echo "\nerro ao persistir";
            }
        } else
        {
            echo "\nNão válido\n";
            print_r($form-> getMessages());

        }
        exit;
        return new ViewModel();
    }

    public function indexAction()
    {
        $repository = $this-> getObjectManager()-> getRepository('\Application\Entity\Compra');
        $qb = $repository->createQueryBuilder('n')
                //->where('n.bar = :bar')
                //->setParameter('bar', $bar);

        $query = $qb->getQuery();

        //this doesn't work
        $totalrows = $query->getResult()();
        exit;
    }

    public function testcAction()
    {

    }

    protected function getObjectManager()
    {
        if (!$this->_objectManager) {
            $this->_objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->_objectManager;
    }
}
