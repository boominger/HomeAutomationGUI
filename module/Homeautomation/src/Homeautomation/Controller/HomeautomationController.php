<?php
namespace Homeautomation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Session\Container;
use Zend\Session\SessionManager;

use Zend\Json\Json;

use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;

class HomeautomationController extends AbstractActionController
{
	const REST_API_URL = 'http://localhost/restApiUrl/'; //TODO write REST API and change url

	private $_socketArray = array(
		//Conrad
		1 => array(
			true => 5510485,
			false => 5510484
		),
		2 => array(
			true => 5522773,
			false => 5522772
		),
		3 => array(
			true => 5525845,
			false => 5525844
		),
		//Pollin
		4 => array(
			true => 1066321,
			false => 1066324
		),
		5 => array(
			true => 1069393,
			false => 1069396
		),
		6 => array(
			true => 1070161,
			false => 1070164
		)
	);

    public function indexAction()
    {
    	$session = new Container('homeautomation');
    	
    	return new ViewModel(array(
    		'debug' => $session->formData,
    	));
    }
    
    public function ajaxAction() {
    	$jsonResponse = array('success' => false);
    	if($this->getRequest()->getQuery()->mode = 'socket') {
			$socketId = (int)$this->getRequest()->getQuery()->socket;
			$status = ($this->getRequest()->getQuery()->status == 'on' ? true : false);
	    	$jsonResponse['success'] = $this->_handleSocket($socketId, $status);
    	}
    	
		$jsonResponse = Json::encode($jsonResponse);
		$this->getResponse()->getHeaders()->addHeaders(array('Content-Type' => 'application/json;charset=UTF-8'));
    	return $this->getResponse()->setContent($jsonResponse);
    }
	
	private function _handleSocket($socketId, $status) {
		if(isset($this->_socketArray[$socketId][$status])) {
			$request = new Request();
			$request->getHeaders()->addHeaders(array(
				'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
			));
			$request->setUri(self::REST_API_URL);
			$request->setMethod('POST');
			$request->setPost(new Parameters(array('transmit' => $this->_socketArray[$socketId][$status])));

			$client = new Client();
			$response = $client->dispatch($request);
			if($response->getStatusCode() == '200') {
				$data = json_decode($response->getBody(), true);
				return $data['success'];
			} else {
				return false;
			}
			$data = json_decode($response->getBody(), true);
		}
	}
}