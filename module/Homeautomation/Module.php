<?php
namespace Homeautomation;

use Homeautomation\Model\Socket;
use Homeautomation\Model\SocketTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module {
	
	public function getAutoloaderConfig() {
		return array(
			'Zend\Loader\ClassMapAutoloader' => array(
				__DIR__ . '/autoload_classmap.php',
			),
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}

	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}	
	
	public function getServiceConfig() {
		return array(
			'factories' => array(
				'Socket\Model\SocketTable' =>  function($sm) {
					$tableGateway = $sm->get('SocketTableGateway');
					$table = new SocketTable($tableGateway);
					return $table;
				},
				'SocketTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Socket());
					return new TableGateway('socket', $dbAdapter, null, $resultSetPrototype);
				},
			),
		);
	}
	
}