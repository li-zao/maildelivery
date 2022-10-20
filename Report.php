<?php
date_default_timezone_set ( 'Asia/Shanghai' );
set_include_path ( '/var/www/maildelivery' . PATH_SEPARATOR . '/var/www/maildelivery/library' . PATH_SEPARATOR . '/var/www/maildelivery/app/models/' . PATH_SEPARATOR . get_include_path ());
// set_include_path ( '.' . PATH_SEPARATOR . './library' . PATH_SEPARATOR . './app/models/' . PATH_SEPARATOR . get_include_path ());
//Set Zend Framework  load class automatically
require_once 'Zend/Loader/Autoloader.php';
require_once 'dompdf/dompdf_config.inc.php';  
Zend_Loader_Autoloader::getInstance ()->setFallbackAutoloader ( true );
Zend_Session::start ();

$registry = Zend_Registry::getInstance ();

$basic = new Zend_Config_Ini ( '/var/www/maildelivery/app/config/basic.ini', null, true );
// $basic = new Zend_Config_Ini ( './app/config/basic.ini', null, true );
Zend_Registry::set ( 'basic', $basic );
$dbAdapter = Zend_Db::factory ( $basic->general->db->adapter, $basic->general->db->toArray () );
$dbAdapter->query ( "SET NAMES {$basic->general->db->charset}" );
Zend_Db_Table::setDefaultAdapter ( $dbAdapter );
Zend_Registry::set ( 'dbAdapter', $dbAdapter );
Zend_Registry::set ( 'db_username', $basic->general->db->username );
Zend_Registry::set ( 'db_password', $basic->general->db->password );
Zend_Registry::set ( 'db_dbname', $basic->general->db->dbname );
Zend_Registry::set ( 'dbprefix', $basic->general->db->prefix );

$tmp = date('Y-m-d H:s',time());
$sendmail =  new Sendmail();

$account = $sendmail->getAllInfos();
if ($account[0]['smtpserverport'] != 25) {
			$config = array('name' => $account[0]['smtpserver'], 'ssl' => 'ssl', 'port' => $account[0]['smtpserverport'], 'auth' => 'login',
			'username' => $account[0]['authuser'],
			'password' => $account[0]['authpwd']);
		} else {
			$config = array('name' => $account[0]['smtpserver'], 'port' => $account[0]['smtpserverport'], 'auth' => 'login',
			'username' => $account[0]['authuser'],
			'password' => $account[0]['authpwd']);
		}
$transport = new Zend_Mail_Transport_Smtp($account[0]['smtpserver'],$config);	

//select Port
$Port_Sql = "SELECT dport,domainname FROM mr_console";
$Resoult_Port = $dbAdapter->fetchAll($Port_Sql);

//select total
$Mail_Sql = "SELECT * FROM mr_task_log WHERE isreport = 1 AND hassendreport= 0 AND status in(8,7,4,5,6) AND reportemail IS NOT NULL ORDER BY runtime DESC ";
$mail_id = $dbAdapter->fetchAll($Mail_Sql);

$nums = count($mail_id);
if($mail_id){
    //select Detailed email
    for ($i=0; $i<$nums; $i++) {
        $getOperatorSql = "select username from mr_accounts where id=".$mail_id[$i]['uid'];
        $operator = $dbAdapter->fetchAll($getOperatorSql);
        if (empty($operator)) {
            $operator = "admin";
        }
        $Task_Sql = "SELECT id AS did,status AS taskstatus,task_name,createtime,modifiedtime,sendtime,sender,subject FROM mr_task WHERE uid={$mail_id[$i]['uid']} AND id={$mail_id[$i]['tid']}  ORDER BY id DESC  LIMIT  0,1";
        $Task_id = $dbAdapter->fetchAll($Task_Sql);

        $Every_Sql = "SELECT id,forward,status FROM mr_smtp_task WHERE taskid = {$mail_id[$i]['tid']} AND tlid={$mail_id[$i]['id']} ORDER BY id";
        $Every_Resoult=$dbAdapter->fetchAll($Every_Sql);

        $Total_Email = array();
        $Final_Resoult = array();
        foreach($Every_Resoult as $every){
            if($every['status'] == 2){
                $Total_Email['successemails'][]=$every['forward'];
                $Total_Email['failureemails'][]="";
            }elseif($every['status'] == 3 || $every['status'] == 5 || $every['status'] == 6){
                $Total_Email['successemails'][]="";
                $Total_Email['failureemails'][]=$every['forward'];
            }
        }
        foreach($Total_Email as &$vals){
            $vals = join(';',$vals);
        }
        $Final_Resoult = array_merge_recursive($Task_id[0],$mail_id[$i],$Total_Email);
        if($Final_Resoult['status'] ==4){
            $Final_Resoult['status'] = '发送完成';
        }elseif($Final_Resoult['status'] ==5){
            $Final_Resoult['status'] = '停止';
        }elseif($Final_Resoult['status'] ==6){
            $Final_Resoult['status'] = '审核失败';
        }elseif($Final_Resoult['status'] ==7){
            $Final_Resoult['status'] = '发送失败';
        }elseif($Final_Resoult['status'] ==8){
            $Final_Resoult['status'] = '部分失败';
        }
                
        if($Final_Resoult){
            $title = $Final_Resoult['task_name']."任务统计报告";
            $html = "<!DOCTYPE html><html lang='en'><head><title>统计报告</title><meta http-equiv='content-type' content='text/html;charset=utf-8' />";
            $html .= "<style>*{font-family:simhei;font-size:10px;}table.list {border: 1px solid #9BCCC6;   border-collapse: collapse;   margin-top: 10px;   width: 100%;    table-layout: fixed;}table.list thead, table.list tfoot {    background: #B3E7EE;}table.list td {    border: 1px solid #31C4D6;    color: #2582BE;    padding: 2px;    word-break: break-all;    text-overflow: ellipsis;    overflow: hidden;    white-space: nowrap;}table.list td.datacolumn {	color: #000000;}table.list th {    border: 1px solid #31C4D6;    color: #2582BE;    padding: 2px;    text-align: left;}table.list th.datacolumn {    color: #000000;    background: #FFFFFF;}table.list th.sortable {    cursor: pointer;}table.list th.sortasc, table.list th.sortdesc {    background-color: #B3E7EE;    background-position: right;    background-repeat: no-repeat;    cursor: pointer;}table.list th.expand {    background-color: #B3E7EE;}table.list th.expandable {    cursor: pointer;}table.list tr.even {    background: #D6F1F5;    color: #052101;}table.list tr.odd {    background: #FFFFFF;    color: #052101;}td{width: 500px;}</style></head><body>";
            $html.="<table style='width: 200px;height: 100px;border:0;padding: 1px;margin-left: 70px;'><caption>".$title."</caption><tr style='height: 40px;'><td style='width: 100px;'>任务名称:</td><td><p>".$Final_Resoult['task_name']."</p></td></tr><tr style='height: 40px;'><td style='width: 100px;'>创建时间:</td><td><p>".$Final_Resoult['createtime']."</p></td></tr><tr style='height: 40px;'><td style='width: 100px;'>发送时间:</td><td><p>".$Final_Resoult['sendtime']."</p></td></tr><tr style='height: 40px;'><td style='width: 100px;'>投递状态:</td><td><p>".$Final_Resoult['status']."</p></td></tr><tr style='height: 40px;'><td style='width: 100px;'>主题:</td><td><p>".$Final_Resoult['subject']."</p></td></tr><tr style='height: 40px;'><td style='width: 100px;'>发送者:</td><td><p>".$Final_Resoult['sender']."</p></td></tr><tr style='height: 40px;'><td style='width: 100px;'>接收者邮箱地址:</td><tdstyle='width:350px;word-break:break-all'><p>".$Final_Resoult['successemails'].";".$Final_Resoult['failureemails']."</p></td></tr><tr style='height: 40px;'><td style='width: 100px;'>发送总量:</td><td><p>".$Final_Resoult['total']."</p></td></tr><tr style='height: 40px;'><td style='width: 100px;'>成功量:</td><td><p>".$Final_Resoult['success']."</p></td></tr><tr style='height: 40px;'><td style='width: 100px;'>失败量:</td><td><p>".$Final_Resoult['failure']."</p></td></tr><tr style='height: 40px;'><td style='width: 100px;'>日志:</td><td><p>".$Final_Resoult['log']."</p></td></tr><tr style='height: 40px;'><td style='width: 100px;'>查看详情:</td><td><a href='http://".$Resoult_Port[0]["domainname"].":".$Resoult_Port[0]["dport"]."'>点击查看</a></td></tr></table><br>";
        }else{
                    echo "\r\nSend Error,Loss of data\r\n";
        }
        
		$report_pdf = "/home/maildata_reports/".$Final_Resoult['task_name'].".pdf";

		$mailbody_html = "<div><span style=\"font-size:14px;\">MailData电子邮件投递系统".$tmp.".[本次共投递".$Final_Resoult['total']."封邮件]。<br>详细投递数据请查看相应附件[".$Final_Resoult['task_name'].".pdf]。</span></div>";
		$html.="</body></html>";

		// echo $html;
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->render();
		$pdf = $dompdf->output();
		file_put_contents($report_pdf, $pdf);	
        $taskname = "MailData电子邮件投递系统-".$Final_Resoult['task_name']."统计报告";
		SystemConsole::selfMadeMail($account, "MailData邮件分发投递系统", $taskname, $mailbody_html, $Final_Resoult['reportemail'], $report_pdf, $operator);
		// @exec("rm -rf /home/maildata_reports_images/".$i.".png");
		// @exec("rm -rf /home/maildata_reports/*.pdf");
        $final_success_resoult = $dbAdapter->query("UPDATE mr_task_log SET hassendreport=1 WHERE id={$mail_id[$i]['id']}");
        if($final_success_resoult){
            echo "Succeed to send  report\r\n";
        }
	}
} else {
	echo "\r\nLoss of data\r\n";
}
