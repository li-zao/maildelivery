<?php
class Calendar extends Common
{
	protected $_name = "calendar";
	protected $_primary = 'id';
	
	/*
	 * Get all users
	 */
	public function getInfoByUid($id) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_calendar where 1=1 and uid='".$id."'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function insertInfo($data) {
		return $this->insert($data);
	}
	
	public function updateInfo($data, $id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->update($data, $where);
	}
	
	public function getInfoByEventID($id) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_calendar where 1=1 and id='".$id."'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function deleteInfoByEventID($id) {
		$db = $this->getAdapter ();
		$sql = "delete from mr_calendar where 1=1 and id='".$id."'";
		$stmt = $db->query ( $sql );
	}
	
	public function checkEventDuration ($s, $e, $userid) {
		$db = $this->getAdapter ();
		$sql = "select count(id) from mr_calendar where uid =".$userid." and ( (starttime>'".$s." 00:00:00' and starttime<'".$e." 23:59:59') or (endtime>'".$s." 00:00:00' and endtime<'".$e." 23:59:59') );";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0]['count(id)'];
	}
}
?>