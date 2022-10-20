<?php
class Domain extends Common {
	protected $_name = "domains";
	protected $_primary = 'id';
	
	public function getAllDomains($conn) {//
		$db = $this->getAdapter ();
		$sql = "SELECT * FROM ".$this->_name." ".$conn;
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		return $domains;
	}
	
	public function getAllCount() {//
		$db = $this->getAdapter ();
		$sql = "SELECT count(id) FROM ".$this->_name;
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		return $domains[0]['count(id)'];
	}
	
	public function getAllADDomains() {
		$db = $this->getAdapter ();
		$sql = "SELECT * FROM ".$this->_name." where protocol=3 order by id DESC";
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		return $domains;
	}
	
	public function addDomain($data) {//		
		return $this->insert($data);
	}
	
	public function updateDomain($domain, $id) {//
		$domain_id = $id;
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $domain_id);
		return $this->update($domain, $where);
	}
	
	public function delDomain($id)
	{
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->delete($where);
	}
	
	public function getDomainById($id) {
		$db = $this->getAdapter ();
		$sql = "SELECT * FROM ".$this->_name." where id='".$id."'";
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		return $domains[0];
	}
	
	public function getDomainNameById($id) {
		$domains = $this->find ( $id );
		$domain = $domains->current ();
		if ($domain != NULL) {
			return $domain['domain'];
		}
		return "";
	}
	
	public function checkDomainName($name) {
		$db = $this->getAdapter ();
		$sql = "SELECT * FROM ".$this->_name." where domain='".$name."'";
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		if (count($domains) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function delAllDomains() {
		$db = $this->getAdapter();
		$where = $db->quoteInto('1 = ?', '1');
		return $this->delete($where);
	}
	
	public function getDomainByName($name) {
		$db = $this->getAdapter ();
		$sql = "SELECT * FROM ".$this->_name." where 1=1 and domain='".$name."'";
		$sql = $sql . " limit 0,1";
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		$domain = $domains[0];
		return $domain;
	}
}
?>