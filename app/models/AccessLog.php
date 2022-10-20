<?php
class AccessLog extends Common {
	protected $_name = "accesslog";
	protected $_primary = 'id';
	
	public function addLog($data) {
		return $this->insert($data);
	}
	
	public function getAllCountByCon($con) {
		$db = $this->getAdapter ();
		$sql = "select count(id) from mr_accesslog where 1=1 ".$con;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0]['count(id)'];
	}
	
	public function getAllInfosByCon($con) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_accesslog where 1=1 ".$con;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
}
?>