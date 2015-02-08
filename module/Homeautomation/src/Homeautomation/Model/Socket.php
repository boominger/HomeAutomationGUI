<?php
namespace Homeautomation\Model;

class Socket {
	
	public $id;
	public $label;
	public $code_on;
	public $code_off;
	public $current_status;
	public $status;

	public function exchangeArray($data) {
		$this->id = (!empty($data['id'])) ? $data['id'] : null;
		$this->label = (!empty($data['label'])) ? $data['label'] : null;
		$this->code_on = (!empty($data['code_on'])) ? $data['code_on'] : null;
		$this->code_off = (!empty($data['code_off'])) ? $data['code_off'] : null;
		$this->current_status = (!empty($data['current_status'])) ? $data['current_status'] : null;
		$this->status = (!empty($data['status'])) ? $data['status'] : null;
	}
	
}