<?php
namespace Application\Form;
use Zend\Form\Form;
class CompraForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('compra');
        $this-> add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'quantidade',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Quantidade',
            ),
        ));

        $this->add(array(
            'name' => 'valueProduct',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Valor do produto',
            ),
        ));

        $this->add(array(
            'name' => 'produto',
            'attributes' => array(
                'type'  => 'int',
            ),
            'options' => array(
                'label' => 'Produto',
            ),
        ));

        $this->add(array(
            'name' => 'fornecedor',
            'attributes' => array(
                'type'  => 'int',
            ),
            'options' => array(
                'label' => 'Fornecedor',
            ),
        ));
    }
}
