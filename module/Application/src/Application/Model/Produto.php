<?php
namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use \Application\Entity\Produto as ProdutoEntity;
class Produto implements InputFilterAwareInterface
{
    private $id;
    private $nome;
    private $imgPath;

    protected $inputFilter;
    protected $_objectManager = null;

    public function exchangeArray($data)
    {
        $this-> id     = (isset($data['id']))     ? $data['id']     : null;
        $this-> nome = (isset($data['nome'])) ? $data['nome'] : null;
        $this-> imgPath  = (isset($data['imgPath']))  ? $data['imgPath']  : null;
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

            $inputFilter-> add($factory-> createInput(array(
                'name'     => 'nome',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'imgPath',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
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
        $produtoEntity = new ProdutoEntity();
        //$hydrator = new DoctrineHydrator($this->getObjectManager());
        //produto = $hydrator-> hydrate($data['user'], $userEntity);
        //$logger-> info("\n" . __LINE__ ." save here:\n" . print_r(get_class_vars(get_class($this)), true) . "\n");
        $logger-> info("\n" . __LINE__ ." save herew:\n" . print_r($propertiesArray, true) . "\n");



    }

    public function __get($name) {
        return $this->$name;
    }
    protected function getObjectManager()
    {
        if (!$this->_objectManager) {
            $this->_objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->_objectManager;
    }

}
