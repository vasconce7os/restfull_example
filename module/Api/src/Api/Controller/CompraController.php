<?php
namespace Api\Controller;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use \Application\Model\Compra;
use \Application\Form\CompraForm;
use \Application\Entity\Compra as CompraEntity;
use \Application\Entity\ProdutoEstoque;
class CompraController extends AbstractRestfulController
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

        $logger-> info("\n" . __LINE__ ."aqui  getList; create() \n Tudo \n");
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

        $form = new CompraForm();
        $compra = new Compra();
        $compra->setEm($this->getObjectManager()); // seting objetc manager doctrine
        $compra->setProtudo($data['produto']);
        $compra->setQuantidade($data['quantidade']);
        $form->setInputFilter($compra->getInputFilter());
        $data['valueProduct'] = str_replace(",", ".", $data['valueProduct']);
        $form->setData($data);
        //sleep(2);
        if ($form->isValid())
        {
            $compra-> exchangeArray($form->getData());
            $compraArray = $compra-> getFilteredFields();
            $hydrator = new DoctrineHydrator($this->getObjectManager());
            $compraEntity = new CompraEntity();
            $compraEntity = $hydrator-> hydrate($compraArray, $compraEntity);
            $compraEntity-> setDataCriacao(new \DateTime);
            $this-> getObjectManager()-> persist($compraEntity);

            //tratando estoque
            //$produtoEstoqueEntity = new ProdutoEstoque();
            $produtoEstoqueEntity = $this->getObjectManager()->getRepository('\Application\Entity\ProdutoEstoque')->findOneBy(array('produto' => $compraEntity-> getProduto()-> getId()), array('id'=> "desc"));
            if($produtoEstoqueEntity)
            {
                if(!$produtoEstoqueEntity-> getQuantidadeBaixa())
                {
                    $quantidadeOld = $produtoEstoqueEntity-> getQuantidade();
                } else
                {
                    $quantidadeOld = $produtoEstoqueEntity-> getQuantidadeBaixas();
                }
            } else
            {
                $quantidadeOld = 0;
            }

            $produtoEstoqueEntity = new ProdutoEstoque();
            $produtoEstoqueEntity-> setQuantidade($compraEntity-> getQuantidade() + $quantidadeOld);
            $produtoEstoqueEntity-> setProduto($compraEntity-> getProduto());

            $this-> getObjectManager()-> persist($produtoEstoqueEntity);
            $this-> getObjectManager()-> flush();
            //$logger-> info("\n" . __LINE__ ." getId: \n". print_r($compraEntity-> getId(), true));

            if($this-> getObjectManager()-> contains($compraEntity))
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
