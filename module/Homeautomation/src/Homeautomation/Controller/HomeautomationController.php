<?php
namespace Homeautomation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Json\Json;

class HomeautomationController extends AbstractActionController {

	protected $_socketTable;
	
	public function getSocketTable() {
		if (!$this->_socketTable) {
			$sm = $this->getServiceLocator();
			$this->_socketTable = $sm->get('Socket\Model\SocketTable');
		}
		return $this->_socketTable;
	}

	public function indexAction() {	
		$this->_initTranslations();
		
    	return new ViewModel(array(
    		'sockets' => $this->getSocketTable()->fetchAll(),
    	));
    }
    
    public function ajaxAction() {
    	$jsonResponse = array('success' => false);
    	if($this->getRequest()->getQuery()->mode == 'socket') {
			$socketId = (int)$this->getRequest()->getQuery()->socket;
			$status = $this->getRequest()->getQuery()->status;
	    	$jsonResponse['success'] = $this->_handleSocket($socketId, $status);
		}
    	
		$jsonResponse = Json::encode($jsonResponse);
		$this->getResponse()->getHeaders()->addHeaders(array('Content-Type' => 'application/json;charset=UTF-8'));
    	return $this->getResponse()->setContent($jsonResponse);
    }
    
    public function statusAction() {
        $jsonResponse = array('active' => false);
        
        $socketId = $this->getRequest()->getQuery()->socket;
        if($socketId) {
            $socket = $this->getSocketTable()->load($socketId);
            $jsonResponse['active'] = ($socket->current_status == 1 ? true : false);
        }
        
        $jsonResponse = Json::encode($jsonResponse);
        $this->getResponse()->getHeaders()->addHeaders(array('Content-Type' => 'application/json;charset=UTF-8'));
        return $this->getResponse()->setContent($jsonResponse);
    }
	
    private function _initTranslations() {
    	$loc = $this->getServiceLocator();
    	$translator = $loc->get('translator');
    	$translator->addTranslationFile("phparray", './module/Homeautomation/language/lang.array.de.php');
    	$loc->get('ViewHelperManager')->get('translate')->setTranslator($translator);
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
			
			$restClient = new \Homeautomation\Model\RestApi('socket', $transmitCode);
			$response = $restClient->request();
			if($response && isset($response['success']) && $response['success'] == true) {
				$socket->current_status = $newStatus;
				$this->getSocketTable()->save($socket);
				return true;
			} else {
				return false;
			}
		}
	}
	
}
