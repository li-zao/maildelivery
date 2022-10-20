<?php
class Statistics extends Common
{
	protected $_name = "statistics";
	protected $_primary = 'id';
	
	/*
	 * Get all task
	 */
	public function getTaskinfo($sql) {
		$db = $this->getAdapter ();
		$array = $db->fetchRow($sql);
		return $array;
	}

	public function getTypeinfo($sql) {
		$db = $this->getAdapter ();
		$stmt = $db->query ( $sql );
		$array = $stmt->fetchAll();
		return $array;
	}
	

	public function searchTask ($where) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_task where ".$where."";
		$stmt = $db->query ( $sql );
		$array = $stmt->fetchAll();
		return $array;
	}

	public function selectAllTask ($sql) {
		$db = $this->getAdapter ();
		$stmt = $db->query ( $sql );
		$array = $stmt->fetchAll();
		return $array;
	}
	
	public function getAllUsers($sql) {
		$db = $this->getAdapter ();
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getSendNum($sql) {
		$db = $this->getAdapter ();
		$array = $db->fetchOne($sql);
		return $array;
	}	
}
?>