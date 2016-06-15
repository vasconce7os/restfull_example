<?php
namespace Api\Controller;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use \Application\Model\Fornecedor;
use \Application\Form\FornecedorForm;
use \Application\Entity\Fornecedor as FornecedorEntity;
class FornecedorController extends AbstractRestfulController
{
    private $operationOk = false;
    private $errorMessage = "";
    private $successMessage = "";
    private $lFornecedoresArray = array();

    protected $_objectManager = null;

    private $setor = null;
    private $user = null;

    public function getList()
    {
        $keySearch = $this->params()->fromQuery('key_search');
        $lFornecedores = $this-> getObjectManager()->getRepository("\Application\Entity\Fornecedor")->createQueryBuilder('fornecedor')
        ->Where('fornecedor.nome LIKE :key')
        ->setParameter('key', "%".$keySearch."%")
        ->getQuery()
        ->getResult();

        if(count($lFornecedores) == 0)
        {
            $this-> errorMessage = "Não há itens!";
        } else
        {
            $this-> successMessage = "tem";
            $this-> operationOk = true;
            foreach ($lFornecedores as $key => $fornecedor)
            {
                $this-> lFornecedoresArray[] = array('id'=> $fornecedor-> getId(), 'nome'=> $fornecedor-> getNome());
            }
            $this-> listSize = count($lFornecedores);
        }
        return new JsonModel($this-> lFornecedoresArray);
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

        $logger-> info("\n" . __LINE__ ."aqui  Controller; create() \n Tudo que recebomos de parâmetro foi: \n". print_r($data, true));

        //sleep(3);
        $fornecedor = $this-> getObjectManager()-> getRepository('\Application\Entity\Fornecedor')-> findOneBy(array('cpf' => @$data['cpf']));
        if($fornecedor)
        {
            $this-> errorMessage['cpf']["isPresent"] = "Fornecedor com CPF ". $data['cpf'] . " já cadastrado!";
        } else
        {
            $form = new FornecedorForm();
            $fornecedor = new Fornecedor();
            $form->setInputFilter($fornecedor->getInputFilter());
            $form->setData($data);

            if ($form->isValid())
            {
                $fornecedor-> exchangeArray($form-> getData());
                $fornecedorArray = $fornecedor-> getFilteredFields();

                $hydrator = new DoctrineHydrator($this->getObjectManager());
                $fornecedorEntity = new FornecedorEntity();
                $fornecedorEntity = $hydrator-> hydrate($fornecedorArray, $fornecedorEntity);

                $this-> getObjectManager()-> persist($fornecedorEntity);
                $persistiu = $this-> getObjectManager()-> flush();
                //$logger-> info("\n" . __LINE__ ."persistiu: \n \n". print_r(gettype($persistiu), true));
                if($this-> getObjectManager()-> contains($fornecedorEntity))
                {
                    $this-> operationOk = true;
                    $this-> errorMessage = null;
                } else
                {
                    $this-> errorMessage[0][] = "Houve um erro ao persistir!";
                }

            } else
            {
                $logger-> info("\n" . __LINE__ ." messages erros: \n \n". print_r($form-> getMessages(), true));
                $this-> errorMessage = $form-> getMessages();
            }
        }
        return new JsonModel($this-> getArrayReturn());

    }

    protected function getObjectManager()
    {
        if (!$this->_objectManager) {
            $this->_objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->_objectManager;
    }
}
