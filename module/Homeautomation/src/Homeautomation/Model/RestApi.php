<?php
namespace Homeautomation\Model;

use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;

class RestApi {
	
	const REST_API_URL = 'http://192.168.1.14/';
	
	private $_method = null;
	private $_action = null;
	private $_additionalParams = null;
	
	public function __construct($method = null, $action = null, $additionalParamsArray = array()) {
		$this->_method = $method;
		$this->_action = $action;
		$this->_additionalParams = implodex("/", $additionalParamsArray);
	}
	
	public function request() {
		$request = new Request();
		$request->getHeaders()->addHeaders(array(
			'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
		));
		$request->setUri(self::REST_API_URL.$this->_method.'/'.$this->_action.'/'.$this->_additionalParams);
		$request->setMethod('GET');
		
		$client = new Client();
		$response = $client->dispatch($request);
		if($response->getStatusCode() == '200') {
			$data = json_decode($response->getBody(), true);
			if($data['success']) {
				return $data;
			}
		}
		
		return false;
	}
	
}