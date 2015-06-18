<?php
return array(
    'pharmacy-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/pharmacy',
            'defaults' => array(
                'module' => 'Pharmacy',
                'controller' => 'Pharmacy\Controller\Pharmacy',
                'action'     => 'list',
            ),
        ),
    ),
    'pharmacy' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/pharmacy',
            'defaults' => array(
                'module' => 'Pharmacy',
                'controller' => 'Pharmacy\Controller\Pharmacy',
                'action'     => 'list',
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'Pharmacy',
                        'controller' => 'Pharmacy\Controller\Pharmacy',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:pharmacy_id',
                    'defaults' => array(
                        'module' => 'Pharmacy',
                        'controller' => 'Pharmacy\Controller\Pharmacy',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'pharmacy_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:pharmacy_id',
                    'defaults' => array(
                        'module' => 'Pharmacy',
                        'controller' => 'Pharmacy\Controller\Pharmacy',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'pharmacy_id' => '[0-9]+'
                    ),
                ),
            ),
        ),
    ),
);