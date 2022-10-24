<?php
date_default_timezone_set ( 'Asia/Shanghai' );
// error_reporting(0);
set_include_path ( '.' . PATH_SEPARATOR . './library' . PATH_SEPARATOR . './app/models/' . PATH_SEPARATOR . get_include_path ());
//Set Zend Framework  load class automatically
require_once 'Zend/Loader/Autoloader.php';
if (!defined('PHPEXCEL_ROOT')) {
    define('PHPEXCEL_ROOT', "./library/excel/");
    require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
}
Zend_Loader_Autoloader::getInstance ()->setFallbackAutoloader ( true );
Zend_Session::start ();
//Init registry
$registry = Zend_Registry::getInstance ();

require_once 'dompdf/dompdf_config.inc.php';  
$autoloader = Zend_Loader_Autoloader::getInstance(); // assuming we're in a controller  
$autoloader->pushAutoloader('DOMPDF_autoload');

//Configure db
$basic = new Zend_Config_Ini ( './app/config/basic.ini', null, true );
Zend_Registry::set ( 'basic', $basic );
$dbAdapter = Zend_Db::factory ( $basic->general->db->adapter, $basic->general->db->toArray () );
$dbAdapter->query ( "SET NAMES {$basic->general->db->charset}" );
Zend_Db_Table::setDefaultAdapter ( $dbAdapter );
Zend_Registry::set ( 'dbAdapter', $dbAdapter );
Zend_Registry::set ( 'db_username', $basic->general->db->username );
Zend_Registry::set ( 'db_password', $basic->general->db->password );
Zend_Registry::set ( 'db_dbname', $basic->general->db->dbname );
Zend_Registry::set ( 'dbprefix', $basic->general->db->prefix );

$cur_lang = $basic->site->st->default_lang;
Zend_Registry::set ( 'default_lang', $cur_lang );

//Set template path
$view = new Zend_View ( );
//Register template
$view->setScriptPath ( './app/views/' );
$view->setHelperPath ( './app/views/helpers/', 'My_View_Helper' );
//Register View
$registry ['view'] = $view;
//Set basic infomations
Zend_Registry::set ( 'sitename', $basic->site->st->sitename );
Zend_Registry::set ( 'keywords', $basic->site->st->keywords );
Zend_Registry::set ( 'description', $basic->site->st->description );
Zend_Registry::set ( 'apache_ssl_crt_path', $basic->admin->apache->ssl_crt_path );
Zend_Registry::set ( 'apache_ssl_key_path', $basic->admin->apache->ssl_key_path );
Zend_Registry::set ( 'apache_config_path', $basic->admin->apache->config_path );
Zend_Registry::set ( 'apache_web_config_path', $basic->admin->apache->web_config_path );
Zend_Registry::set ( 'apache_command', $basic->admin->apache->command );
Zend_Registry::set ( 'console_eth', $basic->admin->console->eth );
Zend_Registry::set ( 'vip_path', $basic->admin->vip->path );
Zend_Registry::set ( 'domainAD', $basic->admin->domain->ad );
Zend_Registry::set ( 'backend_ip', $basic->backend->server->ip );
Zend_Registry::set ( 'backend_port', $basic->backend->server->port );
Zend_Registry::set ( 'domail', $basic->backend->domail->max );
Zend_Registry::set ( 'domainurl', $basic->backend->domail->path );
Zend_Registry::set ( 'engine_port', $basic->backend->engine->port );
Zend_Registry::set ( 'guardian_port', $basic->backend->guardian->port );
Zend_Registry::set ( 'spfserver_port', $basic->backend->spfserver->port );
Zend_Registry::set ( 'task_port', $basic->backend->task->port );
//Create authencate object
$auth = Zend_Auth::getInstance ();
//Create ACL object
$acl = new Common_Plugin_MyAcl ();
//new Smarty
include_once "library/Smarty/Smarty.class.php";
$Smarty = new Smarty ();
$Smarty->left_delimiter = "{%";
$Smarty->right_delimiter = "%}";
$Smarty->template_dir = './app/views';
$Smarty->compile_dir = './app/views/templates_c';
$Smarty->cache_dir = './app/views/cache';
// session time
$console_db = new Console();
$admintimeout = $console_db->getAllInfos();
$timelimit = 30;
if (!empty($admintimeout[0]['dsessiontime']) && is_numeric($admintimeout[0]['dsessiontime'])) {
	$timelimit = $admintimeout[0]['dsessiontime'];
}

$authSession = new Zend_Session_Namespace('Zend_Auth');
$authSession->setExpirationSeconds(60 * $timelimit);

Zend_Registry::set('Smarty', $Smarty);
//Set controller
$frontController = Zend_Controller_Front::getInstance ();
$frontController->setBaseUrl ( $basic->site->st->appdir )->setParam ( 'noViewRenderer', true )->addModuleDirectory ( './app/modules' )->setParam ( 'useDefaultControllerAlways', true )->setParam ( 'noErrorHandler', true )->registerPlugin ( new Common_Plugin_MyAuth ( $auth, $acl ) )->registerPlugin ( new Zend_Controller_Plugin_ErrorHandler ( ) )->throwExceptions ( true )->dispatch ();
?>
