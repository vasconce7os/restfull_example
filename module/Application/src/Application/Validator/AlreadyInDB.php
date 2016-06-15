<?php
namespace Application\Validator;
use Zend\Validator\AbstractValidator;
class AlreadyInDB extends AbstractValidator
{
    private $em = null;
    private $userLogged = null;

    const ALREADY_IN_DB = 'AlreadyInDB';

    protected $messageTemplates =
    //array("lol");
    array(
        self::ALREADY_IN_DB => "Usuário já cadastrado!"
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
        $this->setValue($value);
        $usuario = $this-> em-> getRepository('\Application\Entity\Usuario')-> findBy(array('nomeUsuario'=> $this-> value));
        if ($usuario)
        {
            $this-> error(self::ALREADY_IN_DB);
            return false;
        }
        return true;
    }
}
