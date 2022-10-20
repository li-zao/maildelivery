<?php
require ('CommonController.php');
require ('page.class.php');
class SettingController extends CommonController {
	
	public $account;
	public $console;
	public $ntp;
	public $securityparam;
	public $singledomain;
	public $trustiptable;
	public $staticmx;
	public $domain;
	public $userintercept;
	public $specialdomain;
	public $sendmail;
	public $alertsetting;
	public $workingreport;
	public $vip;
	public $publishedinfo;
	public $smtptask;
	public $accesslog;
	
	function init() {
		parent::init ();
		$this->account = new Account ();
		$this->console = new Console ();
		$this->ntp = new Ntp ();
		$this->securityparam = new SecurityParam ();
		$this->singledomain = new SingleDomain ();
		$this->trustiptable = new TrustipTable();
		$this->staticmx = new Staticmx ();
		$this->domain = new Domain();
		$this->userintercept = new UserIntercept ();
		$this->specialdomain = new Specialdomain ();
		$this->sendmail = new Sendmail ();
		$this->alertsetting = new Alertsetting ();
		$this->workingreport = new WorkingReport();
		$this->vip = new Vip ();
		$this->publishedinfo = new Publishedinfo ();
		$this->smtptask = new SmtpTask ();
		$this->accesslog = new AccessLog();
	}
	
	/**
	 *	networksetting
	 */
	
	public function networksettingAction () {
		$eth0addr = SystemConsole::GetNetworkAddr ("eth0");
		$eth0gw = SystemConsole::GetRoute("eth0");
		$eth1addr = SystemConsole::GetNetworkAddr ("eth1");
		$eth1gw = SystemConsole::GetRoute("eth1");
		$dns = SystemConsole::GetDNS();
		
		$eth1vip = $this->vip->getInfosByEth ("eth1");
		$this->Smarty->assign ("uservipview", $eth1vip['usevip']);	
		$match = strpos($eth1vip['vip'], ";");
		if ($match !== false) {
			$eth1vip = str_replace ($eth1addr['ip'], "", $eth1vip['vip']);
			$eth1vip = str_replace (";", "\r\n", $eth1vip);
			$this->Smarty->assign ("eth1vip", $eth1vip);	
		}
		
		$eth0vip = $this->vip->getInfosByEth ("eth0");
		$match = strpos($eth0vip['vip'], ";");
		if ($match !== false) {
			$eth0vip = str_replace ($eth0addr['ip'], "", $eth0vip['vip']);
			$eth0vip = str_replace (";", "\r\n", $eth0vip);
			$this->Smarty->assign ("eth0vip", $eth0vip);	
		}
		
		$this->Smarty->assign ("eth0addr", $eth0addr);	
		$this->Smarty->assign ("eth0gw", $eth0gw);	
		$this->Smarty->assign ("eth1addr", $eth1addr);	
		$this->Smarty->assign ("eth1gw", $eth1gw);	
		$this->Smarty->assign ("dns", $dns);	
		
		$this->Smarty->assign ("li_menu", "networksetting");	
		$this->Smarty->display ( 'networksetting.php' );
	}
	
	public function updatenetworksettingAction() {
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ( );
			$data = array (
				'eth' => $filter->filter ( $this->_request->getPost ( 'witchcard' ) ), 
				'eth0ip' => $filter->filter ( $this->_request->getPost ( 'eth0ip' ) ),
				'eth0mask' => $filter->filter ( $this->_request->getPost ( 'eth0mask' ) ),
				'eth0gw' => $filter->filter ( $this->_request->getPost ( 'eth0gw' ) ),
				'firstdns' => $filter->filter ( $this->_request->getPost ( 'firstdns' ) ),
				'seconddns' => $filter->filter ( $this->_request->getPost ( 'seconddns' ) ),
				'eth1ip' => $filter->filter ( $this->_request->getPost ( 'eth1ip' ) ),
				'eth1mask' => $filter->filter ( $this->_request->getPost ( 'eth1mask' ) ),
				'eth1gw' => $filter->filter ( $this->_request->getPost ( 'eth1gw' ) ),
				'usevip' => $filter->filter ( $this->_request->getPost ( 'usevip' ) )
			); 
			$eth0vip = $filter->filter ( $this->_request->getPost ( 'eth0vip' ) );
			$eth1vip = $filter->filter ( $this->_request->getPost ( 'eth1vip' ) );
			$dsnlist = array();
			$dsnlist[] = $data['firstdns'];
			$dsnlist[] = $data['seconddns'];
			if ($data['eth'] == 'eth0') {
				$eth0addr = SystemConsole::GetNetworkAddr ("eth0");
				$defaultip = $eth0addr['ip'];
				SystemConsole::ConfigVIP ($defaultip, "eth0", $eth0vip, $data['eth0mask']);
				SystemConsole::ConfigNetwork("eth0", $data['eth0ip'], $data['eth0mask'], $data['eth0gw'], $dsnlist);
			} else if ($data['eth'] == 'eth1') {
				$eth1addr = SystemConsole::GetNetworkAddr ("eth1");
				$defaultip = $eth1addr['ip'];
				SystemConsole::ConfigVIP ($defaultip, "eth1", $eth1vip, $data['eth1mask'], $data['usevip']);
				SystemConsole::ConfigNetwork("eth1", $data['eth1ip'], $data['eth1mask'], $data['eth1gw'], $dsnlist);
			}
			$userid = $this->getCurrentUserID();
			$role = $this->getCurrentUserRole(); 
			$uname = $this->getCurrentUser();
			$description = "该用户修改系统网卡配置。";
			$description_en = "This user modify system network setting.";
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '修改系统网卡配置', $description, 'Modify network setting', $description_en, $_SERVER["REMOTE_ADDR"]);			
		}
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "networksetting", "setting");
	}
	
	/**
	 *	consolesetting
	 */
	public function consolesettingAction () {
		$update = $this->_request->get ('update');
		$serviceupdate = $this->_request->get ('serviceupdate');
		if ($update == "yes") {
			$this->Smarty->assign ("update", $update);
		}
		if ($serviceupdate == "yes") {
			$this->Smarty->assign ("serviceupdate", $serviceupdate);
		}
		$infos = $this->console->getAllInfos();
		
		$this->Smarty->assign ("infos", $infos[0]);
		$this->Smarty->assign ("li_menu", "consolesetting");	
		$this->Smarty->display ( 'consolesetting.php' );
	}
	
	public function updateconsoleAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole(); 
		$uname = $this->getCurrentUser();
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$data = array();
			if($_POST['setport'] == 'setport'){
				$data['https'] = $filter->filter ($this->_request->getPost ('encryption'));
				$data['port'] = $filter->filter ($this->_request->getPost ('port'));
				$data['sessiontime'] = $filter->filter ($this->_request->getPost ('sessiontime'));
				$data['id'] = $filter->filter ($this->_request->getPost ('consoleid'));
				// save data to db 
				$this->console->updateConsole ($data);
				$log_str = "禁用HTTPS，";
				$https = false;
				if ($data['https'] == '1') {
					$https = true;
					$log_str = "启用HTTPS，";
				}
				$log_str .= "端口设置为：".$data['port']."，端口超时时间为：".$data['sessiontime']."分钟";
				SystemConsole::setSessionTimout ($data['sessiontime']);
				$result = SystemConsole::UpdateConsole ("", $data['port'], $https, "maildelivery");
				$description = "该用户修改管理端口设置，".$log_str;
				$description_en = "This user modify Management port settings.";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '修改管理端口设置', $description, 'Modify Management port settings', $description_en, $_SERVER["REMOTE_ADDR"]);
				if ($result) {
					SystemConsole::ApacheRestart();
					$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "consolesetting", "setting", null, array ("update" => "yes") );
				} else {
					$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "consolesetting", "setting", null, array ("update" => "no") );
				}
			}else if($_POST['setport'] == 'setserviceport'){
				// $data['servicehttps'] = $filter->filter ($this->_request->getPost ('servicencryption'));
				$data['domainname'] = $filter->filter ($this->_request->getPost ('domainname'));
				$data['serviceport'] = $filter->filter ($this->_request->getPost ('serviceport'));
				$log_str = "域名为：".$data['domainname']."，服务端口为：".$data['serviceport'];
				$data['id'] = $filter->filter ($this->_request->getPost ('consoleid'));
				$this->console->updateConsole ($data);
				$servicehttps = false;
				$description = "该用户修改订阅服务端口设置，".$log_str;
				$description_en = "This user modifySubscription service port settings.";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '修改订阅服务端口设置', $description, 'Modify Subscription service port settings', $description_en, $_SERVER["REMOTE_ADDR"]);
				$result = SystemConsole::UpdateConsole ($data['domainname'], $data['serviceport'], $servicehttps, "maildeliveryservice");
				$info = $this->dbAdapter->fetchRow("select * from mr__console");
				if($info['https'] == '1'){
					$https = true;
				}
				$result2 = SystemConsole::UpdateConsole ($info['domainname'], $info['port'], $https, "maildelivery");
				if ($result) {
					SystemConsole::ApacheRestart();
					$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "consolesetting", "setting", null, array ("serviceupdate" => "yes") );
				} else {
					$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "consolesetting", "setting", null, array ("serviceupdate" => "no") );
				}
			}
			//$result = SystemConsole::UpdateConsole ("", $data['port'], $https);
		}
	}
	
	/**
	 *	sysclocksetting
	 */
	public function sysclocksettingAction () {
		$systime = date ("Y年m月d日 H时i分s秒", time());
		$ntp = $this->ntp->getAllInfos ();
		$actiontype = $this->_request->get ('actiontype');
		
		
		$this->Smarty->assign ("actiontype", $actiontype);
		$this->Smarty->assign ("ntp", $ntp[0]);
		$this->Smarty->assign ("systime", $systime);	
		$this->Smarty->assign ("li_menu", "sysclocksetting");	
		$this->Smarty->display ( 'sysclocksetting.php' );
	}
	
	public function refreshsysclockAction() {
		$systime = date( "Y年m月d日 H时i分s秒", time() );
		echo $systime;
	}
	
	public function customtimeAction () {
		$role = $this->getCurrentUserRole(); 
		$uname = $this->getCurrentUser();
		$userid = $this->getCurrentUserID();
		if ($this->_request->isPost()){
			$filter = new Zend_Filter_StripTags();
			$settime = $filter->filter ($this->_request->getPost ('settime'));
		}
		if ($settime != null && $settime != "") {
			$str = explode (" ", $settime);
			$settime = "'".$str[1]." ".$str[0]."'";
			SystemConsole::CustomSysTime ($settime);
		}
		$description = "该用户自定义系统时间。设定时间为".$settime;
		$description_en = "The user custom the system time, the time is ".$settime;
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '自定义系统时间', $description, 'Custom the system time', $description_en, $_SERVER["REMOTE_ADDR"]);
		$this->_helper->getHelper('Redirector')->setGotoSimple("sysclocksetting", "setting", null, array ("actiontype" => "custom"));
	}
	
	public function updatentpAction () {
		$role = $this->getCurrentUserRole(); 
		$uname = $this->getCurrentUser();
		$userid = $this->getCurrentUserID();
		if ($this->_request->isPost()){
			$filter = new Zend_Filter_StripTags();
			$data = array (
				'status' => $filter->filter ($this->_request->getPost ('status')),
				'ip' => $filter->filter ($this->_request->getPost ('ip'))
			);	
		}
		$ntpid = $this->_request->getPost ('ntpid');
		if ($data['status'] == '1') {
			$description = "该用户修改时钟同步设置。NTP服务器地址改为".$data['ip'];
			$description_en = "The user modify NTP Configuration, the NTP server is changed to ".$data['ip'];
		} else {
			$description = "该用户修改时钟同步设置。NTP服务器禁用";
			$description_en = "The user modify NTP Configuration, the NTP server is disabled";
		}
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '修改时钟同步设置', $description, 'Modify NTP Configuration', $description_en, $_SERVER["REMOTE_ADDR"]);
		if ($ntpid != null && $ntpid != "") {
			$this->ntp->updateNtp ($data, $ntpid); 
		} else {
			$this->ntp->insertNtp ($data); 
		}
		if ($data['status'] == '0') {
			$this->_helper->getHelper('Redirector')->setGotoSimple("sysclocksetting", "setting", null, array ("actiontype" => "no"));
		}else{
			$this->_helper->getHelper('Redirector')->setGotoSimple("sysclocksetting", "setting", null, array ("actiontype" => "ntp"));
		}
	}
	
	/**
	 * working report
	 */
	
	public function workingreportAction () {
		$num = $this->_request->get ( 'num' );
		if ($num == "" || $num == null) {
			$num = 10;
		}
		$this->Smarty->assign ("anum", $num);
		$total = $this->workingreport->getAllCount();
		$page = new Page ($total, $num, $parameter);
		$infos = $this->workingreport->getAllInfos("order by id desc "."{$page->limit}");
		$this->Smarty->assign ("page", $page->fpage());
		$this->Smarty->assign ("infos", $infos);
		$this->Smarty->assign ("li_menu", "workingreport");
		$this->Smarty->display('workingreport.php');
	}
	
	public function addworkingreportAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$data = array (
				'domain' => $filter->filter ( $this->_request->getPost ( 'domain' )),
				'recipient' => $filter->filter ( $this->_request->getPost ( 'recipient' )),
				'timespan' => $filter->filter ( $this->_request->getPost ( 'timespan' )),
				'reporttime' => $filter->filter ( $this->_request->getPost ( 'reporttime' ))
			);
			$wrid = $filter->filter ( $this->_request->getPost ( 'wrid' ));
			if ($wrid == "" || $wrid == null) {
				$new_id = $this->workingreport->insertWR ($data);
				SystemConsole::configCrontabForReport ( $new_id , $data['reporttime'], "add" );
			} else {
				$this->workingreport->updateWR ($data, $wrid);
				SystemConsole::configCrontabForReport ( $wrid , $data['reporttime'], "update" );
			}
			$description = "该用户添加运行情况邮件报告,添加成功。报告发送邮件域为：".$data['domain'];
			$description_en = "The user adds the report of operation situation of mail, added successfully.Report to email domain: ".$data['domain'];
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '添加运行情况邮件报告', $description, 'Add report of operation situation of mail', $description_en, $_SERVER["REMOTE_ADDR"]);
		}	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "workingreport", "setting", null);
	}
	
	public function getworkingreportinfoAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$id = $this->_request->get ( 'wrid' );
		$this->Smarty->assign ("show_id", $id);
		$infos = $this->workingreport->getInfoByWRID ($id);
		echo json_encode($infos[0]);
	}
	
	public function delworkingreportAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$domains = "";
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$list = $filter->filter ( $this->_request->getPost ( 'infolist' ));
			$id_strs = explode ("@", $list);
			$del_arr = array();
			foreach ($id_strs as $id) {
				if ($id != "" && $id != null) {
					$domain = $this->dbAdapter->fetchOne("select domain from `mr_workingreport` where id=".$id);
					$domains .= $domain.",";
					$this->workingreport->delWR ($id);
					$del_arr[] = $id;
				}
			}
			$domains = trim($domains,",");
			SystemConsole::configCrontabForReport ( $del_arr , "", "delete" );
			$description = "该用户删除运行情况邮件报告,删除成功。报告发送邮件域为：".$domains;
		    $description_en = "The user dels the report of operation situation of mail, deleted successfully.Report to email domain: ".$domains;
		    BehaviorTrack::addBehaviorLog($uname, $role, $userid, '删除运行情况邮件报告', $description, 'Del report of operation situation of mail', $description_en, $_SERVER["REMOTE_ADDR"]);
		}	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "workingreport", "setting", null);
	}
	
	public function checkroutingipAction () {
		$ip = $this->_request->get ( 'ip' );
		$mode = $this->_request->get ( 'mode' );
		if ($mode == '2') {
			$re = SystemConsole::checklocalservice();
			foreach ($re as $re_p) {
				echo $re_p.'@';
			}
		} else {
			$re = SystemConsole::checkHostStatus ($ip);
			echo $re;
		}
	}
	
	/**
	 *	resetsetting
	 */
	public function resetsettingAction () {
		$uname = $this->getcurrentuser();
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$action = $this->_request->get ( 'actiontype' );
		switch ($action) {
			case "r":
				$description = "该用户重启设备。";
				$description_en = "This user reboot divece.";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '重启设备', $description, 'reboot divece', $description_en, $_SERVER["REMOTE_ADDR"]);
				SystemConsole::RebootDevice ();
				break;
			case "s":
				$description = "该用户关闭设备。";
				$description_en = "This user shutdown device.";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '该用户关闭设备', $description, 'shutdown device', $description_en, $_SERVER["REMOTE_ADDR"]);
				SystemConsole::ShutDownDevice ();
				break;
			case "rdd":
				$description = "该用户重启数据库服务。";
				$description_en = "This user restart mysqld service.";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '该用户重启数据库服务', $description, 'restart mysqld service', $description_en, $_SERVER["REMOTE_ADDR"]);
				SystemConsole::RestartMysqld ();
				break;
			case "rdt":
				$description = "该用户重启SMTP服务。";
				$description_en = "This user restart SMTP service.";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '该用户重启SMTP服务', $description, 'restart SMTP service', $description_en, $_SERVER["REMOTE_ADDR"]);
				SystemConsole::RestartSMTP ();
				break;
			case "rds":
				$description = "该用户重启投递服务。";
				$description_en = "This user restart delivery service.";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '该用户重启投递服务', $description, 'restart delivery service', $description_en, $_SERVER["REMOTE_ADDR"]);
				SystemConsole::RestartDS ();
				break;
			case "rda":
				$description = "该用户重启信任服务。";
				$description_en = "This user restart authentication service.";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '该用户重启信任服务', $description, 'restart authentication service', $description_en, $_SERVER["REMOTE_ADDR"]);
				SystemConsole::RestartAuth ();
				break;
			case "rdc":
				$description = "该用户重启转换服务。";
				$description_en = "The user restart conversion service.";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '该用户重启转换服务', $description, 'restart authentication service', $description_en, $_SERVER["REMOTE_ADDR"]);
				SystemConsole::RestartConversion ();
				break;
			case "rdf":
				$description = "该用户重启邮件过滤服务。";
				$description_en = "This user restart filter service.";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '该用户重启邮件过滤服务', $description, 'restart filter service', $description_en, $_SERVER["REMOTE_ADDR"]);
				SystemConsole::RestartFilter ();
				break;
			default:
				break;
		}
		$this->Smarty->assign ("actiontype", $action);	
		$this->Smarty->assign ("li_menu", "resetsetting");	
		$this->Smarty->display ( 'resetsetting.php' );
	}
	
	/**
	 *	networktool
	 */
	public function networktoolAction () {
		
		$this->Smarty->assign ("li_menu", "networktool");	
		$this->Smarty->display ( 'networktool.php' );
	}
	
	public function pingtestAction () {
		$uname = $this->getcurrentuser();
		$userid = $this->getCurrentUserID();
		$lang = Zend_Registry::get('cur_locale');
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ( );
			$data = array ('server' => $filter->filter ( $this->_request->getPost ( 'pingtest' ) ) );
			$pattern = '/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/';
			$status = preg_match($pattern, $data['server']);
			if ($status != 1) {
				$this->Smarty->assign ("a", "Your IP is Invaild!");			
				$this->Smarty->assign ("li_menu", "networktool");	
				$this->Smarty->display ( 'networktool.php' );
				exit();
			}
			if (((($data['server'] == '127.0.0.1') || ($data['server'] == 'localhost') || ($data['server'] == getenv("SERVER_ADDR")))) || ((($data['server'] == '127.0.0.1') || ($data['server'] == 'localhost') || ($data['server'] == getenv("SERVER_ADDR")))))
			{
				if ($lang == "en") {
					$a = "Please don't use the local IP address on test";
				} else {
					$a = "请不要使用本地IP地址进行测试";
				}
			} 
			else if ($data['server'] == "")
			{
				if ($lang == "en") {
					$a = "Please enter the host name or IP address";
				} else {
					$a = "请输入主机名或IP地址";
				}
			}
			else
			{
				if($data['server'] !==""){
					$ip = getenv("REMOTE_ADDR");
					$hname = getenv("HTTP_HOST");
					$a = "Your IP is: $ip\n\r"."Trying to ping: $data[server]\n\r"."Using server: $hname\n\r";
					$b = "STATS:\n\r";
					$command ="sudo ping -c 4 ".$data['server'] ;
					exec($command, $result, $rval);
					if(count($result) <= 0) {
						if ($lang == "en") {
							$c = "No Response";
						} else {
							$c = "没有响应";
						}
					}
					$d = array();
					for ($i = 0; $i < count($result); $i++) {
						$d[] = "$result[$i]\n\r";
					}	
				}
			}
			if (count($data) > 0 && $data != '' && $data != null) {
				$description = "该用户进行ping测试。主机IP地址为: ".$data['server'];
				$description_en = "The users ping test. Host IP address for: ".$data['server'];
				$role = $this->getCurrentUserRole();
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, 'Ping测试', $description, 'Ping Testing', $description_en, $_SERVER["REMOTE_ADDR"]);
			}
		}
		$this->Smarty->assign ("a", $a);	
		$this->Smarty->assign ("b", $b);
		$this->Smarty->assign ("c", $c);
		$this->Smarty->assign ("d", $d);		
		$this->Smarty->assign ("li_menu", "networktool");	
		$this->Smarty->display ( 'networktool.php' );
	}
	
	public function trancerouteAction () {
		$uname = $this->getcurrentuser();
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$lang = Zend_Registry::get('cur_locale');
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$data = array ('tserver' => $filter->filter ( $this->_request->getPost ( 'traceroutetest' ) ) );
			$pattern = '/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/';
			$status = preg_match($pattern, $data['tserver']);
			if ($status != 1) {
				$this->Smarty->assign ("a", "Your IP is Invaild!");			
				$this->Smarty->assign ("li_menu", "networktool");	
				$this->Smarty->display ( 'networktool.php' );
				exit();
			}
			if ( $data['tserver'] == "" ) {
				if ($lang == "en") {
					$a = "Please enter the host name or IP address";
				} else {
					$a = "请输入主机名或IP地址";
				}
			} else {
				$ip = getenv("REMOTE_ADDR");
				$hname = getenv("HTTP_HOST");
				$a = "Your IP is: $ip\n\r"."Trying to TranceRoute: $data[tserver]\n\r"."Using server: $hname\n\r";
				$b = "STATS:\n\r ";
				$command ="sudo traceroute ".$data['tserver'];
				exec($command, $result, $rval);
				if(count($result) <= 0) {
					if ($lang == "en") {
						$c = "No Response";
					} else {
						$c = "没有响应";
					}
				}
				$d = array();
				for ($i = 0; $i < count($result); $i++) {
					$d[] = "$result[$i]\n\r";
				}	
			}
			if (count($data) > 0 && $data != '' && $data != null) {
				$description = "该用户进行TranceRoute测试。主机IP地址为: ".$data['tserver'];
				$description_en = "The users TranceRoute test. Host IP address for: ".$data['server'];
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, 'TranceRoute测试', $description, 'TranceRoute Testing', $description_en, $_SERVER["REMOTE_ADDR"]);
			}
		}
		$this->Smarty->assign ("a", $a);	
		$this->Smarty->assign ("b", $b);
		$this->Smarty->assign ("c", $c);
		$this->Smarty->assign ("d", $d);		
		$this->Smarty->assign ("li_menu", "networktool");	
		$this->Smarty->display ( 'networktool.php' );
	}
	
	public function telnettestAction(){
		$uname = $this->getcurrentuser();
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$lang = Zend_Registry::get('cur_locale');
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ( );
			$data = array (
							'telnetserver' => $filter->filter ( $this->_request->getPost ( 'telnettest' ) ),
							'telnetport' => $filter->filter ( $this->_request->getPost ( 'telnetport' ) )
							);
			$pattern = '/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/';
			$status = preg_match($pattern, $data['telnetserver']);
			if ($status != 1) {
				$this->Smarty->assign ("a", "Your IP is Invaild!");			
				$this->Smarty->assign ("li_menu", "networktool");	
				$this->Smarty->display ( 'networktool.php' );
				exit();
			}	
			if (!is_numeric($data['telnetport'])) {
				$this->Smarty->assign ("a", "Your Port is Invaild!");			
				$this->Smarty->assign ("li_menu", "networktool");	
				$this->Smarty->display ( 'networktool.php' );
				exit();
			}	
			if ( $data['telnetserver'] == "" || $data['telnetport'] == "" ) {
				if ($lang == "en") {
					$a = "Please enter the host name or IP address";
				} else {
					$a = "请输入主机名或IP地址";
				}
			} else {
				$ip = getenv("REMOTE_ADDR");
				$hname = getenv("HTTP_HOST");
				$a =  "Your IP is: $ip\n\r"."Trying to Telnet: $data[telnetserver] Port: $data[telnetport]\n\r"."Using server: $hname\n\r";
				$b = "STATS:\n\r";
				$command ="sudo telnet ".$data['telnetserver']." ".$data['telnetport'];
				exec($command, $result, $rval);
				if(count($result) <= 1) {
					if ($lang == "en") {
						$c = "No Response";
					} else {
						$c = "没有响应";
					}
				}
				$d = array();
				for ($i = 0; $i < count($result); $i++) {
					$d[] = "$result[$i]\n\r";
				}	
			}
			if (count($data) > 0 && $data != '' && $data != null) {
				$description = "该用户进行Telnet测试。主机IP地址为: ".$data['telnetserver']."，端口为: ".$data['telnetport']."。";
				$description_en = "The users Telnet test. Host IP address for: ".$data['telnetserver'].", Port: ".$data['telnetport']."。";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, 'Telnet测试', $description, 'Telnet Testing', $description_en, $_SERVER["REMOTE_ADDR"]);
			}
		}
		$this->Smarty->assign ("a", $a);	
		$this->Smarty->assign ("b", $b);
		$this->Smarty->assign ("c", $c);
		$this->Smarty->assign ("d", $d);		
		$this->Smarty->assign ("li_menu", "networktool");	
		$this->Smarty->display ( 'networktool.php' );
	}
	
	/**
	 *	snmpconfiguration
	 */
	public function snmpconfigurationAction () {
		$datas = SystemConsole::ReadSnmpConfig();
		$this->Smarty->assign ("ifuse", $datas['use']);
		$this->Smarty->assign ("connection_str", $datas['connection_str']);
		$this->Smarty->assign ("ip_area", $datas['ip_area']);
		$this->Smarty->assign ("snmpuser", $datas['user']);
		$this->Smarty->assign ("password", $datas['password']);
		$this->Smarty->assign ("auth_type", $datas['auth_type']);
		$this->Smarty->assign ("encryption", $datas['encryption']);
		$this->Smarty->assign ("version", $datas['version']);	
		
		$actiontype = $this->_request->get ( 'actiontype' );
		$this->Smarty->assign ("actiontype", $actiontype);	
		$this->Smarty->assign ("li_menu", "snmpconfiguration");	
		$this->Smarty->display ( 'snmpconfiguration.php' );
	}
	
	public function addsnmpAction () {
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$data = array ( 
					'snmpserver' => $filter->filter ( $this->_request->getPost ( 'snmpserver' ) ),
					'versions' => $filter->filter ( $this->_request->getPost ( 'versions' ) ),
					'connstr' => $filter->filter ( $this->_request->getPost ( 'connstr' ) ),
					'iparea' => $filter->filter ( $this->_request->getPost ( 'iparea' ) ),
					'username' => $filter->filter ( $this->_request->getPost ( 'username' ) ),
					'pwd' => $filter->filter ( $this->_request->getPost ( 'pwd' ) ),
					'authtype' => $filter->filter ( $this->_request->getPost ( 'authtype' ) ),
					'encryption' => $filter->filter ( $this->_request->getPost ( 'encryption' ) )
				);
			SystemConsole::ConfigSnmp($data);
			$actiontype = "disable";
			if ($data['snmpserver'] != null && $data['snmpserver'] != "") {
				$actiontype = $data['snmpserver'];
			} 
		}
		$userid = $this->getCurrentUserID();
		$uname = $this->getcurrentuser();
		$role = $this->getCurrentUserRole();
		$description = "该用户添加SNMP服务，SNMP版本为：".$data['versions'];
		$description_en = "The users to add SNMP service, SNMP version for".$data['versions'];
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '添加SNMP服务', $description, 'Add SNMP service', $description_en, $_SERVER["REMOTE_ADDR"]);
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "snmpconfiguration", "setting", null, array ("actiontype" => $actiontype) );
	}
	
	/**
	 *	license
	 */
	 
	public function licenseAction () {
		$infos = SystemConsole::GetLicenseInfo();
		$this->Smarty->assign ("infos", $infos);
		$this->Smarty->assign ("li_menu", "license");	
		$this->Smarty->display ( 'license.php' );
	}
	
	public function downloadmdapkeyAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole(); 
		$uname = $this->getCurrentUser();
		$info_file = "/opt/share/longgerconf/mdkey";
		@exec("sudo chmod 777 ".$info_file, $return_array, $status);
		if (is_file ( $info_file ) && file_exists($info_file)) {
			// file size in bytes
			$fsize = filesize ( $info_file );
			Header ( "Pragma: public" );
			Header ( "Expires: 0" ); // set expiration time
			Header ( "Cache-Component: must-revalidate, post-check=0, pre-check=0" );
			Header ( "Content-Length: " . $fsize );
			Header ( "Content-Disposition: attachment; filename=\"mdkey\"" );
			Header ( 'Content-Transfer-Encoding: binary' );
			$file = @fopen ( $info_file, "rb" );
			if ($file) {
				while ( ! feof ( $file ) ) {
					print ( fread ( $file, 20480 ) );
					flush ();
					if (connection_status () != 0) {
						@fclose ( $file );
					}
				}
				@fclose ( $file );
			}
			$description = "该用户下载机器码信息文件，下载成功。";
			$description_en = "This user download device info file, download succeeded.";
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '下载机器码信息文件', $description, 'Download device info file', $description_en, $_SERVER["REMOTE_ADDR"]);
		} else {
			Header ( "Pragma: public" );
			Header ( "Expires: 0" ); // set expiration time
			Header ( "Cache-Component: must-revalidate, post-check=0, pre-check=0" );
			Header ( "Content-Disposition: attachment; filename=\"mdkey\"" );
			Header ( 'Content-Transfer-Encoding: binary' );
			if (Zend_Registry::get('cur_locale') == 'en') {
				print("Machine information file is corrupted! Please contact Administrator!");
			} else {
				print("机器码信息文件内容已损坏！请联系管理员！");
			}
			flush ();
			$description = "该用户下载机器码信息文件，下载失败。";
			$description_en = "This user download device info file, download failed.";
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '下载机器码信息文件', $description, 'Download device info file', $description_en, $_SERVER["REMOTE_ADDR"]);
		}
	}
	
	public function uploadliceseAction () {
		if ($this->_request->isPost ()) {
			$upload = new Zend_File_Transfer_Adapter_Http (); 
			$upload_dir = "/opt/share/longgerconf";//linux
			// $upload_dir = "D:\AppServ\www\V1.1\web\uploads";//windows
			$upload->setDestination ($upload_dir);
			//$upload->addValidator ('Extension', false, '');
			$upload->addValidator ('Count', false, array ('min' => 1, 'max' => 5));  
			$upload->addValidator ('FilesSize', false, array ('min' => '0KB', 'max' => '1024KB')); 
			$fileInfo = $upload->getFileInfo (); 
			$newname = $upload_dir.'/'.$fileInfo['files']['name'];
			if (is_file($newname)) {
				unlink($newname);
			}
			// addFilter 这个改名字的,你自己评估下用不用吧.
			$upload->addFilter('Rename', array ('target' => $newname, 'overwrite' => false));
			$userid = $this->getCurrentUserID();
			$role = $this->getCurrentUserRole(); 
			$uname = $this->getCurrentUser();
			$description = "该用户进行模板操作.上传模板名字为: ".$fileInfo['files']['name'];
			$description_en = "The user template upload operation. The template is: ".$fileInfo['files']['name'];
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '上传操作', $description, 'UPload operation', $description_en, $_SERVER["REMOTE_ADDR"]);
			if (!$upload->receive ()) {
				$messages = $upload->getMessages ();
				echo implode ("\n", $messages);
			} else {
				echo "success";
			}
		}
	}
	
	/**
	 *	The security param
	 */
 
	public function securityparamAction () {
		$info = $this->securityparam->getSecurityParam();
		$this->Smarty->assign ("info", $info);
		$mrengine = SystemConsole::readMrEngine ();
		$this->Smarty->assign ("threadnum", $mrengine['threadnum']);
		$this->Smarty->assign ("bounce", $mrengine['bounce']);
		$this->Smarty->assign ("li_menu", "securityparam");
		$this->Smarty->display('securityparam.php');
	}
	
	public function addsecurityparamAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$data = array (
				'mainfield' => $filter->filter ( $this->_request->getPost ( 'mainfield' )),
				'hostname' => $filter->filter ( $this->_request->getPost ( 'hostname' )),
				'threadnum' => $filter->filter ( $this->_request->getPost ( 'threadnum' )),
				'queuetime' => $filter->filter ( $this->_request->getPost ( 'queuetime' )),
				'logtime' => $filter->filter ( $this->_request->getPost ( 'logtime' )),
				'bounce' => $filter->filter ( $this->_request->getPost ( 'bounce' ))
			);
            
			$inset = array();
			$inset['mainfield'] = $data['mainfield'];
			$inset['hostname'] = $data['hostname'];
			$inset['queuetime'] = $data['queuetime'];
			$inset['logtime'] = $data['logtime'];
			SystemConsole::configMainCf($data);
			SystemConsole::configMrengineConf($data);
			$id = $this->_request->getPost ( 'spid' );
			if ($id != "" && $id != null) {
				$description = "该用户更新投递参数设置。";
				$description_en = "This user update security param.";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '更新投递参数设置', $description, 'update security param', $description_en, $_SERVER["REMOTE_ADDR"]);
				$this->securityparam->updateSecurityParam($inset, $id);
			} else {
				$description = "该用户设置投递参数设置。";
				$description_en = "This user set security param.";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '设置投递参数设置', $description, 'set security param', $description_en, $_SERVER["REMOTE_ADDR"]);
				$this->securityparam->addSecurityParam($inset);
			}
			
			SystemConsole::ReloadRelayCfg();
		}	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "securityparam", "setting", null);
	}
	
	/**
	 *	Single Domain 
	 */
	
	public function singledomainAction () {
		$num = $this->_request->get ( 'num' );
		if ($num == "" || $num == null) {
			$num = 10;
		}
		$utype = $this->getCurrentUserType();
		$this->Smarty->assign ("utype", $utype);
		$this->Smarty->assign ("anum", $num);
		$total = $this->singledomain->getAllCount();
		$page = new Page ($total, $num, $parameter);
		$infos = $this->singledomain->getAllInfos("order by id desc "."{$page->limit}");
		$this->Smarty->assign ("page", $page->fpage());
		$this->Smarty->assign ("infos", $infos);
		$this->Smarty->assign ("li_menu", "singledomain");
		$this->Smarty->display('singledomain.php');
	}
	
	public function addsingledomainAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$domain=$filter->filter ( $this->_request->getPost ( 'domain' ));
			if($domain == '全局'){
				$domain='*';
			}
			$data = array (
				'domain' => $domain,
				'timeout' => $filter->filter ( $this->_request->getPost ( 'timeout' )),
				'reconnect' => $filter->filter ( $this->_request->getPost ( 'reconnect' )),
				'sipmaximum' => $filter->filter ( $this->_request->getPost ( 'sipmaximum' )),
				'maxusersinbatch' => $filter->filter ( $this->_request->getPost ( 'maxusersinbatch' )),
				'smailsize' => $filter->filter ( $this->_request->getPost ( 'smailsize' )),
				'smailattchsize' => $filter->filter ( $this->_request->getPost ( 'smailattchsize' )),
				'unavailableip' => $filter->filter ( $this->_request->getPost ( 'unavailableip' ))
			);
			$rtid = $filter->filter ( $this->_request->getPost ( 'rtid' ));
			if ($rtid == "" || $rtid == null) {
				$description = "该用户添加单域名配置，添加域名为：".$data['domain'];
				$description_en = "The user adds a single domain configuration, add the domain name:".$this->_request->getPost ( 'name' ).".The operation of user:".$uname;
				try {
					$this->singledomain->addSingleDomain ($data);
				} catch (Exception $e) {
				}				
			} else {
				$description = "该用户更新单域名配置，更新域名为：".$data['domain'];
				$description_en = "The user updates a single domain configuration, update the domain name:".$this->_request->getPost ( 'name' ).".The operation of user:".$uname;
				$this->singledomain->updateSingleDomain ($data, $rtid);
			}
		}
		
		SystemConsole::ReloadRelayCfg();
		
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '单域名配置', $description, 'Single Domain', $description_en, $_SERVER["REMOTE_ADDR"]);
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "singledomain", "setting", null);
	}
	
	public function checksingledomainAction () {
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$domain = $filter->filter ( $this->_request->getPost ( 'domain' ));
			$id = $filter->filter ( $this->_request->getPost ( 'rtid' ));
			$status = $this->singledomain->checkDomain ($domain, $id);
			if ( empty ( $status ) ) {
				echo "pass";
			} else {
				echo "1";
			}
		}
	}
	
	public function getsingledomainAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$id = $this->_request->get ( 'rtid' );
		if (is_numeric($id)) {
			$this->Smarty->assign ("show_id", $id);
			$infos = $this->singledomain->getSingleDomainByID ($id);
			echo json_encode($infos[0]);
		} else {
			echo "";
		}
	}
	
	public function delsingledomainAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$domains = "";
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$list = $filter->filter ( $this->_request->getPost ( 'infolist' ));
			$id_strs = explode ("@", $list);
			foreach ($id_strs as $id) {
				if ($id != "" && $id != null) {
					$domain = $this->dbAdapter->fetchOne("select domain from `mr_singledomain` where id=".$id);
					$domains .= $domain.",";
					$this->singledomain->delRT ($id);
				}
			}
		}
		$domains = trim($domains,",");
		SystemConsole::ReloadRelayCfg();
		$description = "该用户批量删除单域名配置信息，删除域名为：".$domains;
		$description_en = "The user delete user for operation overseas relay routing.The domain name is:".$domains;
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '删除单域名配置', $description, 'Delete overseas routing', $description_en, $_SERVER["REMOTE_ADDR"]);
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "singledomain", "setting", null);
	}
	
	/**
	 *	The trust ip table
	 */
 
	public function checktrustipAction () {
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$ips = $filter->filter ( $this->_request->getPost ( 'ips' ));
			$temips = $filter->filter ( $this->_request->getPost ( 'temips' ));
			if ( strpos ( $ips, PHP_EOL ) !== false ) {
				$ip_arr = explode ( PHP_EOL, $ips );
				foreach ( $ip_arr as $ip ) {
					if ( $ip != $temips ) {
						$status = $this->trustiptable->checkInfoByIP ( $ip );
						if ( !empty ( $status ) ) {
							echo $ip;
							exit;
						}
					}
				}
				echo "pass";
			} else {
				$status = $this->trustiptable->checkInfoByIP ( $ips );
				if ( !empty ( $status ) ) {
					echo $ips;
				} else {
					echo "pass";
				}
			}
		}	
	}
 
	public function trustiptableAction () {
		$num = $this->_request->get ( 'num' );
		if ($num == "" || $num == null) {
			$num = 10;
		}
		$sqls = "";
		$has_val = "no";
		$view_domain = $this->_request->get ( 'domain' );
		if ( $view_domain != "" && $view_domain != null && $view_domain != "all" && $view_domain != "*" ) {
			$sqls .= " and domain='".$view_domain."'";
			$this->Smarty->assign ("view_domain", $view_domain);
			$has_val = "yes";
		}
		$view_ip = $this->_request->get ( 'ip' );
		$view_ip = mysql_escape_string($view_ip);
		if ( $view_ip != "" && $view_ip != null ) {
			$sqls .= " and ips like '%".$view_ip."%'";
			$this->Smarty->assign ("view_ip", stripslashes($view_ip));
			$has_val = "yes";
		}
		$view_uname = $this->_request->get ( 'uname' );
		if ( $view_uname != "" && $view_uname != null && $view_uname != "all" ) {
			$sqls .= " and uname='".$view_uname."'";
			$this->Smarty->assign ("view_uname", $view_uname);
			$has_val = "yes";
		}
		$this->Smarty->assign ( "has_val", json_encode ( $has_val ) );
		// 界面弹窗用到
		$userlist = $this->account->getAllCommonUsers ();
		$this->Smarty->assign ("userlist", $userlist);
		
		$domain = $this->trustiptable->getGroupByCon ( "domain" );
		$this->Smarty->assign ("domain", $domain);
		
		$username = $this->trustiptable->getGroupByCon ( "uname" );
		$this->Smarty->assign ("username", $username);
		
		$this->Smarty->assign ("anum", $num);
		$total = $this->trustiptable->getAllCount( $sqls );
		$page = new Page ($total, $num, $parameter);
		$infos = $this->trustiptable->getAllInfos($sqls, "order by id desc "."{$page->limit}");
		
		$mode = $this->_request->get ( 'mode' );
		if( $mode == "search"){
			$userid = $this->getCurrentUserID();
			$uname = $this->getcurrentuser();
			$role = $this->getCurrentUserRole();
			$description='该用户进行查询信任来源地址的操作';
			$description_en='The user performs the operation of querying trust the source address';
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询操作', $description, 'Query operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		}
		$this->Smarty->assign ("page", $page->fpage());
		$this->Smarty->assign ("infos", $infos);
		$this->Smarty->assign ("li_menu", "trustiptable");
		$this->Smarty->display('trustiptable.php');
	}
	
	public function addtrustiptableAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$uips = "";
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$domain = $filter->filter ( $this->_request->getPost ( 'domain' ));
			$domains = $filter->filter ( $this->_request->getPost ( 'domains' ));

			if($domain){
				$domain_final = $domain;
			}
			if($domains){
				$domain_final = $domains;
			}
			$data = array (
				'domain' => $domain_final,
				'ips' => $filter->filter ( $this->_request->getPost ( 'ips' )),
				'description' => $filter->filter ( $this->_request->getPost ( 'description' ))
			);
			$belong = $filter->filter ( $this->_request->getPost ( 'userlist1' ));
			$tem_arr = explode ( "@", $belong );
			$data['uname'] = $tem_arr[0];
			$data['belong'] = $tem_arr[1];
			$rtid = $filter->filter ( $this->_request->getPost ( 'rtid' ));
			if (strpos($data['ips'], "\r\n") !== false) {
				$ips = explode ( "\r\n", $data['ips'] );
			} else if (strpos($data['ips'], "\n") !== false) {
				$ips = explode ( "\n", $data['ips'] );
			}
			if ($rtid == "" || $rtid == null) {
				if ( is_array ( $ips ) ) {
					foreach ( $ips as $ip ) {
						if ( $ip != "" ) {
							$data['ips'] = $ip;
							$uips .= $data['ips'].",";
							$this->trustiptable->insertRT ($data);
						}
					}
				} else {
					$uips = $data['ips'];
					$this->trustiptable->insertRT ($data);
				}
				$uips = trim($uips,",");
				$description = "该用户添加信任地址，地址为：".$uips;
				$description_en = "The user adds a trusted address,the address is: ".$uips;
			} else {
				$uips = $data['ips'];
				$this->trustiptable->updateRT ($data, $rtid);
				$description = "该用户更新信任地址，地址为：".$uips;
				$description_en = "The user update a trusted address,the address is: ".$uips;
			}
		}	
		SystemConsole::ReloadSpfCfg();
		
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '更新信任地址', $description, 'Update the trustip', $description_en, $_SERVER["REMOTE_ADDR"]);
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "trustiptable", "setting", null);
	}
	
	public function gettrustiptableinfoAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$id = $this->_request->get ( 'rtid' );
		$this->Smarty->assign ("show_id", $id);
		$infos = $this->trustiptable->getInfoByRTID ($id);
		if (strpos($infos[0]['ips'], ";") !== false) {
			$infos[0]['ips'] = str_replace(";", "\r\n", $infos[0]['ips']);
		} 
		echo json_encode($infos[0]);
	}
	
	public function deltrustiptableAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$ips = "";
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$list = $filter->filter ( $this->_request->getPost ( 'infolist' ));
			$id_strs = explode ("@", $list);
			foreach ($id_strs as $id) {
				if ($id != "" && $id != null) {
					$ip = $this->dbAdapter->fetchOne("select ips from `mr_trustiptable` where id=".$id);
					$ips .= $ip.",";
					$this->trustiptable->delRT ($id);
				}
			}
			SystemConsole::ReloadSpfCfg();
			
			$ips = trim($ips,",");
			$description = "该用户删除信任地址.地址为:".$ips;
			$description_en = "The user delete a trusted address. the ip is:".$ips;
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '删除信任地址', $description, 'Delete the trustip', $description_en, $_SERVER["REMOTE_ADDR"]);
		}	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "trustiptable", "setting", null);
	}
	
	public function changebelongAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$list = $filter->filter ( $this->_request->getPost ( 'infolist' ));
			$userinfo = $filter->filter ( $this->_request->getPost ( 'userinfolist' ));
			$userinfo_arr = explode ( "@", $userinfo );
			$dest_uname = $userinfo_arr[0];
			$dest_uid = $userinfo_arr[1];
			$id_strs = explode ("@", $list);
			$sql = "";
			foreach ($id_strs as $id) {
				if ($id != "" && $id != null) {
					if ( $sql == "" ) {
						$sql = $id;
					} else {
						$sql .= ", ".$id;
					}
				}
			}
			$this->trustiptable->changeBelong ( $sql, $dest_uname, $dest_uid );
			SystemConsole::ReloadSpfCfg();

			$description = "该用户更新信任地址归属.操作用户为:".$uname."";
			$description_en = "The user delete a trusted address. Operation user:".$uname;
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '更新信任地址归属', $description, 'Delete the trust', $description_en, $_SERVER["REMOTE_ADDR"]);
		}	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "trustiptable", "setting", null);
	}
	
	/**
	 *	The static mx
	 */
 
	public function staticmxAction () {
		$num = $this->_request->get ( 'num' );
		if ($num == "" || $num == null) {
			$num = 10;
		}
		$this->Smarty->assign ("anum", $num);
		$total = $this->staticmx->getAllCount();
		$page = new Page ($total, $num, $parameter);
		$infos = $this->staticmx->getAllInfos("order by id desc "."{$page->limit}");
		$this->Smarty->assign ("page", $page->fpage());
		$this->Smarty->assign ("infos", $infos);
		$this->Smarty->assign ("li_menu", "staticmx");
		$this->Smarty->display('staticmx.php');
	}
	
	public function checkstaticmxAction () {
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$domain = $filter->filter ( $this->_request->getPost ( 'domain' ));
			$id = $filter->filter ( $this->_request->getPost ( 'rtid' ));
			$status = $this->staticmx->checkDomain ($domain, $id);
			if ( empty ( $status ) ) {
				echo "pass";
			} else {
				echo "1";
			}
		}
	}
	
	public function addstaticmxAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$data = array (
				'domain' => $filter->filter ( $this->_request->getPost ( 'domain' )),
				'ips' => $filter->filter ( $this->_request->getPost ( 'ips' )),
				'description' => $filter->filter ( $this->_request->getPost ( 'description' ))
			);
			if (strpos($data['ips'], "\r\n") !== false) {
				$data['ips'] = str_replace("\r\n", ";", $data['ips']);
			} else if (strpos($data['ips'], "\n") !== false) {
				$data['ips'] = str_replace("\n", ";", $data['ips']);
			}
			$rtid = $filter->filter ( $this->_request->getPost ( 'rtid' ));
			if ($rtid == "" || $rtid == null) {
				$this->staticmx->insertRT ($data);
				$description = "该用户添加静态路由配置，添加的域名为：".$data['domain'];
				$description_en = "This user add static routing configuration, the domain name is: ".$data['domain'];
			} else {
				$this->staticmx->updateRT ($data, $rtid);
				$description = "该用户修改静态路由配置，修改的域名为：".$data['domain'];
				$description_en = "This user modify static routing configuration, the domain name is: ".$data['domain'];
			}
			$userid = $this->getCurrentUserID();
			$role = $this->getCurrentUserRole(); 
			$uname = $this->getCurrentUser();
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '更新静态路由配置', $description, 'Update the static routing configuration', $description_en, $_SERVER["REMOTE_ADDR"]);
			
			SystemConsole::ReloadRelayCfg();
		}	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "staticmx", "setting", null);
	}
	
	public function getstaticmxinfoAction () {
		$id = $this->_request->get ( 'rtid' );
		$this->Smarty->assign ("show_id", $id);
		$infos = $this->staticmx->getInfoByRTID ($id);
		if (strpos($infos[0]['ips'], ";") !== false) {
			$infos[0]['ips'] = str_replace(";", "\r\n", $infos[0]['ips']);
		} 
		echo json_encode($infos[0]);
	}
	
	public function delstaticmxtableAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$domains = "";
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$list = $filter->filter ( $this->_request->getPost ( 'infolist' ));
			$id_strs = explode ("@", $list);
			foreach ($id_strs as $id) {
				if ($id != "" && $id != null) {
					$domain = $this->dbAdapter->fetchOne("select domain from `mr_staticmx` where id=".$id);
					$domains .= $domain.",";
					$this->staticmx->delRT ($id);
				}
			}
			$domains = trim($domains,",");
			$description = "该用户删除静态路由配置，删除的域名为：".$domains;
			$description_en = "This user delete static routing configuration, the domain name is: ".$domains;
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '删除静态路由配置', $description, 'Delete the static routing configuration', $description_en, $_SERVER["REMOTE_ADDR"]);
			
			SystemConsole::ReloadRelayCfg();
		}	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "staticmx", "setting", null);
	}
	
	/**
	 *	The Authentication
	 */
 
	public function authsettingAction () {
		$num = $this->_request->get ( 'num' );
		if ($num == "" || $num == null) {
			$num = 10;
		}
		$this->Smarty->assign ("anum", $num);
		$total = $this->domain->getAllCount();
		$page = new Page ($total, $num, $parameter);
		$infos = $this->domain->getAllDomains("order by id desc "."{$page->limit}");
		$this->Smarty->assign ("page", $page->fpage());
		$this->Smarty->assign ("infos", $infos);
		$this->Smarty->assign ("li_menu", "authsetting");
		$this->Smarty->display('authsetting.php');
	}
	
	public function addauthsettingAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$data = array (
				'domain' => $filter->filter ( $this->_request->getPost ( 'domain' )),
				'server' => $filter->filter ( $this->_request->getPost ( 'server' )),
				'port' => $filter->filter ( $this->_request->getPost ( 'port' )),
				'maxtime' => $filter->filter ( $this->_request->getPost ( 'maxtime' )),
				'protocol' => $filter->filter ( $this->_request->getPost ( 'protocol' )),
				'ad_dn' => $filter->filter ( $this->_request->getPost ( 'ad_dn' )),
				'ad_admin' => $filter->filter ( $this->_request->getPost ( 'ad_admin' )),
				'ad_admin_pwd' => $filter->filter ( $this->_request->getPost ( 'ad_admin_pwd' ))
			);
			$asid = $filter->filter ( $this->_request->getPost ( 'asid' ));
			if ($asid == "" || $asid == null) {
				$this->domain->addDomain ($data);
			} else {
				$this->domain->updateDomain ($data, $asid);
			}

			SystemConsole::ReloadSpfCfg();

			$description = "该用户添加用户认证设置.操作用户为:".$uname.".添加用户域名为:".$this->_request->getPost ( 'domain' );
			$description_en = "The user add user authentication settings. The operation of user:".$uname.". Add user domain:".$this->_request->getPost ( 'domain' );
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '添加用户认证', $description, 'Add user authentication', $description_en, $_SERVER["REMOTE_ADDR"]);
		}	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "authsetting", "setting", null);
	}
	
	public function getauthsettinginfoAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$id = $this->_request->get ( 'asid' );
		$this->Smarty->assign ("show_id", $id);
		$infos = $this->domain->getDomainById ($id);
		echo json_encode($infos);
	}
	
	public function delauthsettingAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$list = $filter->filter ( $this->_request->getPost ( 'infolist' ));
			$id_strs = explode ("@", $list);
			foreach ($id_strs as $id) {
				if ($id != "" && $id != null) {
					$this->domain->delDomain ($id);
				}
			}
			SystemConsole::ReloadSpfCfg();

			$description = "该用户删除用户认证.操作用户为:".$uname;
			$description_en = "The User delete user authentication.The operation of user::".$uname;
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '删除用户认证', $description, 'Delete user authentication', $description_en, $_SERVER["REMOTE_ADDR"]);
		}	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "authsetting", "setting", null);
	}
	
	
	/**
	 *	The user intercept
	 */
	public function userinterceptAction () {
		$num = $this->_request->get ( 'num' );
		if ($num == "" || $num == null) {
			$num = 10;
		}
		$this->Smarty->assign ("anum", $num);
		$total = $this->userintercept->getAllCount();
		$page = new Page ($total, $num, $parameter);
		$infos = $this->userintercept->getAllUsers("order by id desc "."{$page->limit}");
		$this->Smarty->assign ("page", $page->fpage());
		$this->Smarty->assign ("infos", $infos);
		$this->Smarty->assign ("li_menu", "userintercept");
		$this->Smarty->display('userintercept.php');
	}
	//添加拦截用户
	public function addinterceptuserAction () {
		$uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$data = array (
				'mailbox' => $filter->filter ( $this->_request->getPost ( 'mailbox' )),
				'desc' => $filter->filter ( $this->_request->getPost ( 'desc' ))
			);
			$userid = $filter->filter ( $this->_request->getPost ( 'userid' ));
			if (isset($userid) && $userid != '') {
				$this->userintercept->updateUser ($data, $userid);
			} else {
				$this->userintercept->addUser ($data);
			}
			$description = "该用户添加拦截用户.操作用户为:".$uname.".添加用户邮箱为:".$this->_request->getPost ( 'mailbox' );
			$description_en = "The user is added to intercept the user. The operation of user:".$uname.". Add user mail :".$this->_request->getPost ( 'mailbox' );
			BehaviorTrack::addBehaviorLog($uname, $role, $uid, '添加拦截用户', $description, 'Add intercept user', $description_en, $_SERVER["REMOTE_ADDR"]);
		}	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "userintercept", "setting", null);
	}
	//查看拦截用户
	public function getinterceptuserAction () {
		$filter = new Zend_Filter_StripTags ();
		$id = $filter->filter ( $this->_request->get ( 'userid' ) );
		if($id != ''){
			$infos = $this->userintercept->getUserinfo($id);
		}
		echo json_encode($infos);
	}
	//删除拦截用户
	public function delinterceptuserAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$list = $filter->filter ( $this->_request->getPost ( 'infolist' ));
			$id_strs = explode ("@", $list);
			foreach ($id_strs as $id) {
				if ($id != "" && $id != null) {
					$this->userintercept->delUser ($id);
				}
			}
			$description = "该用户删除拦截用户.操作用户为:".$uname;
			$description_en = "The user deletes the intercept user.The operation of user:".$uname;
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '删除拦截用户', $description, 'Delete intercept user', $description_en, $_SERVER["REMOTE_ADDR"]);
		}	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "userintercept", "setting", null);
	}

	public function checkinterceptuserAction () {
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$mail = $filter->filter ( $this->_request->getPost ( 'mail' ));
			$info = $this->userintercept->getUserinfoBymail ( $mail );
			//var_dump($mail);exit;
			if(!empty($info)){
				echo 'no';
			}else{
				echo $mail;
			}
		}
	}
	
	/**
	 *	specialdomain
	 */
	public function specialdomainAction () {
		$infos = $this->specialdomain->getAllInfos ();
		$match = strpos($infos['specialdomain'], ";");
		if ($match !== false) {
			$infos['specialdomain'] = str_replace (";", "\r\n", $infos['specialdomain']);
		}
		$this->Smarty->assign ("infos", $infos);	
		$this->Smarty->assign ("li_menu", "specialdomain");	
		$this->Smarty->display ( 'specialdomain.php' );
	}
	
	public function updatespecialdomainAction () {
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$sdid = $this->_request->getPost ( 'sdid' );
			$specialdomain = $this->_request->getPost ( 'specialdomain' );
			$match = strpos($specialdomain, "\r\n");
			if ($match !== false) {
				$specialdomain = str_replace ("\r\n", ";", $specialdomain);
			}
			$data = array();
			$data['specialdomain'] = $specialdomain;
			if ($sdid != "" && $sdid != null) {
				$this->specialdomain->updateSpecialDomain ($data, $sdid);
			} else {
				$this->specialdomain->insertSpecialDomain ($data);
			}
		}	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "specialdomain", "setting" );
	}
	
	/**
	 *	alertsetting
	 */
	 
	public function alertsettingAction () {
		$actiontype = $this->_request->get ( 'actiontype' );
		$infos = $this->alertsetting->getAllInfos ();
		$match = strpos($infos['recipients'], ";");
		if ($match !== false) {
			$infos['recipients'] = str_replace (";", "\r\n", $infos['recipients']);
		}
		$this->Smarty->assign ("infos", $infos);
		$this->Smarty->assign ("actiontype", $actiontype);
		$this->Smarty->assign ("li_menu", "alertsetting");	
		$this->Smarty->display ( 'alertsetting.php' );
	}
	
	public function addalertsettingAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		if ($this->_request->isPost ()) {
			$log_str = "配置";
			$filter = new Zend_Filter_StripTags ();
			$id = $this->_request->getPost ( 'id' );
			$data = array ( 
				'cpu' => $filter->filter ( $this->_request->getPost ( 'cpu' ) ),
				'mqueue' => $filter->filter ( $this->_request->getPost ( 'mqueue' ) ),
				'subarea' => $filter->filter ( $this->_request->getPost ( 'subarea' ) ),
				'dqueue' => $filter->filter ( $this->_request->getPost ( 'dqueue' ) ),
				'recipients' => $filter->filter ( $this->_request->getPost ( 'recipients' ) )
			);
			$match = strpos($data['recipients'], "\r\n");
			if ($match !== false) {
				$data['recipients'] = str_replace ("\r\n", ";", $data['recipients']);
			}
			if ($data['cpu'] != "" && $data['cpu'] != "0") {
				$log_str .= "CPU告警：".$data['cpu']."%，";
			}
			if ($data['mqueue'] != "" && $data['mqueue'] != "0") {
				$log_str .= "转发队列数量告警：".$data['mqueue']."封，";
			}
			if ($data['subarea'] != "" && $data['subarea'] != "0") {
				$log_str .= "存储告警：".$data['subarea']."%，";
			}
			if ($data['dqueue'] != "" && $data['dqueue'] != "0") {
				$log_str .= "投递失败数量告警：".$data['dqueue']."%，";
			}
			$log_str .= "告警邮件接收人：".$data['recipients'];
			$actiontype = "";
			if ($id != "" && $id != null) {
				$description = "该用户更新系统告警配置，".$log_str;
				$description_en = "The user update System alarm configuration";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '系统告警配置', $description, 'System alarm configuration', $description_en, $_SERVER["REMOTE_ADDR"]);
				$actiontype = "update";
				$this->alertsetting->updateAlertSetting ($data, $id);
			} else {
				$description = "该用户添加系统告警配置，".$log_str;
				$description_en = "The user add System alarm configuration";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '系统告警配置', $description, 'System alarm configuration', $description_en, $_SERVER["REMOTE_ADDR"]);
				$actiontype = "insert";
				$this->alertsetting->insertAlertSetting ($data);
			}
		}	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "alertsetting", "setting", null, array ("actiontype" => $actiontype) );
	}
	
	
	
	/**
	 *	accountmanage
	 */
	public function accountmanageAction () {
		$role = $this->getCurrentUserRole(); 
		$uname = $this->getCurrentUser();
		$userid = $this->getCurrentUserID();
		if ($role == "sadmin") {
			$userlist = $this->account->getSAdminUsers ();
		}
		if ($role == "admin") {
			$userlist = $this->account->getAdminUsers ($userid);
		}
		if ($role == "stasker") {
			$userlist = $this->account->getSTaskerUsers ($userid);
		}
		if ($role == "tasker") {
			$userlist = $this->account->getTaskerUsers ($userid);
		}
		for($i = 0; $i <count($userlist); $i++){
			$info = $this->dbAdapter->fetchOne("select username from mr_accounts where id =".$userlist[$i]['parentid']."");
			$userlist[$i]['parentid'] = $info;
		}
		$this->Smarty->assign ("role", $role);	
		$this->Smarty->assign ("userlist", $userlist);	
		$this->Smarty->assign ("li_menu", "accountmanage");	
		$this->Smarty->display ( 'accountmanage.php' );
	}
	
	public function admincreationAction () {
		$role = $this->getCurrentUserRole(); 
		$uname = $this->getCurrentUser();
		$userid = $this->getCurrentUserID();
		$is_sadmin = false;
		if ($role == "sadmin") {
			$is_sadmin = true;
		}
		$infos = $this->dbAdapter->fetchAll("select username from mr_accounts where parentid = 1 and role = 'stasker' ");
		
		$this->Smarty->assign ("infos", $infos);
		$this->Smarty->assign ("is_sadmin", $is_sadmin);
		$this->Smarty->assign ("cur_role", $role);
		$this->Smarty->assign ("li_menu", "accountmanage");	
		$this->Smarty->display ( 'admincreation.php' );
	}
	
	public function addadminAction () {
		$role = $this->getCurrentUserRole(); 
		$uname = $this->getCurrentUser();
		$userid = $this->getCurrentUserID();
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$uid = $this->_request->getPost ( 'uid' );
			$data = array ( 
				'username' => $filter->filter ( $this->_request->getPost ( 'username' ) ),
				'lock' => $filter->filter ( $this->_request->getPost ( 'lock' ) ),
				'password' => $filter->filter ( $this->_request->getPost ( 'password' ) ),
				'password1' => $filter->filter ( $this->_request->getPost ( 'password1' ) ),
				'belongs' => $filter->filter ( $this->_request->getPost ( 'belongs' ) ),
				'mail' => $filter->filter ( $this->_request->getPost ( 'mail' ) ),
				'audit' => $filter->filter ( $this->_request->getPost ( 'audit' ) ),
				'trustip' => $filter->filter ( $this->_request->getPost ( 'trustip' ) )
			);
			//$usertype = $filter->filter ( $this->_request->getPost ( 'usertype' ) );
			$acldata = array (
				//首页
				'firstpage' => $filter->filter($this->_request->getPost('firstpage')),
				//系统监控
				'systemmonitor' => $filter->filter($this->_request->getPost('systemmonitor')),
				'mailstatistics' => $filter->filter($this->_request->getPost('mailstatistics')),
				'mailqueue' => $filter->filter($this->_request->getPost('mailqueue')),
				'searchlogs' => $filter->filter($this->_request->getPost('searchlogs')),
				'denyaccess' => $filter->filter($this->_request->getPost('denyaccess')),
				//系统设置
				'consolesetting' => $filter->filter($this->_request->getPost('consolesetting')),
				'sendmail' => $filter->filter($this->_request->getPost('sendmail')),
				'alertsetting' => $filter->filter($this->_request->getPost('alertsetting')),
				'sysclocksetting' => $filter->filter($this->_request->getPost('sysclocksetting')),
				'workingreport' => $filter->filter($this->_request->getPost('workingreport')),
				'license' => $filter->filter($this->_request->getPost('license')),
				'resetsetting' => $filter->filter($this->_request->getPost('resetsetting')),
				'publishedinfo' => $filter->filter($this->_request->getPost('publishedinfo')),
				//网络设置
				'networksetting' => $filter->filter($this->_request->getPost('networksetting')),
				'networktool' => $filter->filter($this->_request->getPost('networktool')),
				'snmpconfiguration' => $filter->filter($this->_request->getPost('snmpconfiguration')),
				//参数设置
				'securityparam' => $filter->filter($this->_request->getPost('securityparam')),
				'singledomain' => $filter->filter($this->_request->getPost('singledomain')),
				'trustiptable' => $filter->filter($this->_request->getPost('trustiptable')),
				'staticmx' => $filter->filter($this->_request->getPost('staticmx')),
				'authsetting' => $filter->filter($this->_request->getPost('authsetting')),
				'userintercept' => $filter->filter($this->_request->getPost('userintercept')),
				//联系人管理
				'personlist' => $filter->filter($this->_request->getPost('personlist')),
				'expansion' => $filter->filter($this->_request->getPost('expansion')),
				'contactlist' => $filter->filter($this->_request->getPost('contactlist')),
				'filter' => $filter->filter($this->_request->getPost('filter')),
				'formlist' => $filter->filter($this->_request->getPost('formlist')),
				//邮件内容管理
				'createtempl' => $filter->filter($this->_request->getPost('createtempl')),
				'mytempl' => $filter->filter($this->_request->getPost('mytempl')),
				'preset' => $filter->filter($this->_request->getPost('preset')),
				'mgattach' => $filter->filter($this->_request->getPost('mgattach')),
				'imgattach' => $filter->filter($this->_request->getPost('imgattach')),
				//投递任务管理
				'create' => $filter->filter($this->_request->getPost('create')),
				'addtask' => $filter->filter($this->_request->getPost('addtask')),
				'drafttask' => $filter->filter($this->_request->getPost('drafttask')),
				'listtask' => $filter->filter($this->_request->getPost('listtask')),
				'typetask' => $filter->filter($this->_request->getPost('typetask')),
				//统计分析
				'singletask' => $filter->filter($this->_request->getPost('singletask')),
				'taskclassification' => $filter->filter($this->_request->getPost('taskclassification')),
				'releaseperson' => $filter->filter($this->_request->getPost('releaseperson')),
				'alltaskstatistics' => $filter->filter($this->_request->getPost('alltaskstatistics')),
				'allforwardstatistics' => $filter->filter($this->_request->getPost('allforwardstatistics')),
				//账号管理
				'accountmanage' => $filter->filter($this->_request->getPost('accountmanage')),
			);
			$role_str = "";
			if ($role == "sadmin") {
				$data['access'] = SystemConsole::ParseAccessStr ($acldata);
				$data['role'] = "admin";
				//$data['audit'] = "0";
				$data['parentid'] = 1;
				$role_str = "普通管理员";
			}else if ($role == "stasker") {
				$data['access'] = "1@00110@00000000@000@00000@11111@11111@11111@11010@1";
				$data['role'] = "tasker";
				$data['parentid'] = 2;
				$role_str = "普通任务发布员";
				unset ($data['audit']);
			}
			$isself = $filter->filter($this->_request->getPost('is-self'));
			if ($isself == 'yes') {
				unset ($data['access']);
				unset ($data['role']);
				unset ($data['audit']);
			}
            
			$data['lastmodify'] = date ("Y-m-d H:i:s", time ());
			$data['lastaccess'] = date ("Y-m-d H:i:s", time ());
			if($data['access'] == NULL && $isself != "yes"){
				$this->Smarty->display('login.php');
				exit(); 
			}
			if ($uid != null && $uid != "") {
				$description = "该用户更新 ".$role_str.$data['username']."的信息";
				$description_en = "The user update ".$role_str.$data['username']."'s infos";
				if ($data['password'] == "**********" || $data['password1'] == "**********") {
					unset ($data['password']);
					unset ($data['password1']);
				} else {
					if ($data['password'] == $data['password1']) {
						unset ($data['password1']);
						$data['password'] = MD5 ($data['password']);
					}
				}
				unset($data['username']);
				if ($data['lock'] == "" || $data['lock'] == null) {
					unset($data['lock']);
				}
				if ($data['lock'] == "1"){
					$data['logins'] = 0;
				}
				if ( $data['belongs'] == "" && $data['role'] != "sadmin"){
					$data['belongs'] = "admin";
				}
				unset ($data['lastmodify']);
				unset ($data['belongs']);
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '更新用户', $description, "update user's infos", $description_en, $_SERVER["REMOTE_ADDR"]);
				$this->account->updateAccount ($data, $uid);
			} else {
				$description = "该用户创建：".$role_str.$data['username'];
				$description_en = "The user create new user:".$role_str.$data['username'];
				if ($data['password'] == $data['password1']) {
					unset ($data['password1']);
					$data['password'] = MD5 ($data['password']);
				}
				if ( $data['belongs'] == "" && $data['role'] != 'sadmin'){
					$data['belongs'] = "admin";
				}
				unset ($data['belongs']);
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '创建用户', $description, 'System alarm configuration', $description_en, $_SERVER["REMOTE_ADDR"]);
				$this->account->insertAccount ($data);
			}
		}
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "accountmanage", "setting" );		
	}
	
	public function editaccountAction () {
		$role = $this->getCurrentUserRole(); 
		$uname = $this->getCurrentUser();
		$userid = $this->getCurrentUserID();
		$id = $this->_request->get ( 'id' );
		$item  = $this->account->getAccountInfoByID ($id);
		$is_sadmin = false;
		if ($role == "sadmin") {
			$is_sadmin = true;
		}
		$is_self = "no";
		if ($role == $item['role'] && $uname == $item['username']) {
			$is_self = "yes";
		}
		$item['belongs'] = $this->dbAdapter->fetchOne("select username from mr_accounts where id = ".$item['parentid']);
		$infos = $this->dbAdapter->fetchAll("select username from mr_accounts where role = 'stasker'");
		$this->Smarty->assign ("infos", $infos);
		$this->Smarty->assign ("is_self", $is_self);
		$this->Smarty->assign ("is_sadmin", $is_sadmin);
		$this->Smarty->assign ("sadmin_self", $sadmin_self);
		$this->Smarty->assign ("item", $item);
		$this->Smarty->assign ("editaccount", "yes");
		$this->Smarty->assign ("li_menu", "accountmanage");
		$this->Smarty->display ( 'admincreation.php' );
	}
	
	public function modifypwdAction () {
		$id = $this->_request->get ( 'id' );
		$item = $this->account->getAccountInfoByID ($id);
		
		$this->Smarty->assign ("item", $item);
		$this->Smarty->assign ("li_menu", "accountmanage");
		$this->Smarty->display ( 'modifypwd.php' );
	}
	
	public function updatepwdAction () {
		$role = $this->getCurrentUserRole(); 
		$uname = $this->getCurrentUser();
		$userid = $this->getCurrentUserID();
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$uid = $this->_request->getPost ( 'uid' );
			$password = $filter->filter ( $this->_request->getPost ( 'password' ) );
			$password1 = $filter->filter ( $this->_request->getPost ( 'password1' ) );
			if ($password != "**********" && $password1 != "**********") {
				$data = array();
				$data['password'] = MD5 ($password);
				$this->account->updateAccount ($data, $uid);
			}
		}	
		$description = "该用户修改密码";
		$description_en = "The user modify password";
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '修改密码', $description, 'modify password', $description_en, $_SERVER["REMOTE_ADDR"]);
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "modifypwd", "setting", null, array("id"=>$uid) );
	}
	
	public function delaccountAction () {
		$role = $this->getCurrentUserRole(); 
		$uname = $this->getCurrentUser();
		$userid = $this->getCurrentUserID();
		$id = $this->_request->get ( 'id' );
		$del_dist = "";
		if ($id != null && $id != "") {
			$infos = $this->account->getAccountInfoByID ($id);
			$del_dist = $infos['username'];
			$this->account->delAccount ($id);
			
			//被删除的任务发布员创建的还在执行的循环任务  不再循环发送,找到所有cycle_type > 0
			$cycle_tasks = $this->dbAdapter->fetchAll("select id,cycle_type from `mr_task` where cycle_type > 0 and uid=".$id);
			if(count($cycle_tasks)>0){
				foreach($cycle_tasks as $ctask){
					$ctype = "-".$ctask['cycle_type'];
					$rows = $this->dbAdapter->update('mr_task', array('cycle_type' => $ctype, 'status' => 5), 'id=' . $ctask['id'] );
				}
			}
		}
		$description = "该用户删除普通任务发布员：".$del_dist."的信息";
		$description_en = "The user delete: ".$del_dist."'s infos";
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '删除用户', $description, 'delete user', $description_en, $_SERVER["REMOTE_ADDR"]);
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "accountmanage", "setting" );
	}
	
	public function checkunameAction () {
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$username = $this->_request->getPost ( 'username' );
			if ($username != "" && $username != null) {
				$info = $this->account->checkUserName ($username);
				if ($info['id'] != null && $info['id'] != "") {
					echo "用户名已存在，请重新输入！";
				} else {
					echo "pass";
				}
			}
		}	
	}
	
	public function checkmailAction () {
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$mail = $this->_request->getPost ( 'mail' );
			if ($mail != "" && $mail != null) {
				$info = $this->account->checkUserMail ($mail);
				if ($info['id'] != null && $info['id'] != "") {
					echo "邮箱已存在，请重新输入！";
				} else {
					echo "pass";
				}
			}
		}	
	}
	
	public function accesslogAction () {
		$role = $this->getCurrentUserRole(); 
		$uname = $this->getCurrentUser();
		$userid = $this->getCurrentUserID();
		$num = $this->_request->get ( 'num' );
		if ($num == "" || $num == null) {
			$num = 10;
		}
		$this->Smarty->assign ("anum", $num);
		$sql = "";
		$parameter = "&num=".$num;
		
		$ip = $this->_request->get ( 'ip' );
		if ($ip != "" && $ip != null) {
			$parameter .= "&ip=".$ip;
			$sql = "and ip='".$ip."'";
			$this->Smarty->assign ("ip", $ip);
		}
		$uid = $this->_request->get ( 'userid' );
		if ($uid != "" && $uid != null) {
			if ($uid != "" && $uid != null) {
				if ($sql == "") {
					$sql = "and userid='".$uid."'";
				} else {
					$sql .= " and userid='".$uid."'";
				}
			}
			$parameter .= "&userid=".$uid;
			$cur_user = $this->account->getAccountInfoByID ($uid);
			$this->Smarty->assign ("curuser", $cur_user);	
			$this->Smarty->assign ("userid", $userid);
			$this->Smarty->assign ("uid", $uid);
		}
		$subject = $this->_request->get ( 'subject' );
		$subject=str_replace(array("'"), array(''), $subject);
		if ($subject != "" && $subject != null) {
			if ($sql == "") {
				$sql = "and subject like '%".$subject."%'";
			} else {
				$sql .= " and subject like '%".$subject."%'";
			}
			$parameter .= "&subject=".$subject;
			$this->Smarty->assign ("subject", $subject);
		}
		$description = $this->_request->get ( 'description' );
		$description=str_replace(array("'"), array(''), $description);
		if ($description != "" && $description != null) {
			if ($sql == "") {
				$sql = "and description like '%".$description."%'";
			} else {
				$sql .= " and description like '%".$description."%'";
			}
			$parameter .= "&description=".$description;
			$this->Smarty->assign ("description", $description);
		}
		$starttime = $this->_request->get ( 'starttime' );
		if ($starttime != "" && $starttime != null) {
			if ($sql == "") {
				$sql = "and accesstime>='".$starttime."'";
			} else {
				$sql .= " and accesstime>='".$starttime."'";
			}
			$parameter .= "&starttime=".$starttime;
			$this->Smarty->assign ("starttime", $starttime);
		}
		$endtime = $this->_request->get ( 'endtime' );
		if ($endtime != "" && $endtime != null) {
			if ($sql == "") {
				$sql = "and accesstime<'".$endtime."'";
			} else {
				$sql .= " and accesstime<'".$endtime."'";
			}
			$parameter .= "&endtime=".$endtime;
			$this->Smarty->assign ("endtime", $endtime);
		}
		$total = $this->accesslog->getAllCountByCon($sql);
		$page = new Page ($total, $num, $parameter);
		$infos = $this->accesslog->getAllInfosByCon($sql." order by accesstime desc "."{$page->limit}");
		$this->Smarty->assign ("infos", $infos);
		$this->Smarty->assign ("page", $page->fpage());
		$this->Smarty->assign ("li_menu", "accountmanage");	
		$this->Smarty->display ( 'accesslog.php' );
	}
	
	/**
	 *	sendmail
	 */
	 
	public function sendmailAction () {
		$infos = $this->sendmail->getAllInfos ();
		$actiontype = $this->_request->get ( 'actiontype' );
		$this->Smarty->assign ("actiontype", $actiontype);
		$this->Smarty->assign ("infos", $infos[0]);	
		$this->Smarty->assign ("li_menu", "sendmail");	
		$this->Smarty->display ( 'sendmail.php' );
	} 
	
	public function updatesmtpAction () {
		$role = $this->getCurrentUserRole(); 
		$uname = $this->getCurrentUser();
		$userid = $this->getCurrentUserID();
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$smtpid = $this->_request->getPost ( 'smtpid' );
			$data = array ( 
				'smtpserver' => $filter->filter ( $this->_request->getPost ( 'smtpserver' ) ),
				'smtpserverport' => $filter->filter ( $this->_request->getPost ( 'smtpserverport' ) ),
				'authuser' => $filter->filter ( $this->_request->getPost ( 'authuser' ) ),
				'authpwd' => $filter->filter ( $this->_request->getPost ( 'authpwd' ) )
			);
			$actiontype = "";
			if ($smtpid != "" && $smtpid != null) {
				$description = "该用户更新报告发送配置，SMTP服务器：".$data['smtpserver']."，SMTP服务器端口：".$data['smtpserverport']."，认证用户邮箱：".$data['authuser'];
				$description_en = "The user update Send a report configuration";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '报告发送配置', $description, 'delete user', $description_en, $_SERVER["REMOTE_ADDR"]);
				$actiontype = "update";
				$this->sendmail->updateSMTPServer ($data, $smtpid);
			} else {
				$description = "该用户添加报告发送配置：".$data['smtpserver']."，SMTP服务器端口：".$data['smtpserverport']."，认证用户邮箱：".$data['authuser'];
				$description_en = "The user add Send a report configuration";
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '报告发送配置', $description, 'delete user', $description_en, $_SERVER["REMOTE_ADDR"]);
				$actiontype = "insert";
				$this->sendmail->insertSMTPServer ($data);
			}
		}
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "sendmail", "setting", null, array ("actiontype" => $actiontype) );
	}
	
	/**
	 *	 Published info
	 */
	public function publishedinfoAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$num = $this->_request->get ( 'num' );
		if ($num == "" || $num == null) {
			$num = 10;
		}
		$this->Smarty->assign ("num", $num);
		$curpage = $this->_request->get ( 'page' );
		if ($curpage == "" || $curpage == null) {
			$curpage = 1;
		}
		$this->Smarty->assign ("curpage", $curpage);
		$parameter = "";
		$sql_where = "";
		$title = $this->_request->get ( 'title' );
		$title = str_replace("_", "\_", $title);
		if ($title != "" && $title != null) {
			$sql_where = "and title like '%".$title."%' ";
			$parameter .= "&title=".$title;
			$this->Smarty->assign ("title", stripslashes($title));
		}
		$content = $this->_request->get ( 'content' );
		$content = str_replace("_", "\_", $content);
		if ($content != "" && $content != null) {
			if ($sql_where == "") {
				$sql_where = "and content like '%".$content."%' ";
			} else {
				$sql_where .= "and content like '%".$content."%' ";
			}
			$parameter .= "&content=".$content;
			$this->Smarty->assign ("content", stripslashes($content));
		}
		$creator = $this->_request->get ( 'creator' );
		$creator = str_replace("_", "\_", $creator);
		if ($creator != "" && $creator != null) {
			if ($sql_where == "") {
				$sql_where = "and creator='".$creator."' ";
			} else {
				$sql_where .= "and creator='".$creator."' ";
			}
			$parameter .= "&creator=".$creator;
			$this->Smarty->assign ("creator", stripslashes($creator));
		}
		$createtime1 = $this->_request->get ( 'createtime1' );
		if ($createtime1 != "" && $createtime1 != null) {
			if ($sql_where == "") {
				$sql_where = "and createtime>='".$createtime1."' ";
			} else {
				$sql_where .= "and createtime>='".$createtime1."' ";
			}
			$parameter .= "&createtime1=".$createtime1;
			$this->Smarty->assign ("createtime1", $createtime1);
		}
		$createtime2 = $this->_request->get ( 'createtime2' );
		if ($createtime2 != "" && $createtime2 != null) {
			if ($sql_where == "") {
				$sql_where = "and createtime<='".$createtime2."' ";
			} else {
				$sql_where .= "and createtime<='".$createtime2."' ";
			}
			$parameter .= "&createtime2=".$createtime2;
			$this->Smarty->assign ("createtime2", $createtime2);
		}
		
		$total = $this->publishedinfo->getAllCountByCon($sql_where);
		$page = new Page ($total, $num, $parameter);
		$infos = $this->publishedinfo->getAllInfosByCon($sql_where."order by id desc {$page->limit}");
		
		$mode = $this->_request->get ( 'mode' );
		if( $mode == "search"){
			$userid = $this->getCurrentUserID();
			$uname = $this->getcurrentuser();
			$role = $this->getCurrentUserRole();
			$description='该用户进行查询信息公告的操作';
			$description_en='The operation of the user to query information announcement';
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询操作', $description, 'Query operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		}
		$this->Smarty->assign ("page", $page->fpage());
		$this->Smarty->assign ("infos", $infos);	
		$this->Smarty->assign ("li_menu", "publishedinfo");	
		$this->Smarty->display ( 'publishedinfo.php' );
	}
	
	public function addinfoAction () {
		$infoid = $this->_request->get ( 'infoid' );
		if ($infoid != "" && $infoid != null) {
			$infos = $this->publishedinfo->getInfosByID ($infoid);
		}
		$this->Smarty->assign ("infos", $infos);	
		$this->Smarty->assign ("li_menu", "publishedinfo");	
		$this->Smarty->display ( 'addpublishedinfo.php' );
	}
	
	public function showinfoAction () {
		$infoid = $this->_request->get ( 'infoid' );
		$infos = array();
		if ($infoid != "" && $infoid != null) {
			$infos = $this->publishedinfo->getInfosByID ($infoid);
		}
		echo json_encode($infos);
	}
	
	public function updateinfoAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$insert = false;
		$id = "";
		$title = "";
		$content = "";
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$title = $filter->filter ( $this->_request->getPost ( 'title' ));
			$content = $this->_request->getPost ( 'content' );
			$id = $filter->filter ( $this->_request->getPost ( 'infoid' ));
			if ($id == "" || $id == null) {
				$insert = true;
			}
		}
        
		$createtime = date("Y-m-d H:i:s", time());
		$data = array();
		if ($insert) {
			$data['title'] = $title;
			$data['content'] = $content;
			$data['createtime'] = $createtime;
			$data['creator'] = $uname;
			$data['role'] = $role;
			$this->publishedinfo->insertInfo ($data);
			$description = "该用户新建信息公告，标题为： ".$data['title'];
			$description_en = "The user new information announcement,the title name is: ".$data['title'];
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '新建信息公告', $description, 'new information announcement', $description_en, $_SERVER["REMOTE_ADDR"]);
		} else {
			$data['title'] = $title;
			$data['content'] = $content;
			$this->publishedinfo->updateInfo ($data, $id);
			$description = "该用户修改信息公告，标题为： ".$data['title'];
			$description_en = "The user update information announcement,the title name is: ".$data['title'];
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '修改信息公告', $description, 'update information announcement', $description_en, $_SERVER["REMOTE_ADDR"]);
		}
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "publishedinfo", "setting" );
	}
	
	public function delinfoAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$titles = "";
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$infolist = $filter->filter ( $this->_request->getPost ( 'infolist' ));
		}
		$items = explode("@", $infolist);
		foreach ($items as $item) {
			if ($item != "") {
				$title = $this->dbAdapter->fetchOne("SELECT title from mr_publishedinfo where id = ".$item);
				$titles .= $title.",";
				$this->publishedinfo->deleteInfo ($item);
			}
		}
		$titles =trim($titles,",");
		$description = "该用户删除信息公告，标题为： ".$titles;
		$description_en = "The user delete information announcement,the title name is: ".$titles;
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '删除信息公告', $description, 'delete information announcement', $description_en, $_SERVER["REMOTE_ADDR"]);
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "publishedinfo", "setting" );
	}
	
}
?>