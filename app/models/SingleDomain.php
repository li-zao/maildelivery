<?php
class SingleDomain extends Common {
	protected $_name = "singledomain";
	protected $_primary = 'id';
	
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
	
	public function getSingleDomainByID ( $id ) {
		$db = $this->getAdapter ();
		$sql = "SELECT * FROM ".$this->_name." where id=".$id;
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		return $domains;
	}
	
	public function getSingleDomainByName ( $name ) {
		$db = $this->getAdapter ();
		$sql = "SELECT * FROM ".$this->_name." where domain='".$name."' or domain='*'";
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		return $domains;
	}
	
	public function getAllCount () {
		$db = $this->getAdapter ();
		$sql = "SELECT count(id) FROM ".$this->_name;
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		return $domains[0]['count(id)'];
	}
	
	public function getAllInfos ( $con ) {
		$db = $this->getAdapter ();
		$sql = "select * from ".$this->_name." where 1=1 ".$con;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function addSingleDomain($data) {
		return $this->insert($data);
	}
	
	public function updateSingleDomain($data, $id) {
		$data_id = $id;
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $data_id);
		return $this->update($data, $where);
	}
	
	public function delRT ($id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->delete($where);
	}
}
?>