<?php
namespace Homeautomation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Session\SessionManager;

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
    	if(isset($_REQUEST['data'])) {
	    	$session = new Container('homeautomation');
	    	$session->formData = $_REQUEST['data'];
			//TODO logic
	    	$jsonResponse['success'] = true;
    	}
    	
    	//TODO Dirty
    	echo json_encode($jsonResponse);
    	exit();
    }
}