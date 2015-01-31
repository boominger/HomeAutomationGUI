<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Homeautomation\Controller\Homeautomation' => 'Homeautomation\Controller\HomeautomationController',
		),
	),
	// The following section is new and should be added to your file
	'router' => array(
		'routes' => array(
			'homeautomation' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/homeautomation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Homeautomation\Controller\Homeautomation',
						'action'     => 'index',
					),
				),
			),
		),
	),
	'view_manager' => array(
		'template_path_stack' => array(
			'homeautomation' => __DIR__ . '/../view',
		),
	),
);