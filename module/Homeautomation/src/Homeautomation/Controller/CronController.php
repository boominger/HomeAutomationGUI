<?php
namespace Homeautomation\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class CronController extends AbstractActionController {
	
	public function indexAction() {
		echo 'Test';
		//TODO
		//Temperatur regelmäßig anfordern und loggen CREATE TABLE temperature datetime, temp
		//Cron schedule aq, temp; -> Crons in Zend2? http://stackoverflow.com/questions/19752109/how-to-run-cron-job-with-zend-framework-2
	}

}