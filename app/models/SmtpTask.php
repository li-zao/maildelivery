<?php
class SmtpTask extends Common
{
	protected $_name = "smtp_task";
	protected $_primary = 'id';
	
	/*
	 * Get all users
	 */
    public function addSmtpTask ( $data ) {
		return $this->insert ( $data );
	} 
     
	public function getAllCountByCon($con, $ips="") {
		$db = $this->getAdapter ();
		$sql = "select count(id) from mr_smtp_task where 1=1".$ips." ".$con;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0]['count(id)'];
	}
	
	public function getAllInfosByCon($con, $ips="") {
		$db = $this->getAdapter ();
		$sql = "select * from mr_smtp_task where 1=1".$ips." ".$con;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getTaskDetailsByCon ($con) {
		$db = $this->getAdapter ();
		$stmt = $db->query ( $con );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getTaskDetailsCountByCon ($con) {
		$db = $this->getAdapter ();
		$sql = "select count(id) from mr_smtp_task_log where 1=1".$con;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0]['count(id)'];
	}
	
	public function getSmtpInQueueNum () {
		$db = $this->getAdapter ();
		$sql = "select count(id) from mr_smtp_task where 1=1 and status='4' or status='1' or status='0'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
        if (!empty($info[0]['count(id)'])) {
            return $info[0]['count(id)'];
        } else {
            return 0;
        }
	}
	
	public function delTask ($id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->delete($where);
	}

	public function getSmtpDQueueNum () {
		$db = $this->getAdapter ();
		$sql = "select count(id) from mr_smtp_task where 1=1 and status='3' or status='5'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		if (!empty($info[0]['count(id)'])) {
            return $info[0]['count(id)'];
        } else {
            return 0;
        }
	}
	
	public function getInfoByTid ($id) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_smtp_task where id='".$id."'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function updateTask($id) {
		$db = $this->getAdapter ();
		$sql = "update mr_smtp_task set status=1, retries=0 where id='".$id."' and (status=3 or status=5 or status=6 or status=7)";
		$stmt = $db->query ( $sql );
	}
	
	public function getPast24HourStats($timespan) {
		if (empty($timespan) || !is_numeric($timespan)) {
			$timespan = 24;
		}
		$date = date('Y-m-d H:i:s',time());
		$yesterday  = mktime(date("H")-$timespan, 0, 0, date("m") , date("d"), date("Y"));
		$yesterday = date('Y-m-d H:00:00',$yesterday);
		$current  = mktime(date("H"), 0, 0, date("m") , date("d"), date("Y"));
		$current = date('Y-m-d H:00:00',$current);
		$return_arr = array();
		
		$sql = "SELECT * FROM mr_smtp_task_stats where runtime<'".$current."' and runtime>='".$yesterday."'";
		$sql = $sql." order by runtime ASC";
		$db = $this->getAdapter ();
		$stmt = $db->query ( $sql );
		$stats = $stmt->fetchAll ();
		$return_arr['smtp'] = $stats;
		
		$sql = "SELECT * FROM mr_task_stats where runtime<'".$current."' and runtime>='".$yesterday."'";
		$sql = $sql." order by runtime ASC";
		$db = $this->getAdapter ();
		$stmt = $db->query ( $sql );
		$stats = $stmt->fetchAll ();
		$return_arr['task'] = $stats;
		return $return_arr;
	}
	
	public function getTotalStats () {
		$db = $this->getAdapter ();
		$return_array = array();
		// smtp 
		$sql = "SELECT SUM(total) AS total, SUM(success) AS success, SUM(failure) AS failure, SUM(softfailure) AS softfailure FROM mr_smtp_task_stats";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		$return_array['smtp'] = $info[0];
		// task 
		$sqls = "select sum(total) as total, sum(success) as success, sum(failure) as failure, SUM(softfailure) AS softfailure from mr_task_stats";
		$stmt = $db->query ( $sqls );
		$info = $stmt->fetchAll();
		$return_array['task'] = $info[0];
		return $return_array;
	}
	
	public function getCurrentHourStats() {
		$date = date('Y-m-d H',time());
		// smtp 
		$db = $this->getAdapter ();
		$sql = 'SELECT COUNT(id) AS total, status FROM mr_smtp_task WHERE 1=1 and taskid>=1 and runtime like "%'.$date.'%" GROUP BY status';
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		$task_success = 0;
		$task_failure = 0;
		$task_softfailure = 0;
		foreach ($info as $cals) {
			if ($cals['status'] == 2){
				$task_success = $cals['total'];        
			} elseif ($cals['status'] == 3){
				$task_failure = $cals['total'];
			} elseif ($cals['status'] == 5){
				$task_softfailure = $cals['total'];
			}
		}
		$task_total = $task_success + $task_failure + $task_softfailure;
		// task
		$db = $this->getAdapter ();
		$sqls = 'SELECT COUNT(id) AS total, status FROM mr_smtp_task WHERE 1=1 and taskid<=0 and runtime like "%'.$date.'%" GROUP BY status';
		$stmt = $db->query ( $sqls );
		$info = $stmt->fetchAll();
		$smtp_success = 0;
		$smtp_failure = 0;
		$smtp_softfailure = 0;
		foreach ($info as $cals) {
			if ($cals['status'] == 2){
				$smtp_success = $cals['total'];        	//success mail
			} elseif ($cals['status'] == 3 ){
				$smtp_failure = $cals['total'];
			} elseif ($cals['status'] == 5){
				$smtp_softfailure = $cals['total'];
			}
		}
		$smtp_total = $smtp_success + $smtp_failure + $smtp_softfailure;//$dm_wait
		$return_array = array();
		$return_array['task']['success'] = $task_success;
		$return_array['task']['failure'] = $task_failure;
		$return_array['task']['softfailure'] = $task_softfailure;
		$return_array['task']['total'] = $task_total;
		$return_array['smtp']['success'] = $smtp_success;
		$return_array['smtp']['failure'] = $smtp_failure;
		$return_array['smtp']['softfailure'] = $smtp_softfailure;
		$return_array['smtp']['total'] = $smtp_total;
		return $return_array;
	}
	
    public function cleanSmtpLogs ( $date, $type = "" ) {
		$db = $this->getAdapter ();
		// mr_smtp_accesslog
		$sql = "delete from mr_smtp_accesslog where logtime <= '".$date."'";
		$stmt = $db->query ( $sql );
		// mr_smtp_task
		$sql = "delete from mr_smtp_task where runtime <= '".$date."'";
		$stmt = $db->query ( $sql );
		// mr_smtp_task_stats
		$sql = "delete from mr_smtp_task_stats where runtime <= '".$date."'";
		$stmt = $db->query ( $sql );
	}
    
	public function execSqlForMail($sql) {
		$db = $this->getAdapter ();
		$stmt = $db->query ( $sql );
		return $stmt;
	}
}
?>