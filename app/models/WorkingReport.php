<?php
class WorkingReport extends Common
{
	protected $_name = "workingreport";
	protected $_primary = 'id';
	
	/*
	 * Get all users
	 */
	public function getAllInfos($con) {
		$db = $this->getAdapter ();
		$sql = "select * from ".$this->_name." where 1=1 ".$con;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getInfoByWRID($id) {
		$db = $this->getAdapter ();
		$sql = "select * from ".$this->_name." where 1=1 and id='".$id."'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getAllCount () {
		$db = $this->getAdapter ();
		$sql = "select count(id) from ".$this->_name." where 1=1";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0]['count(id)'];
	}
	
	public function updateWR($data, $id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->update($data, $where);
	}
	
	public function insertWR ($data) {
		return $this->insert($data);
	}
	
	public function delWR ($id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->delete($where);
	}
}
?>