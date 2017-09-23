<?php
namespace Homeautomation\Model;

use Zend\Db\TableGateway\TableGateway;

class SocketTable {
	
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll() {
		$resultSet = $this->tableGateway->select(array('status' => 1));
		return $resultSet;
	}

	public function load($id) {
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function save(Socket $socket) {
		$data = array(
			'label' => $socket->label,
			'code_on' => $socket->code_on,
			'code_off' => $socket->code_off,
			'current_status' => $socket->current_status,
			'status' => $socket->status
		);

		$id = (int) $socket->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->load($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('ID does not exist');
			}
		}
	}

	public function delete($id) {
		$this->tableGateway->delete(array('id' => (int) $id));
	}
	
}
