<?php
namespace Homeautomation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Json\Json;

class HomeautomationController extends AbstractActionController
{
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
	    	$jsonResponse['success'] = $this->_handleSocket($this->getRequest()->getQuery()->socket, $this->getRequest()->getQuery()->status);
    	}
    	
		$jsonResponse = Json::encode($jsonResponse);
		$this->getResponse()->getHeaders()->addHeaders(array('Content-Type' => 'application/json;charset=UTF-8'));
    	return $this->getResponse()->setContent($jsonResponse);
    }
	
	private function _handleSocket($socketId, $status) {
		//TODO Session handling
		//$session = new Container('homeautomation');
		//$session->formData = $_REQUEST['data'];
		//TODO logic
		return true;
	}
}