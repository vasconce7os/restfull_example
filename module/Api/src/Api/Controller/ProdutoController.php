<?php
namespace Api\Controller;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use \Application\Model\Produto;
use \Application\Form\ProdutoForm;
use \Application\Entity\Produto as ProdutoEntity;
class ProdutoController extends AbstractRestfulController
{
    private $operationOk = false;
    private $errorMessage = "";
    private $successMessage = "";
    private $lProdutosArray = array();
    private $listSize = 0;
    protected $_objectManager = null;

    private $setor = null;
    private $user = null;

    public function getList()
    {
        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        $logger->addWriter($writer);

        $logger-> info("\n" . __LINE__ ."aqui  getList; create() \n Tudo \n");

        $keySearch = $this->params()->fromQuery('key_search');

        $this-> successMessage = "successMessage fh aqui e deve ser alterada, getList ghgh\n " . print_r($keySearch, true);
        $logger-> info("\n" . __LINE__ ."  \nsei sim; getList, successMessage: " . print_r($keySearch, true) . "\n");

        $lProdutos = $this-> getObjectManager()->getRepository("\Application\Entity\Produto")->createQueryBuilder('produto')
        ->Where('produto.nome LIKE :key')
        ->setParameter('key', "%".$keySearch."%")
        ->getQuery()
        ->getResult();

        if(count($lProdutos) == 0)
        {
            $this-> errorMessage = "Não há itens!";
        } else
        {
            $this-> successMessage = "tem";
            $this-> operationOk = true;
            //$logger-> info("\n" . __LINE__ ."  \nddddzzzz: " . \Doctrine\Common\Util\Debug::export($lProdutos[0]) . "\n");
            foreach ($lProdutos as $key => $produto)
            {
                $this-> lProdutosArray[] = array('id'=> $produto-> getId(), 'nome'=> $produto-> getNome());
            }
            $this-> listSize = count($lProdutos);
        }

        //return new JsonModel($this-> getArrayReturn());
        return new JsonModel($this-> lProdutosArray);
    }

    private function getArrayReturn()
    {
        $return = array(
            'ok'=> $this-> operationOk,
            'errorMessage'=> $this-> errorMessage,
            'successMessage'=> $this-> successMessage,
            'lProdutos'=> $this-> lProdutosArray,
            'listSize'=> $this-> listSize,
        );
        return $return;
    }

    public function create($data)
    {
        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        $logger->addWriter($writer);

        $logger-> info("\n" . __LINE__ ."aqui  Controller; create() \n Tudo que recebomos de parâmetro foi: \n". print_r($data, true));

        $produto = $this-> getObjectManager()-> getRepository('\Application\Entity\Produto')-> findOneBy(array('nome' => $data['nome']));
        if($produto)
        {
            $this-> errorMessage[0][] = "Produto Já cadastrado!";
        } else
        {
            $form = new ProdutoForm();
            $produto = new Produto();
            $form->setInputFilter($produto->getInputFilter());
            $form->setData($data);
            if ($form->isValid())
            {
                $produto->exchangeArray($form->getData());
                $produtoArray = $produto-> getFilteredFields();
                $hydrator = new DoctrineHydrator($this->getObjectManager());
                $produtoEntity = new ProdutoEntity();
                $produtoEntity = $hydrator-> hydrate($produtoArray, $produtoEntity);

                $this-> getObjectManager()-> persist($produtoEntity);
                $this-> getObjectManager()-> flush();
                if($this-> getObjectManager()-> contains($produtoEntity))
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
