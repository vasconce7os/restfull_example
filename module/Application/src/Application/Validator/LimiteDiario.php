<?php
namespace Application\Validator;
use Zend\Validator\AbstractValidator;
class LimiteDiario extends AbstractValidator
{
    private $em = null;
    private $produtoId;
    private $quantidadeVenda;

    const LIMIT_DAY_EXCEEDED = 'Zica';

    protected $messageTemplates =
    array(
        self::LIMIT_DAY_EXCEEDED => "Esta venda não pode ser realizado pois excede o limite diário de 50 itens!"
    );


    public function __construct($options)
    {
        parent::__construct();
        if((gettype($options['em']) == "object" && ("Doctrine\ORM\EntityManager" == get_class($options['em']))))
        {
            $this-> setEm($options['em']);
        } else
        {
            throw new \Exception("O Validator não recebeu objeto Doctrine\ORM\EntityManager!");
        }
        $this-> produtoId = $options['produtoId'];

        $this-> quantidadeVenda = $options['quantidadeVenda'];
    }

    public function setEm($em = null)
    {
        $this-> em = $em;
    }

    public function getEm()
    {
        return $this-> em;
    }

    public function isValid($value)
    {
        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        $logger->addWriter($writer);
        //$logger-> info("\n" . __LINE__ ."  " .get_class($this) . "; isValid() \n : \n". print_r($data, true));
        //$logger-> info("\n" . __LINE__ ."  " .get_class($this) . "; isValid()");

        $this-> setValue($value);
        //$compra = $this-> getEm()-> getRepository('\Application\Entity\Compra')-> findBy(array('produto'=> ));
        $repository = $this-> getEm()-> getRepository('\Application\Entity\Compra');
        $qb = $repository->createQueryBuilder('compra')
            ->where('DATE_DIFF(compra.dataCriacao, CURRENT_DATE()) = 0')
            ->andwhere('compra.produto = :produtoId')
            ->setParameter('produtoId', $this-> produtoId)
            ;

        //$logger-> info("\n" . __LINE__ ." produtoId in vali:" . ";  \n". print_r( $this-> produtoId, true));
        $query = $qb-> getQuery();

        //$logger-> info("\n" . __LINE__ ." query:" . ";  \n". print_r($query, true));

        //this doesn't work
        $lCompras = $query-> getResult();

        // estou com prequiça de pesquisar o sum
        $itensToday = 0;
        foreach ($lCompras as $key => $compraDB)
        {
            $itensToday += $compraDB-> getQuantidade();
            if($key == 0)
            {
                //$inDB = \Doctrine\Common\Util\Debug::export($compraDB, true);
                //$logger-> info("\n" . __LINE__ ."  " . "; isValid() \n compraDB: \n". print_r($inDB, true));
            }
        }
        $logger-> info("\n" . __LINE__ . "\nitensTotal: ". print_r($itensToday, true));


        //echo "<pre>";
        //$inDB = \Doctrine\Common\Util\Debug::export($totalrows, true);

        //$logger-> info("\n" . __LINE__ ."  " .get_class($this) . "; isValid() \n inDB: \n". print_r($inDB, true));
        //exit;

        $logger-> info("\n" . __LINE__ . "\nquantidade que ficará: ". print_r(($this-> quantidadeVenda + $itensToday), true));
        //return true;
        if (($this-> quantidadeVenda + $itensToday) > 50)
        {
            $this-> error(self::LIMIT_DAY_EXCEEDED);
            return false;
        }
        return true;
    }
}
