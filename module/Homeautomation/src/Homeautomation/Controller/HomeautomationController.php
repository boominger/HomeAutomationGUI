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
	    	if(isset($_REQUEST['data']['led']['priority']) && isset($_REQUEST['data']['led']['brightness']) && isset($_REQUEST['data']['led']['color']) && isset($_REQUEST['data']['led']['status'])) {
	    		exec("kill $(ps aux | grep '[b]oblight-constant' | awk '{print $2}')");
	    		if($_REQUEST['data']['led']['status'] == 'on') {
	    			exec("boblight-constant -p ".$_REQUEST['data']['led']['priority']." -o value=".$_REQUEST['data']['led']['brightness']." ".substr($_REQUEST['data']['led']['color'], 1));
	    		}
	    	}
	    	$jsonResponse['success'] = true;
    	}
    	
    	//TODO Dirty
    	echo json_encode($jsonResponse);
    	exit();
    }
}