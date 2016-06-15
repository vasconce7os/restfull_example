<?php
namespace Application\Form;

use Zend\Form\Form;

class FornecedorForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('fornecedor');
        // $this-> setAttribute('method', 'post');
        $this-> add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Nome:',
            ),
        ));
        $this->add(array(
            'name' => 'fone',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Fone',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'E-mail',
            ),
        ));

        $this->add(array(
            'name' => 'cpf',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'CPF',
            ),
        ));
    }
}
