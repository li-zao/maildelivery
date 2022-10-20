<?php
class Staticmx extends Common
{
	protected $_name = "staticmx";
	protected $_primary = 'id';
	
	/*
	 * Get all users
	 */
	 
	 public function checkDomain ( $domain, $id="" ) {
		$db = $this->getAdapter ();
		$sql_tail = "";
		if ( $id != "" && $id != null ) {
			$sql_tail = " and id !='".$id."'";
		}
		$sql = "SELECT * FROM ".$this->_name." where domain='".$domain."'".$sql_tail;
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		return $domains;
	}
	 
	public function getAllInfos($con) {
		$db = $this->getAdapter ();
		$sql = "select * from ".$this->_name." where 1=1 ".$con;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getInfoByRTID($id) {
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
	
	public function updateRT($data, $id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->update($data, $where);
	}
	
	public function insertRT ($data) {
		return $this->insert($data);
	}
	
	public function delRT ($id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->delete($where);
	}
}
?>