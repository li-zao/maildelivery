<?php
class SmtpAccessLog extends Common
{
	protected $_name = "smtp_accesslog";
	protected $_primary = 'id';
	
	public function getAllCountByCon($con) {
		$db = $this->getAdapter ();
		$sql = "select count(id) from mr_smtp_accesslog where 1=1 ".$con;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0]['count(id)'];
	}
	
	public function getAllInfosByCon($con) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_smtp_accesslog where 1=1 ".$con;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function delSmtpAccessLog ($id) {
		$db = $this->getAdapter ();
		$sql = "delete from mr_smtp_accesslog where 1=1 and id='".$id."'";
		$stmt = $db->query ( $sql );
	}
	
	public function addSmtpAccessLog($data) {
		return $this->insert($data);
	} 
}
?>	