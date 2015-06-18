<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Pharmacy\Controller\Pharmacy' => 'Pharmacy\Controller\PharmacyController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/delete-pharmacy-modal'  => __DIR__ . '/../view/partial/delete-pharmacy-modal.phtml',
            'partial/delete-massive-pharmacy-modal'  => __DIR__ . '/../view/partial/delete-massive-pharmacy-modal.phtml',
        ),
        'template_path_stack' => array(
            'phamracy' => __DIR__ . '/../view'
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