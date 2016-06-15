<?php
namespace Application\Form;
use Zend\Form\Form;
class UsuarioForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('usuario');
        $this-> add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'nomeUsuario',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Nome',
            ),
        ));
    }
}
