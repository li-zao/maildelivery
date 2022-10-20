<?php
date_default_timezone_set ( 'Asia/Shanghai' );
set_include_path ( '/var/www/maildelivery' . PATH_SEPARATOR . '/var/www/maildelivery/library' . PATH_SEPARATOR . '/var/www/maildelivery/app/models/' . PATH_SEPARATOR . get_include_path () );
//Set Zend Framework  load class automatically
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance ()->setFallbackAutoloader ( true );
Zend_Session::start ();
//Configure db
$basic = new Zend_Config_Ini ( '/var/www/maildelivery/app/config/basic.ini', null, true );
Zend_Registry::set ( 'basic', $basic );
$dbAdapter = Zend_Db::factory ( $basic->general->db->adapter, $basic->general->db->toArray () );
$dbAdapter->query ( "SET NAMES {$basic->general->db->charset}" );
Zend_Db_Table::setDefaultAdapter ( $dbAdapter );
Zend_Registry::set ( 'dbAdapter', $dbAdapter );
Zend_Registry::set ( 'db_username', $basic->general->db->username );
Zend_Registry::set ( 'db_password', $basic->general->db->password );
Zend_Registry::set ( 'db_dbname', $basic->general->db->dbname );
Zend_Registry::set ( 'dbprefix', $basic->general->db->prefix );

exec("ps -ef|grep checkTaskStatus.php|grep -v grep", $return_array, $status);
$running_count = count($return_array);
if ($running_count > 1) {
	exit('running'.PHP_EOL);
}

$taskResult = $dbAdapter->fetchAll("select id, tid, total, log from mr_task_log where status=3 order by runtime");

if (empty($taskResult)) {
	exit(addlog("task is empty ,do nothing" . PHP_EOL));
}

foreach ($taskResult as $info) {
	$tasklogid 	= $info['id'];
	$taskid 	= $info['tid'];
	$total 		= $info['total'];
	$log 		= $info['log'];

	echo addlog("start working on " . $tasklogid) . PHP_EOL;

	// 通过tasklogid 取出smtptask相应的信
	$qsql = "select taskid, tlid, status from mr_smtp_task where tlid = " . $tasklogid;
	$dbresult = $dbAdapter->fetchAll($qsql);
	if (empty($dbresult)) {
		continue;
	}

	$statisArray = array();
	$statisArray['total'] = $total;
	$statisArray['index'] = 0;
	$statisArray['succeed'] = 0;
	$statisArray['failed'] = 0;
	$statisArray['waited'] = 0; 
	foreach ($dbresult as $task) {
		$taskid = $task['taskid'];
		$tasklogid = $task['tlid'];
		$status = $task['status'];

		if ($status == '2' || $status == '4') {			// 成功，重试
			$statisArray['succeed'] ++;
		} else if ($status == '3' || $status == '5') {	//	硬退，软退
			$statisArray['failed'] ++;
		} else {
			$statisArray['waited'] ++;
		}
		$statisArray['index'] ++;
	}

	echo addlog("STATS_TASK_QUERY_SQL:". $qsql . ", count:" . $statisArray['index'] . PHP_EOL);
	$newStatus = getNewStatus($statisArray);

	$newLog = '';
	if ($newStatus == '4') {
		$str = "投递结束, 一共有" . $statisArray['total'] . "用户, 投递成功.\n";
		$newLog = addlog($str);
	} else if ($newStatus == '7' || $newStatus == '8') {
		$str = "投递结束, 一共有" . $statisArray['total'] . "用户,其中成功用户：" . $statisArray['succeed'] .
				 ", 失败用户:" . $statisArray['failed'] . "\n";
		$newLog = addlog($str);
	}

	// update mr_task_log status
	$update_tasklog_sql = "update mr_task_log set status='{$newStatus}', success='{$statisArray['succeed']}', " 
						. "failure='{$statisArray['failed']}'";
	if ($newLog != '') {
		$update_tasklog_sql .= ", log='" . $log . $newLog . "'";
	}
	$update_tasklog_sql .= " where id=" . $tasklogid;
	try {
		$dbAdapter->query($update_tasklog_sql);
	} catch (Exception $e) {
		echo addlog("update error, tasklog_sql:" . $update_tasklog_sql. PHP_EOL);
	}

	// update mr_task status
	if ($newLog != '') {
		$update_task_sql = "update mr_task set status='{$newStatus}' where id=" . $taskid;
		try {
			$dbAdapter->query($update_task_sql);
		} catch (Exception $e) {
			echo addlog("update error, task_sql:" . $update_task_sql. PHP_EOL);
		}
	}

	echo addlog("done tasklogid:" . $tasklogid . PHP_EOL);
}

// 通过统计数据，生产新的status
function getNewStatus($statisArray) {
	if ($statisArray['succeed'] == $statisArray['total']) {
		// TASK_DONE
		return 4;
	} elseif ($statisArray['waited'] == 0) {
		if ($statisArray['failed'] == $statisArray['total']) {
			// TASK_FAILURE
			return 7;
		} elseif ($statisArray['failed'] > 0) {
			// TASK_PARTIAL_FAILURE
			return 8;
		}
	}

	// TASK_INPROGRESS
	return 3;
}

function addlog($log) {
	return date('Y-m-d H:i:s', time()) . ' -- ' . $log;
}


?>