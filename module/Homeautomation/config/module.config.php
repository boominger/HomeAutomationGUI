<?php
return array(
		'controllers' => array(
				'invokables' => array(
						'Hapi\Controller\Hapi' => 'Hapi\Controller\HapiController',
				),
		),
		// The following section is new and should be added to your file
		'router' => array(
				'routes' => array(
						'hapi' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/hapi[/:action][/:id]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[0-9]+',
										),
										'defaults' => array(
												'controller' => 'Hapi\Controller\Hapi',
												'action'     => 'index',
										),
								),
						),
				),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'hapi' => __DIR__ . '/../view',
				),
		),
);