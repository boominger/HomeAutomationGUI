<?php
namespace Homeautomation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Json\Json;

use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;

//TODO list:
//Model für rest api
//Temperatur regelmäßig anfordern und loggen CREATE TABLE temperature datetime, temp
//Cron schedule aq, temp; -> Crons in Zend2? http://stackoverflow.com/questions/19752109/how-to-run-cron-job-with-zend-framework-2

class HomeautomationController extends AbstractActionController {
	
	const REST_API_URL = 'http://192.168.1.14/';

	protected $_socketTable;
	
	public function getSocketTable() {
		if (!$this->_socketTable) {
			$sm = $this->getServiceLocator();
			$this->_socketTable = $sm->get('Socket\Model\SocketTable');
		}
		return $this->_socketTable;
	}

	public function indexAction() {
    	return new ViewModel(array(
    		'sockets' => $this->getSocketTable()->fetchAll(),
    	));
    }
    
    public function sysinfoAction() {
    	return new ViewModel(array(
    		'temperature' => $this->_getTemperature()
    	));
    }
    
    public function ajaxAction() {
    	$jsonResponse = array('success' => false);
    	if($this->getRequest()->getQuery()->mode == 'socket') {
			$socketId = (int)$this->getRequest()->getQuery()->socket;
			$status = $this->getRequest()->getQuery()->status;
	    	$jsonResponse['success'] = $this->_handleSocket($socketId, $status);
    	} elseif($this->getRequest()->getQuery()->mode == 'temperature') {
			$jsonResponse['success'] = true;
			$jsonResponse['value'] = $this->_getTemperature();
		}
    	
		$jsonResponse = Json::encode($jsonResponse);
		$this->getResponse()->getHeaders()->addHeaders(array('Content-Type' => 'application/json;charset=UTF-8'));
    	return $this->getResponse()->setContent($jsonResponse);
    }
	
	private function _getTemperature() {
		$request = new Request();
		$request->getHeaders()->addHeaders(array(
			'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
		));
		$request->setUri(self::REST_API_URL.'temp/get/');
		$request->setMethod('GET');

		$client = new Client();
		$response = $client->dispatch($request);
		if($response->getStatusCode() == '200') {
			$data = json_decode($response->getBody(), true);
			if($data['success']); {
				return $data['value'];
			}
		} else {
			return false;
		}
		$data = json_decode($response->getBody(), true);
	}
	
	private function _handleSocket($socketId, $status) {
		$socket = $this->getSocketTable()->load($socketId);
		if($socket->id == $socketId) {
			if($status == 'on') {
				$transmitCode = $socket->code_on;
				$newStatus = 1;
			} elseif($status == 'off') {
				$transmitCode = $socket->code_off;
				$newStatus = 0;
			}
			
			$request = new Request();
			$request->getHeaders()->addHeaders(array(
				'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
			));
			$request->setUri(self::REST_API_URL.'socket/'.$transmitCode.'/');
			$request->setMethod('GET');

			$client = new Client();
			$response = $client->dispatch($request);
			if($response->getStatusCode() == '200') {
				$data = json_decode($response->getBody(), true);
				if($data['success']) {
					$socket->current_status = $newStatus;
					$this->getSocketTable()->save($socket);
				}
				return $data['success'];
			} else {
				return false;
			}
		}
	}
	
}