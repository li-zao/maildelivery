<?php
class UserIntercept extends Common {
	protected $_name = "userintercept";
	protected $_primary = 'id';
	
	public function getAllUsers($conn) {
		$db = $this->getAdapter ();
		$sql = "SELECT * FROM ".$this->_name." ".$conn;
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		return $domains;
	}
	
	public function getAllCount() {
		$db = $this->getAdapter ();
		$sql = "SELECT count(id) FROM ".$this->_name;
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		return $domains[0]['count(id)'];
	}
	
	
	
	public function addUser($data) {		
		return $this->insert($data);
	}
	
	public function updateUser($data, $userid) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $userid);
		return $this->update($data, $where);
	}
	
	public function delUser($id)
	{
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->delete($where);
	}
	
	public function getUserinfo($id) {
		$db = $this->getAdapter ();
		$sql = "SELECT * FROM ".$this->_name." where id = '".$id."'";
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		return $domains[0];
	}
	
	public function getUserinfoBymail($mail) {
		$db = $this->getAdapter ();
		$sql = "SELECT * FROM ".$this->_name." where mailbox = '".$mail."'";
		$stmt = $db->query ( $sql );
		$rows = $stmt->fetchAll();
		return $rows[0];
	}
}
?>