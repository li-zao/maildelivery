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
Zend_Registry::set ( 'log_dir', $basic->task->generate->path );

exec("ps -ef|grep generatingTasks.php|grep -v grep", $return_array, $status);
$running_count = count($return_array);
if ($running_count > 1) {
	exit('running'.PHP_EOL);
}

$create_tasklog_sql = 'insert ignore into mr_task_log (id, uid, tid, runtime, status, total, success, failure, deal, isreport, hassendreport, reportemail, log) VALUES(';

$task_model = new Task();
$group_model = new Group();

$now = date('Y-m-d H:i:s', time());
$datestr = date('YzH', time());
$datestr = (int)substr($datestr, 3);

$sendmail = new Sendmail ();
$mailauth = $sendmail->getAllInfos ();
if ($mailauth == null || $mailauth == "") {
	return "No Configuration";
}
$account = $mailauth[0];

// 获取所有的组id和组名
$table_infos = $group_model->getAllGidTname();
$table_infos = fetchTablename($table_infos);
Zend_Registry::set ( 'table_infos', $table_infos );

// 获取需要处理的队列，sql搬运自java
$task_result = $task_model->getTaskByTime($now);
if (empty($task_result)) {
	exit( addlog('task is empty ,do nothing') . PHP_EOL );
}

// 获取自定义字段并整理格式
$dbAdapter = Zend_Registry::get('dbAdapter');
$dbresult = $dbAdapter->fetchAll("select `name` from mr_group_extension");
$custom_variables_array = array();
foreach ($dbresult as $value) {
	$custom_variables_array[] = $value['name'];
}
Zend_Registry::set('custom_variables_array', $custom_variables_array);

// 获取服务端ip和端口
$service_console = array();
$dbresult = $dbAdapter->fetchAll("select `domainname` as `ip` ,`serviceport` as `port` from mr_console limit 1");
if (!empty($dbresult)) {
	$service_console = $dbresult[0];
}
Zend_Registry::set('service_console', $service_console);

foreach ($task_result as $info) {
	$value 		= "";
	$task_id	= $info['id'];
	$uid		= $info['uid'];
	$tlid		= $task_id * 1000000 + $datestr; 
	if ($tlid < 0) {
		continue;
	}

	$isreport	= $info['isreport'];
	$reportemail= $info['reportemail'];
	if ($reportemail == null) {
		$reportemail = "";
	}

	echo addlog("start working on " . $tlid) . PHP_EOL;

	// create task run log
	$value = $tlid . "," . $uid . "," . $task_id . ",'" . $now . "',0,0,0,0,0," . $isreport . ",0,'" . $reportemail . "','')";
	$dbAdapter->query($create_tasklog_sql . $value);

	// update task status
	$task_model->updateTask(array('status'=>3), $task_id);

	// ===================== 获取接收者 start========================  
	// 获取发送的组成员
	$endusers = array();
	$groups_str = $info['groups'];
	$groups = explode(',', $groups_str);
	for ($i=0; $i < count($groups); $i++) { 
		$gid = $groups[$i];
		$gusers = array();

		if ($gid == 'all') {
			//mr_subscriber
			$dbresult = $dbAdapter->fetchAll("select mailbox from mr_subscriber");
			foreach ($dbresult as $value) {
				$gusers[] = $value['mailbox'];
			}
		} else if ($gid >=0 ) {
			$gusers = getGroupUsers($gid, $info['fid']);
		}

		if ($gusers != null) {
			$endusers = array_merge($endusers, $gusers);
		}

	}
	// 获取手动输入的邮箱
	$receivers_str = $info['receivers'];
	if ($receivers_str != null) {
		$receivers_pieces = explode(',', $receivers_str);
		for ($i=0; $i < count($receivers_pieces); $i++) { 
			$mailbox = $receivers_pieces[$i];

			if (strpos($mailbox, '@') !== false) {
				$endusers[] = $mailbox;
			} else {
				$log = addlog("Task[" .$task_id."] - invalid address:" . $mailbox);
				writeFile($log);
			}
		}
	}
	// 去重并重建键
	$endusers = array_values(array_unique($endusers));
	// ===================== 获取接收者 end======================== 

	// 判断信是否已经插入过smtptask
	$oforwards = getCreatedSmtpTask($tlid); 

	$total = 0;
	$index = 0;
	// 循环发信
	foreach ($endusers as $mailbox) {
		if ($mailbox != null) {
			$total ++;
			$already_add = false;

			if ($oforwards != null) {
				foreach ($oforwards as $oforward) {
					if ($oforward != null && strpos($oforward, $mailbox) !== false && strlen($oforward) == strlen($mailbox)+2) {
						$already_add = true;
						break;
					}
				}

			}
			if ($already_add) {
				$index ++;
			} else {
				$uniq_id = md5(uniqid(time()));
				$msgid = sprintf('<%s@%s>', $uniq_id, $_SERVER['HOSTNAME']);
				// 生成参数并发信
				$info['sendto'] = $mailbox;
				$info['tlid'] = $tlid;
				$info['msgid'] = $msgid;
				$param = generateParamter($info);
				$account['authuser'] = $param['fromname'];

				SystemConsole::selfMadeMail($account, $info['sender'], 
					$param['subject'], $param['mailbody'], trim($mailbox), $param['attachment'], 
					"admin", $param['reply'], $param['taskid'], $param['tlid'], $msgid);

				$index ++;
			}
		}
	}

	if ($total == $index) {
		$UPDATE_TASKLOG_SQL_P1 = "update mr_task_log set status=3, total=";
		$UPDATE_TASKLOG_SQL_P2 = ",runtime='";
		$UPDATE_TASKLOG_SQL_P3 = "',log='";
		$UPDATE_TASKLOG_SQL_P4 = "' where id=";
		$nowdate = date('Y-m-d H:i:s', time());
		$plog = addlog("任务开始准备投递，共有" . $total . "接收者。\n");

		$sql = $UPDATE_TASKLOG_SQL_P1 . $total . $UPDATE_TASKLOG_SQL_P2 . $nowdate . $UPDATE_TASKLOG_SQL_P3 . $plog .
				$UPDATE_TASKLOG_SQL_P4 . $tlid;
		try {
			$dbAdapter->query($sql);
		} catch (Exception $e) {
			$log = addlog("error UPDATE_TASKLOG_SQL:".$sql);
			writeFile($log);		
		}
	} else {
		$log = addlog("generate smtp tasks mismatch:" . $total .';'.$index.';'.$task_id.';'.$tlid);
		writeFile($log);		
	}
	// 本次循环结束打log
	$log = addlog("done tlid: " . $tlid);
	writeFile($log);
	echo $log . PHP_EOL;
}


// 整理group表的结构为 array('组id'=>'表名')的格式
function fetchTablename ($table_array) {
	$return = array();
	foreach ($table_array as $value) {
		$return[$value['id']] = $value['tablename'];
	}

	return $return;
}

// 通过组id和fid 获取组成员
function getGroupUsers($groupid, $fid) {
	$users = array();
	$filter_str = '';

	// 去表名 
	$group_tablename = getGroupTablename($groupid);
	if ($group_tablename == null) {
		return null;
	}

	// 取筛选条件
	if ($fid >= 0) {
		$filter_str = getFilterStr($fid);
	}

	// 取用户
	try {
		$dbAdapter = Zend_Registry::get('dbAdapter');
		$RETRIEVE_USER_BYGROUPID_SQL = "select distinct mailbox from ";
		$sql = $RETRIEVE_USER_BYGROUPID_SQL . $group_tablename;

		if ($filter_str != null && strlen($filter_str) > 0) {
			$sql .= ' where ' . $filter_str;
		}

		$dbresult = $dbAdapter->fetchAll($sql);
		if (empty($dbresult)) {
			return null;
		}

		// 计数并整理用户
		$index = 0;
		for ($i=0; $i < count($dbresult); $i++) { 
			$mailbox = $dbresult[$i]['mailbox'];
			if ($mailbox != null) {
				if (strpos($mailbox, '@') !== false) {
					$users[] = $mailbox;
					$index ++;
				} else {
					$log = addlog("Group[" . $groupid . ";" . $group_tablename . "] - invalid address:" . $mailbox);
					writeFile($log);
				}
			}
		}
	} catch (Exception $e) {
		$users = null;
	}

	return $users;
}

// 通过组id获取表名
function getGroupTablename($groupid) {
	$table_infos = Zend_Registry::get ('table_infos');;

	if (isset($table_infos[$groupid])) {
		return $table_infos[$groupid];
	} else {
		return null;
	}
}

function getFilterStr($fid) {
	$dbAdapter = Zend_Registry::get ('dbAdapter');;

	$RETRIEVE_FILTER_BYID_SQL = "select id, `condition` from mr_filter where id=";
	$result = null;
	if ($fid < 0) {
		return $result;
	}

	try {
		$sql  = $RETRIEVE_FILTER_BYID_SQL . $fid;
		$dbresult = $dbAdapter->fetchAll($sql);
		if (empty($dbresult)) {
			return $result;
		}

		$result = $dbresult[0]['condition'];
	} catch (Exception $e) {
		$log = addlog("getFilterStr fail, RETRIEVE_FILTER_BYID_SQL:" . $sql);
		writeFile($log);
	}

	return $result;
}

function getCreatedSmtpTask($tlid){
	$dbresult = null;
	$sql_list = null;
	$QUERY_SMTP_TASK_SQL = "select id, forward from mr_smtp_task where tlid=";
	$dbAdapter = Zend_Registry::get('dbAdapter');

	try {
		$sql = $QUERY_SMTP_TASK_SQL . $tlid;
		$dbresult = $dbAdapter->fetchAll($sql);
		$forward = null;
		$id = 0;
		$index = 0;

		if (empty($dbresult)) {
			return null;
		}

		for ($i=0; $i < count($dbresult); $i++) { 
			$id = $dbresult[$i]['id'];
			$forward = $dbresult[$i]['forward'];

			if ($forward != null) {
				if (strpos($forward, '@') !== false) {
					$sql_list[] = $forward;
					$index ++;
				} else {
					$log = addlog("SmtpTask[" . $id . "] - invalid address:" . $forward);
					writeFile($log);
				}
			}
		}
	} catch (Exception $e) {
		$log = addlog("getCreatedSmtpTask -- Exception" . $e);
		writeFile($log);
	}
	return $sql_list;
}

// 生成信的数据
function generateParamter($info) {
	$taskid = $info['id'];
	// 发信人
	$from = $info['sendemail'];

	// 标题 
	$subject = parseVariables($info['sendto'], $info['subject']);
	$domainAD = $info['domainAD'];
	if ($domainAD != null) {
		$domainADs = explode(',', $domainAD);
		for ($j=0; $j < count($domainADs); $j++) { 
			$pdomainAD = '@' . trim($domainADs[$j]);

			if (strlen($pdomainAD) > 0 && strpos($info['sendto'], $pdomainAD) !== false) {
				$subject = '(AD)' . $subject;
			}
		}
	}

	// 正文
	$imgUrl = generateIMGURL($taskid, $info['msgid']);	// 更改邮件状态脚本
	$displayurl = generateDISPMAILURL($taskid, $info['msgid']);		// 显示邮件url
	$unsubscribeurl = generateUNSUBSCRIBEURL($taskid, $info['sendto']);		// 取消订阅url 
	$html_header = "<P style=\"WHITE-SPACE: normal\" id=mdds><TABLE style=\"BORDER-BOTTOM: #cccccc 1px solid; "
			. "BORDER-LEFT: #cccccc 1px solid; BORDER-TOP: #cccccc 1px solid; BORDER-RIGHT: #cccccc 1px solid\" "
			. "border=0 cellSpacing=0 cellPadding=0 width=750 align=center><TBODY><TR><TD style=\"PADDING-LEFT: 10px;line-height: 1.8em;text-align:center;font-size:13px;font-family: '微软雅黑';\""
			. "height=30\"><img src=\"{$imgUrl}\" border=\"0\" style=\"display:none\" >如无法正常打开此邮件，请<A style=\"color: #f00;text-decoration: none;\" href=\"{$displayurl}\""
			. " target=_blank>点击此处</A>。取消订阅，请<A style=\"color: #f00;text-decoration: none;\" href=\"{$unsubscribeurl}\" target=_blank>点击此处</A>。拒绝所有邮件，请<A style=\""
			. "color: #f00;text-decoration: none;\" href=\"{$unsubscribeurl}\" target=_blank>点击此处</A>。<br>为了您能正常接收邮件，请将"
			. " <a href=\"mailto:{$info['sendemail']}\" target=\"_blank\" style=\"color: #f00; text-decoration: none;\">{$info['sender']}</a> 添加到白名单或通讯录。</TD></TR></TBODY></TABLE></P>";
	$con = parseVariables($info['sendto'], $info['data']);	
	$content = $html_header . $con;

	// 附件
	$attachment = getAttachmentByTid($taskid);
	
	// 回复地址
	$reply = $info ['replyemail'];

	$param['fromname'] = $from;
	$param['subject'] = $subject;
	$param['mailbody'] = $content;
	$param['receiver'] = $info['sendto'];
	$param['attachment'] = $attachment;
	$param['taskid'] = $taskid;
	$param['reply'] = $reply;
	$param['tlid'] = $info['tlid'];

	return $param;
}

function generateIMGURL ($taskid, $msgid) {
	$config = Zend_Registry::get('service_console');
	$url = 'http://' . $config['ip'] .':' . $config['port'] . '/showmail.php?mail=';
	$orgin_str = "3#" . $taskid . "#" . $msgid . "#153";

	$des = new Des("lg!@2015");
	$orgin_str = $des->encrypt($orgin_str);

	$encrypt_str = $url . urlencode($orgin_str);
	return $encrypt_str;
}

function generateDISPMAILURL($taskid, $msgid) {
	$config = Zend_Registry::get('service_console');
	$url = 'http://' . $config['ip'] .':' . $config['port'] . '/img.php?mk=';
	$orgin_str = "9#" . $taskid . "#".$msgid."#785";

	$des = new Des("lg!@2015");
	$orgin_str = $des->encrypt($orgin_str);

	$encrypt_str = $url . urlencode($orgin_str);
	return $encrypt_str;
}

function generateUNSUBSCRIBEURL($taskid, $sendto) {
	$config = Zend_Registry::get('service_console');
	$url = 'http://' . $config['ip'] .':' . $config['port'] . '/showunsubscription.php?mail=';
	$orgin_str = "7#" . $taskid . "#" . $sendto . "#916";

	$des = new Des("lg!@2015");
	$orgin_str = $des->encrypt($orgin_str);

	$encrypt_str = $url . urlencode($orgin_str);
	return $encrypt_str;
}

// 解析页面插入的变量
function parseVariables($mailbox, $str) {
	$custom_variables_array = Zend_Registry::get('custom_variables_array');
	$dbAdapter = Zend_Registry::get('dbAdapter');

	//查找用户详细信息 
	$dbresult = $dbAdapter->fetchAll("select * from mr_subscriber where mailbox = '{$mailbox}'");
	if (empty($dbresult)) {
		return $str;
	}

	// 整理preg_replace第一个参数的数组
	$pattern_array = array();
	foreach ($custom_variables_array as $value) {
		$value = '/\[\$'. $value .'\]/';
		$pattern_array[] = $value;
	}

	// 整理preg_replace第二个参数的数组
	$replacement = array();
	foreach ($custom_variables_array as $type) {
		$value = $dbresult[0][$type];
		if ($type == 'sex') {
			if ($value == '1') {
				$value = '先生';
			} else if ($value == '2') {
				$value = '女士';
			} else {
				$value = '客户';
			}
		}
		if ($type == 'birth') {
			$value = date('Y-m-d', $value);
		}
		$replacement[] = $value;
	}
	// 参考preg_replace(['/\[\$username\]/','/\[\$sex\]/','/\[\$tel\]/'], ["小明","男","18632430161"], '这里是邮件主题[$username][$sex] -- 手机号:[$tel]');
	$str = preg_replace($pattern_array, $replacement, $str);

	return $str;
}
// 通过taskid获取附件
function getAttachmentByTid($taskid) {
	$result = null;

	try {
		$dbAdapter = Zend_Registry::get('dbAdapter');
		$sql = "select path,truename from mr_attachment where tid = " . $taskid;
		$dbresult = $dbAdapter->fetchAll($sql);
		if (empty($dbresult)) {
			return null;
		}

		for ($i=0; $i < count($dbresult); $i++) { 
			if (is_file($dbresult[$i]['path'])) {
				$result[] = $dbresult[$i];
			} else {
				$log = addlog("attachment[" .$path."] - get attachment fail");
				writeFile($log);
			}
		}
	} catch (Exception $e) {
		$log = addlog("getAttachmentByTid -- Exception" . $e);
		writeFile($log);
	}

	return $result;
}

function addlog($log) {
	return date('Y-m-d H:i:s', time()) . ' -- ' . $log;
}

function writeFile ($log) {
	$now = time();
	$log_root_dir = Zend_Registry::get('log_dir');
	if (!is_dir($log_root_dir)) {
		mkdir($log_root_dir);
	}
	$filename = date('Y_m_d',time()) . '.log';

	$path = $log_root_dir . '/' . $filename;
	chmod($log_root_dir, 0777);
	chmod($path, 0777);

	file_put_contents($path, $log . PHP_EOL, FILE_APPEND);
}
?>
