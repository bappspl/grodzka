<?php
namespace Pharmacy\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class PharmacyForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Pharmacy');
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'id'
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'id' => 'name',
                'type'  => 'text',
                'placeholder' => 'Wprowadź nazwę'
            ),
            'options' => array(
                'label' => 'Nazwa',
            ),
        ));

        $this->add(array(
            'name' => 'city',
            'attributes' => array(
                'id' => 'city',
                'type'  => 'text',
                'placeholder' => 'Wprowadź miasto'
            ),
            'options' => array(
                'label' => 'Miasto',
            ),
        ));

        $this->add(array(
            'name' => 'street',
            'attributes' => array(
                'id' => 'street',
                'type'  => 'text',
                'placeholder' => 'Wprowadź ulicę'
            ),
            'options' => array(
                'label' => 'Ulica',
            ),
        ));

        $this->add(array(
            'name' => 'zip_code',
            'attributes' => array(
                'id' => 'zip_code',
                'type'  => 'text',
                'placeholder' => 'Wprowadź kod pocztowy'
            ),
            'options' => array(
                'label' => 'Kod pocztowy',
            ),
        ));

        $this->add(array(
            'name' => 'phone',
            'attributes' => array(
                'id' => 'phone',
                'type'  => 'text',
                'placeholder' => 'Wprowadź telefon'
            ),
            'options' => array(
                'label' => 'Telefon',
            ),
        ));

        $this->add(array(
            'name' => 'weekdays',
            'attributes' => array(
                'id' => 'weekdays',
                'type'  => 'text',
                'placeholder' => 'Wprowadź godziny otwarcia w dni powszednie'
            ),
            'options' => array(
                'label' => 'Dni powszednie',
            ),
        ));

        $this->add(array(
            'name' => 'saturday',
            'attributes' => array(
                'id' => 'saturday',
                'type'  => 'text',
                'placeholder' => 'Wprowadź godziny otwarcia w sobote'
            ),
            'options' => array(
                'label' => 'Sobota',
            ),
        ));

        $this->add(array(
            'name' => 'sunday',
            'attributes' => array(
                'id' => 'sunday',
                'type'  => 'text',
                'placeholder' => 'Wprowadź godziny otwarcia w niedziele'
            ),
            'options' => array(
                'label' => 'Niedziela',
            ),
        ));

        $this->add(array(
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control',
                'name' => 'place_id',
            ),
            'options' => array(
                'label' => 'Miejsca',
                'value_options' => array(
                ),
                'disable_inarray_validator' => true
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Zapisz',
                'id' => 'submit',
                'class' => 'btn btn-primary pull-right'
            ),
        ));
    }
}