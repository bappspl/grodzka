<?php
return array(
    'medicaments-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/medicaments',
            'defaults' => array(
                'module' => 'Medicaments',
                'controller' => 'Medicaments\Controller\Medicaments',
                'action'     => 'list',
            ),
        ),
    ),
    'medicaments' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/medicaments',
            'defaults' => array(
                'module' => 'Medicaments',
                'controller' => 'Medicaments\Controller\Medicaments',
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
                        'module' => 'Medicaments',
                        'controller' => 'Medicaments\Controller\Medicaments',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:medicaments_id',
                    'defaults' => array(
                        'module' => 'Medicaments',
                        'controller' => 'Medicaments\Controller\Medicaments',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'medicaments_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:medicaments_id',
                    'defaults' => array(
                        'module' => 'Medicaments',
                        'controller' => 'Medicaments\Controller\Medicaments',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'medicaments_id' => '[0-9]+'
                    ),
                ),
            ),
        ),
    ),
);