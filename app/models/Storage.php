<?php
class Storage extends Common
{
	protected $_name = "storage";
	protected $_primary = 'id';
	
	/*
	 * Get all users
	 */
	public function getTempMailPath () {
		$db = $this->getAdapter ();
		$sql = "select * from mr_storage where 1=1 and sname='temp' order by id desc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getCurrentTempMailPath () {
		$db = $this->getAdapter ();
		$sql = "select spath from mr_storage where 1=1 and sname='temp' order by id desc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		if (count($info) > 0) {
			$storage = $info[0];
			if ($storage) {
				return $storage['spath'];
			}
		}		
		return "/home/maildelivery";
	}
	
	public function getAttchPath () {
		$db = $this->getAdapter ();
		$sql = "select * from mr_storage where 1=1 and sname='attach' order by id desc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function updateCycle ($data) {
		$db = $this->getAdapter();
		$sql = "update mr_storage set spath='".$data."' where sname='cycle'";
		$stmt = $db->query ( $sql );
	}
	
	public function updateLogCycle ($data) {
		$db = $this->getAdapter();
		$sql = "update mr_storage set spath='".$data."' where sname='logcycle'";
		$stmt = $db->query ( $sql );
	}
	
	public function insertInfos ($data) {
		return $this->insert($data);
	}
	
}
?>