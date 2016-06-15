<?php
namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
class Compra implements InputFilterAwareInterface
{
    private $id;
    private $quantidade;
    private $valueProduct;
    private $dataCriacao;
    private $produto;
    private $fornecedor;

    protected $inputFilter;
    private $em; // usado para o validator

    public function exchangeArray($data)
    {
        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        $logger->addWriter($writer);
        $this-> id     = (isset($data['id']))     ? $data['id']     : null;
        $this-> quantidade = (isset($data['quantidade'])) ? $data['quantidade'] : null;
        $this-> valueProduct  = (isset($data['valueProduct']))  ? $data['valueProduct']  : null;
        $this-> dataCriacao  = (isset($data['dataCriacao']))  ? $data['dataCriacao']  : null;
        $this-> produto  = (isset($data['produto']))  ? $data['produto']  : null;
        $this-> fornecedor  = (isset($data['fornecedor']))  ? $data['fornecedor']  : null;

        //$logger-> info("\n" . __LINE__ ."  " .get_class($this) . "; isValid() \n : \n". print_r($data, true));
        //$logger-> info("\n" . __LINE__ ." exchangeArray " . print_r($this-> produto, true) . ";");

    }

    public function setEm($em = null)
    {
        $this-> em = $em;
    }


    public function setProtudo($produto)
    {

        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        $logger->addWriter($writer);

        //$logger-> info("\n" . __LINE__ ." setProtudo: " . print_r($produto, true) . ";");

        $this-> produto = $produto;
    }


    public function setQuantidade($quantidade)
    {
        $this-> quantidade = $quantidade;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        $logger->addWriter($writer);

        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory-> createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            //$logger-> info("\n" . __LINE__ ." getInputFilter " . print_r($this-> produto, true) . ";");

            $inputFilter-> add($factory-> createInput(array(
                'name'     => 'quantidade',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'IsInt',
                        'options' => array(),
                    ),
                    array(
                        'name'    => 'Between',
                        'options' => array(
                            'min' => 1,
                            'max' => 50,
                        ),
                    ),

                    array(
                        'name'    => '\Application\Validator\LimiteDiario',
                        'options' => array
                        (
                            'em'       => $this-> em,
                            'produtoId'=> $this-> produto['id'],
                            'quantidadeVenda'=> $this-> quantidade,
                        ),
                    ),

                ),
            )));
            //$logger-> info("\n" . __LINE__ ."  depois getInputFilter " . print_r($this-> quantidade, true) . ";");

            $inputFilter->add($factory->createInput(array(
                'name'     => 'valueProduct',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'IsFloat',
                        'options' => array
                        (
                            'locale' => 'en_US'
                        ),
                    ),
                ),
            )));


            $inputFilter->add($factory->createInput(array(
                'name'     => 'produto',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => '\Application\Validator\ProdutoExiste',
                        'options' => array
                        (
                            'em'       => $this-> em,
                        ),
                    ),
                ),
            )));


            $inputFilter->add($factory->createInput(array(
                'name'     => 'fornecedor',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => '\Application\Validator\FornecedorExiste',
                        'options' => array
                        (
                            'em'       => $this-> em,
                        ),
                    ),
                ),
            )));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }


    public function getFilteredFields()
    {
        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        $logger->addWriter($writer);


        $reflect = new \ReflectionClass($this);
        $properties = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE);

        $propertyName = array();
        $propertiesArray = array();
        foreach ($properties as $key => $prop)
        {
            $propertyName[$key]['name'] = ($prop-> name);
            $propertyName[$key]['value'] = $this-> __get($prop-> name);
            //if($prop-> name != "id")
            {
                $propertiesArray[$prop-> name] = $this-> __get($prop-> name);
            }

        }
        return $propertiesArray;
    }

    public function __get($name) {
        return $this->$name;
    }

}
