<?php
namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
class Usuario implements InputFilterAwareInterface
{
    private $id;
    private $nomeUsuario;

    protected $inputFilter;
    private $em; // usado para o validator

    public function exchangeArray($data)
    {
        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        $logger->addWriter($writer);
        $this-> id     = (isset($data['id']))     ? $data['id']     : null;
        $this-> nomeUsuario = (isset($data['nomeUsuario'])) ? $data['nomeUsuario'] : null;
    }

    public function setEm($em = null)
    {
        $this-> em = $em;
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
                'name'     => 'nomeUsuario',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    /*array(
                        'name'    => 'IsInt',
                        'options' => array(),
                    ),*/
                    array(
                        'name'    => '\Application\Validator\AlreadyInDB',
                        'options' => array
                        (
                            'em'       => $this-> em,
                        ),
                    ),

                ),
            )));
            //$logger-> info("\n" . __LINE__ ."  depois getInputFilter " . print_r($this-> quantidade, true) . ";");

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
