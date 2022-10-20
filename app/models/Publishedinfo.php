<?php
class Publishedinfo extends Common
{
	protected $_name = "publishedinfo";
	protected $_primary = 'id';
	
	/*
	 * Get all users
	 */
	public function getAllCountByCon($con) {
		$db = $this->getAdapter ();
		$sql = "select count(id) from mr_publishedinfo where 1=1 ".$con;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0]['count(id)'];
	} 
	
	public function getAllInfosByCon($con) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_publishedinfo where 1=1 ".$con;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getAllInfos() {
		$db = $this->getAdapter ();
		$sql = "select * from mr_publishedinfo where 1=1 order by id desc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getInfosByID($id) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_publishedinfo where 1=1 and id='".$id."'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0];
	}
	
	public function insertInfo($data) {
		return $this->insert($data);
	}
	
	public function updateInfo($data, $id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->update($data, $where);
	}
	
	public function getInfosByLimit() {
		$db = $this->getAdapter ();
		$sql = "select * from mr_publishedinfo where 1=1 order by id desc limit 5";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function deleteInfo($id) {
		$db = $this->getAdapter ();
		$sql = "delete from mr_publishedinfo where 1=1 and id='".$id."'";
		$stmt = $db->query ( $sql );
	}
}
?>