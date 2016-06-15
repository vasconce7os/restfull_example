<?php
namespace Application\Validator;
use Zend\Validator\AbstractValidator;
class FornecedorExiste extends AbstractValidator
{
    private $em = null;
    private $userLogged = null;

    const EXIST_IN_DB_BY_DOCTRINE = 'Zica';

    protected $messageTemplates =
    array(
        self::EXIST_IN_DB_BY_DOCTRINE => "Informe um fornecedor que esteja cadastrado!"
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
        $this-> setValue($value);
        $fornecedor = $this-> em-> getRepository('\Application\Entity\Fornecedor')-> find($this-> value);
        if (!$fornecedor)
        {
            $this-> error(self::EXIST_IN_DB_BY_DOCTRINE);
            return false;
        }
        return true;
    }
}
