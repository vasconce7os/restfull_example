<?php
namespace Application\Form;

use Zend\Form\Form;

class ProdutoForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('produto');
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
            'name' => 'imgPath',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Imagem',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Ok',
                'id' => 'submitbutton',
            ),
        ));
    }
}
