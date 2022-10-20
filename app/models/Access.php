<?php
class Access {
	public $access_str;
	public $role;
	//首页
	public $firstpage;
	//系统监控
	public $systemmonitor;
	public $mailstatistics;
	public $mailqueue;
	public $searchlogs;
	public $denyaccess;
	//系统设置
	public $consolesetting;
	public $sendmail;
	public $alertsetting;
	public $sysclocksetting;
	public $workingreport;
	public $license;
	public $resetsetting;
	public $publishedinfo;
	//网络设置
	public $networksetting;
	public $networktool;
	public $snmpconfiguration;
	//参数设置
	public $securityparam;
	public $singledomain; 
	public $trustiptable;
	public $staticmx;
	//public $authsetting;
	public $userintercept;
	//联系人管理
	public $personlist;		
	public $expansion;	
	public $contactlist;
	public $filter;	
	public $formlist;	
	public $subscribe;	
	//邮件内容管理
	public $createtempl;
	public $mytempl;				
	public $preset;					
	public $mgattach;				
	public $images;
	//投递任务管理
	public $create;					
	public $addtask;				
	public $drafttask;				
	public $listtask;				
	public $typetask;				
	//统计分析
	public $singletask;
	public $taskclassification;
	public $releaseperson;
	public $alltaskstatistics;
	public $allforwardstatistics;
	//admin manage
	public $admincreation;
	
	public function __construct($access_str) {
		$this->init($access_str);
	}
	
	public function init ($access_str) {		
		$this->access_str = $access_str;
		//首页
		$this->firstpage = false;
		//系统监控
		$this->systemmonitor = false;
		$this->mailstatistics = false;
		$this->mailqueue = false;
		$this->searchlogs = false;
		$this->denyaccess = false;
		//系统设置
		$this->consolesetting = false;
		$this->sendmail = false;
		$this->alertsetting = false;
		$this->sysclocksetting = false;
		$this->workingreport = false;
		$this->license = false;
		$this->resetsetting = false;
		$this->publishedinfo = false;
		//网络设置
		$this->networksetting = false;
		$this->networktool = false;
		$this->snmpconfiguration = false;
		//参数设置
		$this->securityparam = false;
		$this->singledomain = false;   
		$this->trustiptable = false;
		$this->staticmx = false;
		//$this->authsetting = false;
		$this->userintercept = false;
		//联系人管理
		$this->personlist = false;		
		$this->expansion = false;	
		$this->contactlist = false;
		$this->filter = false;	
		$this->formlist = false;
		$this->subscribe = false;
		//邮件内容管理
		$this->createtempl = false;
		$this->mytempl = false;				
		$this->preset = false;					
		$this->mgattach = false;				
		$this->images = false;				
		//投递任务管理
		$this->create = false;					
		$this->addtask = false;				
		$this->drafttask = false;				
		$this->listtask = false;				
		$this->typetask = false;				
		//统计分析
		$this->singletask = false;
		$this->taskclassification = false;
		$this->releaseperson = false;
		$this->alltaskstatistics = false;
		$this->allforwardstatistics = false;
		//admin manage
		$this->admincreation = false;
	
		if ($this->access_str == '*') {
			//首页
			$this->firstpage = true;
			//系统监控
			$this->systemmonitor = true;
			$this->mailstatistics = true;
			$this->mailqueue = true;
			$this->searchlogs = true;
			$this->denyaccess = true;
			//系统设置
			$this->consolesetting = true;
			$this->sendmail = true;
			$this->alertsetting = true;
			$this->sysclocksetting = true;
			$this->workingreport = true;
			$this->license = true;
			$this->resetsetting = true;
			$this->publishedinfo = true;
			//网络设置
			$this->networksetting = true;
			$this->networktool = true;
			$this->snmpconfiguration = true;
			//参数设置
			$this->securityparam = true;
			$this->singledomain = true;    
			$this->trustiptable = true;
			$this->staticmx = true;
			//$this->authsetting = true;
			$this->userintercept = true;
			//联系人管理
			$this->personlist = true;		
			$this->expansion = true;	
			$this->contactlist = true;
			$this->filter = true;	
			$this->formlist = true;	
			$this->subscribe = true;	
			//邮件内容管理
			$this->createtempl = true;
			$this->mytempl = true;				
			$this->preset = true;					
			$this->mgattach = true;				
			$this->images = true;				
			//投递任务管理
			$this->create = true;					
			$this->addtask = true;				
			$this->drafttask = true;				
			$this->listtask = true;				
			$this->typetask = true;				
			//统计分析
			$this->singletask = true;
			$this->taskclassification = true;
			$this->releaseperson = true;
			$this->alltaskstatistics = true;
			$this->allforwardstatistics = true;
			//admin manage
			$this->admincreation = true;
		}
		
		if ($this->access_str != "" && $this->access_str != null) {
			$access = $this->access_str;
			$group = preg_split ( '/[@]/', $access );
			//首页
			if (count($group) > 0) {
				$temp = trim ($group[0]);
				if (strlen($temp) > 0) {
					if ($temp{0} == "1") {
		 				$this->firstpage  = true;
		 			}
				}
			}
			//系统监控
			if (count($group) > 1) {
				$temp = trim ($group[1]);
				if (strlen($temp) > 0) {
					if ($temp{0} == "1") {
		 				$this->systemmonitor  = true;
		 			}
		 			if ($temp{1} == "1") {
		 				$this->mailstatistics  = true;
		 			}
					if ($temp{2} == "1") {
		 				$this->mailqueue  = true;
		 			}
					if ($temp{3} == "1") {
		 				$this->searchlogs = true;
		 			}
					if ($temp{4} == "1") {
		 				$this->denyaccess = true;
		 			}
				}
			}
			//系统设置
			if (count($group) > 2) {
				$temp = trim ($group[2]);
				if (strlen($temp) > 0) {
					if ($temp{0} == "1") {
		 				$this->consolesetting = true;
		 			}
					if ($temp{1} == "1") {
		 				$this->sendmail = true;
		 			}
					if ($temp{2} == "1") {
		 				$this->alertsetting = true;
		 			}
					if ($temp{3} == "1") {
		 				$this->sysclocksetting = true;
		 			}
					if ($temp{4} == "1") {
		 				$this->workingreport = true;
		 			}
					if ($temp{5} == "1") {
		 				$this->license = true;
		 			}
					if ($temp{6} == "1") {
		 				$this->resetsetting = true;
		 			}
					if ($temp{7} == "1") {
		 				$this->publishedinfo = true;
		 			}
				}
			}
			//网络设置
			if (count($group) > 3) {
				$temp = trim ($group[3]);
				if (strlen($temp) > 0) {
					if ($temp{0} == "1") {
		 				$this->networksetting = true;
		 			}
					if ($temp{1} == "1") {
		 				$this->networktool = true;
		 			}
					if ($temp{2} == "1") {
		 				$this->snmpconfiguration = true;
		 			}
				}
			}
			//参数设置
			if (count($group) > 4) {
				$temp = trim ($group[4]);
				if (strlen($temp) > 0) {
					if ($temp{0} == "1") {
		 				$this->securityparam = true;
		 			}
					if ($temp{1} == "1") {
		 				$this->singledomain = true;
		 			}
					if ($temp{2} == "1") {
		 				$this->trustiptable = true;
		 			}
					if ($temp{3} == "1") {
		 				$this->staticmx = true;
		 			}
					//if ($temp{4} == "1") {
		 			//	$this->authsetting = true;
		 			//}
					if ($temp{4} == "1") {
		 				$this->userintercept = true;
		 			}
				}
			}
			//联系人管理
			if (count($group) > 5) {
				$temp = trim ($group[5]);
				if (strlen($temp) > 0) {
					if ($temp{0} == "1") {
		 				$this->personlist = true;
		 			}
					if ($temp{1} == "1") {
		 				$this->expansion = true;
		 			}
					if ($temp{2} == "1") {
		 				$this->contactlist = true;
		 			}
					if ($temp{3} == "1") {
		 				$this->filter = true;
		 			}
					if ($temp{4} == "1") {
		 				$this->formlist = true;
						$this->subscribe = true;
		 			}
				}
			}
			//邮件内容管理
			if (count($group) > 6) {
				$temp = trim ($group[6]);
				if (strlen($temp) > 0) {
					if ($temp{0} == "1") {
		 				$this->createtempl = true;
		 			}
					if ($temp{1} == "1") {
		 				$this->mytempl = true;
		 			}
					if ($temp{2} == "1") {
		 				$this->preset = true;
		 			}
					if ($temp{3} == "1") {
		 				$this->mgattach = true;
		 			}
		 			if ($temp{4} == "1") {
		 				$this->images = true;
		 			}
				}
			}
			//投递任务管理
			if (count($group) > 7) {
				$temp = trim ($group[7]);
				if (strlen($temp) > 0) {
					if ($temp{0} == "1") {
		 				$this->create = true;
		 			}
					if ($temp{1} == "1") {
		 				$this->addtask = true;
		 			}
					if ($temp{2} == "1") {
		 				$this->drafttask = true;
		 			}
					if ($temp{3} == "1") {
		 				$this->listtask = true;
		 			}
					if ($temp{4} == "1") {
		 				$this->typetask = true;
		 			}
				}
			}
			//统计分析
			if (count($group) > 8) {
				$temp = trim ($group[8]);
				if (strlen($temp) > 0) {
					if ($temp{0} == "1") {
		 				$this->singletask = true;
		 			}
					if ($temp{1} == "1") {
		 				$this->taskclassification = true;
		 			}
					if ($temp{2} == "1") {
		 				$this->releaseperson = true;
		 			}
					if ($temp{3} == "1") {
		 				$this->alltaskstatistics = true;
		 			}
					if ($temp{4} == "1") {
		 				$this->allforwardstatistics = true;
		 			}
				}
			}
			// admin manage
			if (count($group) > 9) {
				$temp = trim ($group[9]);
				if (strlen($temp) > 0) {
					if ($temp{0} == "1") {
		 				$this->admincreation = true;
		 			}
				}
			}
		}
	}
	//首页
	public function getFirstPageAccess(){
		return $this->firstpage;
	}
	//系统监控
	public function getSystemMonitorAccess(){
		return $this->systemmonitor;
	}
	
	public function getMailStatisticsAccess(){
		return $this->mailstatistics;
	}
	
	public function getMailQueueAccess(){
		return $this->mailqueue;
	}
	
	public function getSearchLogsAccess(){
		return $this->searchlogs;	
	}
	
	public function getDenyAccessAccess(){
		return $this->denyaccess;	
	}
	
	//系统设置
	public function getConsoleSetAccess () {
		return $this->consolesetting;
	}
	
	public function getSendMailAccess () {
		return $this->sendmail;
	}
	
	public function getAlertSetAccess () {
		return $this->alertsetting;
	}
	
	public function getSysClockSetAccess () {
		return $this->sysclocksetting;
	}
	
	public function getWorkingReportAccess () {
		return $this->workingreport;
	}
	
	public function getLicenseAccess () {
		return $this->license;
	}
	
	public function getResetAccess () {
		return $this->resetsetting;
	}
	
	public function getPublishedInfoAccess () {
		return $this->publishedinfo;
	}
	//网络设置
	public function getNetworkSetAccess () {
		return $this->networksetting;
	}
	
	public function getNetworkToolAccess () {
		return $this->networktool;
	}
	
	public function getSNMPConfigAccess () {
		return $this->snmpconfiguration;
	}
	//参数设置
	public function getSecurityParamAccess () {
		return $this->securityparam;
	}
	
	public function getSingleDomainAccess () {
		return $this->singledomain;
	}
	
	public function getTrustipTableAccess () {
		return $this->trustiptable;
	}
	
	public function getStaticMxAccess () {
		return $this->staticmx;
	}
	
	public function getAuthSettingAccess () {
		return $this->authsetting;
	}
	
	public function getUserInterceptAccess () {
		return $this->userintercept;
	}
	//联系人管理
	public function getContactListAccess(){
		return $this->contactlist;	
	}
	
	public function getExpansionAccess(){
		return $this->expansion;
	}
	
	public function getPersonListAccess(){
		return $this->personlist;
	}
	
	public function getFilterAccess(){
		return $this->filter;
	}
	
	public function getFormListAccess(){
		return $this->formlist;
	}
	
	//邮件内容管理
	public function getCreateTemplAccess(){
		return $this->createtempl;
	}

	public function getPresetTemplAccess(){
		return $this->preset;
	}

	public function getMyTemplAccess(){
		return $this->mytempl;
	}
	
	public function getMgAttachAccess(){
		return $this->mgattach;
	}

	public function getMgImagesAccess(){
		return $this->images;
	}

	//投递任务管理
 	public function getCreateTaskAccess(){
 		return false;
		// return $this->create;
	} 
	
 	public function getAddTaskAccess(){
		return $this->addtask;
	}  

	public function getDraftTaskAccess(){
		return $this->drafttask;
	} 

 	public function getListTaskAccess(){
		return $this->listtask;
	} 

 	public function getTypeTaskAccess(){
		return $this->typetask;
	} 
	
	//统计分析
	public function getSingleTaskAccess () {
		return $this->singletask;
	}	

	public function getTaskClassificationAccess () {
		return $this->taskclassification;
	}	

	public function getReleasePersonAccess () {
		return $this->releaseperson;
	}

	public function getAllTasksAccess () {
		return $this->alltaskstatistics;
	}

	public function getAllForwardAccess () {
		return $this->allforwardstatistics;
	}	
	//admin manage
	public function getAdminCreationAccess () {
		return $this->admincreation;
	}
	
	public function isAllowed ($role, $resource, $action) {
		if ($role == "superadmin") {
			return true;
		}
		if ($resource == 'default:index') {
			return true;
		}
		if ($resource == 'default:systemmonitor') {
			// system monitor
			if ($action == "systemmonitor" || $action == "getsysinfo") {
				return $this->getSystemMonitorAccess();
			}
			// mail statistics
			if ($action == "mailstatistics" || $action == "getdiffstats") {
				if ($role == "guest") {
					return true;
				} else {
					return $this->getMailStatisticsAccess();
				}
			}
			// mail queue
			if ($action == "mailqueue" || $action == "getsmtptasklog") {
				return $this->getMailQueueAccess();
			}
			// search logs
			if ($action == "searchlogs") {
				return $this->getSearchLogsAccess();
			}
			// deny access
			if ($action == "denyaccess") {
				return $this->getDenyAccessAccess();
			}
			// operateeml
			if ( $action == "operateeml") {
				return ($role == "admin" || $this->getMailQueueAccess());
			}
			return false;
		}
		if ($action == "modifypwd" || $action == "updatepwd") {
			return true;
		}
		if ($resource == 'default:setting') {
			// system setting
			if ($action == "default") {
				return true;
			}
			if ($action == "consolesetting" || $action == "updateconsole") {
				if ($role == "tasker") {
					return false;
				} else {
					return $this->getConsoleSetAccess ();
				}
			}
			if ($action == "sendmail" || $action == "updatesmtp") {
				if ($role == "tasker") {
					return false;
				} else {
					return $this->getSendMailAccess ();
				}
			}
			if ($action == "alertsetting" || $action == "addalertsetting") {
				if ($role == "tasker") {
					return false;
				} else {
					return $this->getAlertSetAccess ();
				}
			}
			if ($action == "sysclocksetting" || $action == "refreshsysclock" || $action == "customtime" || $action == "updatentp") {
				if ($role == "tasker") {
					return false;
				} else {
					return $this->getSysClockSetAccess ();
				}
			}
			if ($action == "workingreport" || $action == "addworkingreport" || $action == "getworkingreportinfo" || $action == "delworkingreport") {
				if ($role == "tasker") {
					return false;
				} else {
					return $this->getWorkingReportAccess ();
				}
			}
			if ($action == "uploadlicese" || $action == "license" || $action == "downloadmdapkey") {//no completely
				if ($role == "tasker") {
					return false;
				} else {
					return $this->getLicenseAccess ();
				}
			}
			if ($action == "resetsetting" || $action == "checkroutingip") {
				if ($role == "tasker") {
					return false;
				} else {
					return $this->getResetAccess ();
				}
			}
			if ($action == "showinfo" || $action == "delinfo" || $action == "publishedinfo" || $action == "addinfo" || $action == "updateinfo") {
				return true;
			}
			// network setting
			if ($action == "networksetting" || $action == "updatenetworksetting") {
				if ($role == "tasker") {
					return false;
				} else {
					return $this->getNetworkSetAccess ();
				}
			}
			if ($action == "networktool" || $action == "pingtest" || $action == "tranceroute" || $action == "telnettest") {
				if ($role == "tasker") {
					return false;
				} else {
					return $this->getNetworkToolAccess ();
				}
			}
			if ($action == "snmpconfiguration" || $action == "addsnmp") {
				if ($role == "tasker") {
					return false;
				} else {
					return $this->getSNMPConfigAccess ();
				}
			}
			// security setting
			if ($action == "securityparam" || $action == "addsecurityparam") {
				return $this->getSecurityParamAccess();
			}
			// single domain
			if ($action == "checksingledomain" || $action == "singledomain" || $action == "addsingledomain" || $action == "getsingledomain" || $action == "delsingledomain") {
				return $this->getSingleDomainAccess();
			}
			// trustiptable
			if ($action == "checktrustip" || $action == "trustiptable" || $action == "changebelong" || $action == "addtrustiptable" || $action == "gettrustiptableinfo" || $action == "deltrustiptable") {
				return $this->getTrustipTableAccess();
			}
			//  static mx
			if ($action == "checkstaticmx" || $action == "staticmx" || $action == "addstaticmx" || $action == "getstaticmxinfo" || $action == "delstaticmxtable") {
				return $this->getStaticMxAccess();
			}
			//  Authentication
			if ($action == "authsetting" || $action == "addauthsetting" || $action == "getauthsettinginfo" || $action == "delauthsetting") {
				return $this->getAuthSettingAccess();
			}
			//  UserIntercept
			if ($action == "userintercept" || $action == "addinterceptuser" || $action == "getinterceptuser" || $action == "delinterceptuser" || $action == "checkinterceptuser") {
				return $this->getUserInterceptAccess();
			}
			//  account manage
			if ($action == "accesslog" || $action == "checkuname" || $action == "checkmail" || $action == "addadmin" || $action == "accountmanage" || $action == "delaccount" || $action == "editaccount" || $action == "admincreation") {
				return true;
			}
			return false;
		}
		
		if ($resource == 'default:contact') {
			/*if ($role != 'admin' && $role != 'sadmin') {
				return false;
			}

			return true;*/
			if($action == "contactlist"){
				return $this->getContactListAccess();
			}
			if($action == "expansion"){
				if ($role == 'stasker') {
					return $this->getExpansionAccess();
				} else {
					return false;
				}
			}
			if($action == "personlist"){
				return $this->getPersonListAccess();
			}
			if($action == "filter"){
				return $this->getFilterAccess();
			}
			if($action == "subscribe"){
				return $this->getFormListAccess();
			}
			if($action == "contactlist" || $action == "addgroup" || $action == "edit" || $action == "delete" || $action == "checkmail" || $action == "illegallist"){
				return true;
			}
			if($action == "combine" || $action == "personlist" || $action == "addperson" || $action == "getgroups" || $action == "updateperson" || $action == "deleteperson"){
				return true;
			}
			if($action == "expansion" || $action == "addexpansion" || $action == "editexpansion" || $action == "deletexpansion" || $action == "combinegroup" || $action == "ajaxgroup" || $action == "ajaxextension"){
				return true;
			}
			if($action == "getinfo" || $action == "import" || $action == "export" || $action == "exportview" || $action == "subscribe" || $action == "createform"){
				return true;
			}
			if($action == "docreateform" || $action == "ajaxcode" || $action == "formlist" || $action == "updatestatus" || $action == "preview" || $action == "ajaxmailbox"){
				return true;
			}
			if($action == "adduser" || $action == "delform" || $action == "edittemplate" || $action == "doedittemplate" || $action == "editform" || $action == "doeditform"){
				return true;
			}
			if($action == "ajaxform" || $action == "unsubscribe" || $action == "filter" || $action == "addfilter" || $action == "doaddfilter" || $action == "delfilter" || $action == "ajaxdelall"){
				return true;
			}
			if($action == "ajaxfiltername" || $action == "getexpansion" || $action == "addallperson" || $action == "ajaxdelallgroups" || $action == "ajaxmailsendper"){
				return true;
			}
			if($action == "ajaxdelallfilter"  || $action == "selectalled"){
				return true;
			}
		}
		if ($resource == 'default:createtem') {
			if($action == "addtpls" || $action == "doaddtpls"){
				return true;
			}	
			if($action == "checkevent" || $action == "calendarjson" || $action == "addcalendar" || $action == "calendarevent" || $action == "gettotalmail" ||$action == "firstpage" || $action == "delattach" || $action == "uploadattchs" || $action == "delimages"){
				return $this->getFirstPageAccess();
			}
			if($action == "mgattach"){
				return $this->getMgAttachAccess();
			}
			if($action == "mdimages"){
				return $this->getMgImagesAccess();
			}
			return false;
		}
		if ($resource == 'default:list') {
			if ($role != 'admin' && $role != 'sadmin') {
				return false;
			}
			return true;
		}
		if ($resource == 'default:status') {
			if ($role != 'admin' && $role != 'sadmin') {
				return false;
			}
			return true;
		}
		
		if ($resource == 'default:statistics') {
			if($action == "singletask"){
				return $this->getSingleTaskAccess();
			}
			if($action == "taskclassification"){
				return $this->getTaskClassificationAccess();
			}	
			if($action == "releaseperson"){
				return $this->getReleasePersonAccess();
			}
			if($action == "alltaskstatistics"){
				return $this->getAllTasksAccess();
			}	
			if($action == "allforwardstatistics"){
				return $this->getAllForwardAccess();
			}
			if($action == 'reportimg' || $action == "matchtask"){
				return true;
			}
			return false;
		}
		
		if ($resource == 'default:task') {
			/* if ($role != 'admin' && $role != 'sadmin') {
				return false;
			}
			return true; */
			if($action == "create"){
				return $this->getCreateTaskAccess();
			}
			if($action == "addtask" ){
				return $this->getAddTaskAccess();
			}
			if($action == "drafttask"){
				return $this->getDraftTaskAccess();
			}	
			if($action == "listtask"){
				return $this->getListTaskAccess();
			}	
			if($action == "typetask"){
				return $this->getTypeTaskAccess();
			}
			
			if($action == "settask" || $action == "designtask" || $action == "confirmtask" || $action == "inserttask" || $action == "quickctask" || $action == "edittask" || $action == "viewtask" || $action == "copytask" || $action == "subtask" || $action == "checkforward" || $action == "taskdata" || $action == "tasksmtp" || $action == 'checkbuffer' || $action == 'ajaxgetdomainname' || $action == 'selectfilter' || $action == 'uploadexcelgroup' || $action == 'uploadaddr' || $action == 'searchgroups'){
				return true;
			}
			
			if($action == "ajaxgroupcount" || $action == "stop" || $action == "open" || $action == "deltask" || $action == "deltaskcat" || $action == "checktask"  || $action == "selecttpl"  || $action == "tplcont"  || $action == "ajaxgroup"  || $action == "ajaxgetinfo"  || $action == "getcount"  || $action == "uploadattchment"  || $action == "delattch"  || $action == "delmodattch"  || $action == "delfiltertask" || $action == "audittask" || $action == "auditinfo" || $action == "searchattach"){
				return true;
			}
			return false;
		}
		if ($resource == 'default:templet') {
			if($action == "preset"){
				return $this->getPresetTemplAccess();
			}
			if($action == "mytempl" ){
				return $this->getMyTemplAccess();
			}
			if($action == "createtempl"){
				return $this->getCreateTemplAccess();
			}
			
			if($action == "addvocation" || $action == "docreates" || $action == "editvocation" || $action == "doupdatevo" || $action == "deletevo" || $action == "checktpl" || $action == "editupload" || $action == 'checkvo'){
				return true;
			}
			if($action == "searchvo" || $action == "ajaxweb" || $action == "uploadtem" || $action == "preview" || $action == "previewone" || $action == "tplview" || $action == "deltpls"){
				return true;
			}
			return false;
		}
		return false;
	}
}
?>