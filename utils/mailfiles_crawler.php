<?php
date_default_timezone_set ( 'Asia/Shanghai' );
set_include_path ( '/var/www/maildelivery' . PATH_SEPARATOR . '/var/www/maildelivery/library' . PATH_SEPARATOR . '/var/www/maildelivery/app/models/' . PATH_SEPARATOR . get_include_path () );
//Set Zend Framework  load class automatically
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance ()->setFallbackAutoloader ( true );
ini_set('memory_limit', '1024M');
//pcntl_signal(SIGSEGV, SIG_IGN, false);
//start session
Zend_Session::start ();
//Init registry
$registry = Zend_Registry::getInstance ();

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

if( empty($argv[1])){
	echo "\r\nThe path is invalid, please input the correct path\r\n";
}

$force = true;

$filepath = $argv[1];

if (is_dir ( $filepath ) && file_exists($filepath)) {
	$storage_db = new Storage();
	$storagespath = $storage_db->getCurrentTempMailPath();
	system("sudo mkdir -p ".$storagespath);
	$mail_db = new Mail();
	$count = CommonUtil::parseEmlFiles($filepath, true, $mail_db);
	echo "\r\nFind ".$count." mail files, imported ".$count." mail files".".\r\n";
} else {
	echo "\r\nThe path is invalid, please input the correct path\r\n";
}

echo "\r\nSucceed to craw the path\r\n";
?>