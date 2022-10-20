<?php
class Task extends Common
{
	protected $_name = "task";
	protected $_primary = 'id';
	
	/*
	 * Get all task
	 */
	public function getLogininfo($sql) {
		$db = $this->getAdapter ();
		$stmt = $db->query ( $sql );
		$array = $stmt->fetchRow();
		return $array;
	}

	public function getOnetask($id) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_task where id = ".$id."";
		$array = $db->fetchRow($sql);
		return $array;
	}
	
	public function updateTask($data, $id) {
		$db = $this->getAdapter();
		$rows=$db->update('mr_task', $data, 'id=' . $id);
		return $rows;
	}
	
	public function insertTask ($data) {
		return $this->insert($data);
	}
	
	public function searchTask ($where) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_task where ".$where."";
		$stmt = $db->query ( $sql );
		$array = $stmt->fetchAll();
		return $array;
	}

	public function selectAllTask ($where) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_task where 1=1 ".$where."";
		$stmt = $db->query ( $sql );
		$array = $stmt->fetchAll();
		return $array;
	}
	
	public function getAllCountTask($where) {
		$db = $this->getAdapter ();
		$sql="select count(id) as num from mr_task where 1=1 ".$where."";
		$res = $db->fetchOne ( $sql );
		return $res;
	}
	
	public function getTaskInQueueNum () {
		$db = $this->getAdapter ();
		$sql = "select count(id) from mr_task_log where 1=1 and status='4' or status='1' or status='0'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0]['count(id)'];
	}
	
	public function getTaskDQueueNum () {
		$db = $this->getAdapter ();
		$sql = "select count(id) from mr_task_log where 1=1 and status='3' or status='5'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0]['count(id)'];
	}

	public function getTaskByTime ($time) {
		$db = $this->getAdapter ();
		$sql = "select id, uid, fid, random, task_name, groups, receivers, isreport, reportemail, sender, sendemail, domainAD, subject, data, replyemail from mr_task where draft = 0 and status=2 and (checkpass = 1 or checkpass = 3) and sendtime <> '0000-00-00 00:00:00' and ((sendtype = 2 and sendtime <= '" . $time . "' and cycle_end_time >= '" . $time . "') or (sendtype = 1 and sendtime <= '" . $time . "') or (sendtype = 0 and sendtime <= '" . $time . "')) order by modifiedtime asc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
}
?>