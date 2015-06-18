<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Medicaments\Controller\Medicaments' => 'Medicaments\Controller\MedicamentsController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/delete-medicaments-modal'  => __DIR__ . '/../view/partial/delete-medicaments-modal.phtml',
            'partial/delete-massive-medicaments-modal'  => __DIR__ . '/../view/partial/delete-massive-medicaments-modal.phtml',
        ),
        'template_path_stack' => array(
            'medicaments' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    )
);