<?php
class Vip extends Common
{
	protected $_name = "vip";
	protected $_primary = 'id';
	
	/*
	 * Get all users
	 */
	public function getInfosByEth($eth) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_vip where 1=1 and eth='".$eth."' order by id desc limit 1";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0];
	}
	
	public function deleteMatchEthInfo ($eth) {
		$db = $this->getAdapter ();
		$sql = "delete from mr_vip where eth='".$eth."'";
		$stmt = $db->query ( $sql );
	}
	
	public function insertVip ($data) {
		return $this->insert($data);
	}
}
?>