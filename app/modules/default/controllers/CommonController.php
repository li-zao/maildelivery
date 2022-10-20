<?php
class CommonController extends Zend_Controller_Action {
	public $cache;
	public $access;
	function init() {
		$counter = new CounterModel();
		$curtotal = $counter->fetchOSTotal();
		$curtotal = intval($curtotal);

		$infos = SystemConsole::GetLicenseInfo();
		$curdaytotal = $infos['usernumber'];

		if(!$curdaytotal){
			$curdaytotal = 0;
		}

		$this->registry = Zend_Registry::getInstance ();
		$this->Smarty = $this->registry['Smarty'];
		$this->dbAdapter = $this->registry ['dbAdapter'];

		if ($curdaytotal == '0') {
			$this->Smarty->assign ("ratio", '0.00');
			$this->Smarty->assign ("curdaytotal",0);
			$this->Smarty->assign ("curtotal", 0);
		} else {
			$this->Smarty->assign ("ratio", number_format(($curtotal/$curdaytotal)*100, 2, '.', ''));
			$this->Smarty->assign ("curdaytotal", $curdaytotal);
			$this->Smarty->assign ("curtotal", $curtotal);
		}
		
		// Cahe configuration
		$frontendOptions = array ('lifeTime' => 120, 'automatic_serialization' => true );
		
		$backendOptions = array ('cache_dir' => "data/cache/" );
		
		$this->cache = Zend_Cache::factory ( 'Core', 'File', $frontendOptions, $backendOptions );		
		
		$auth = Zend_Auth::getInstance ();
		$accessStr = "";
		$role = "admin";
		$group = "";
		$ctype = 8;
		if ($auth->hasIdentity ()) {
			// Identity exists; get it
			$this->Smarty->assign ("uid", $auth->getStorage ()->read ()->id);
			$this->Smarty->assign ("uname", $auth->getStorage ()->read ()->username);
			$accessStr = $auth->getStorage ()->read ()->access;
			$role = $auth->getStorage ()->read ()->role;
		}
		$this->access = new Access($accessStr);
		$this->Smarty->assign ("role", $role);
		$this->Smarty->assign ("access", $this->access);
	}
	
	public function getCurrentUser() {
		$auth = Zend_Auth::getInstance ();
		$uname = '';
		if ($auth->hasIdentity ()) {
			// Identity exists; get it
			$uname = $auth->getStorage ()->read ()->username;
		}
		return $uname;
	}
	
	public function getCurrentUserID() {
		$auth = Zend_Auth::getInstance ();
		$id = 1;
		if ($auth->hasIdentity ()) {
			// Identity exists; get it
			$id = $auth->getStorage ()->read ()->id;
		}
		return $id;
	}
	
	public function getCurrentUserRole() {
		$auth = Zend_Auth::getInstance ();
		$role = 'admin';
		if ($auth->hasIdentity ()) {
			// Identity exists; get it
			$role = $auth->getStorage ()->read ()->role;
		}
		return $role;
	}
	
	public function getCurrentUserType() {
		$auth = Zend_Auth::getInstance ();
		$usertype = '1';
		if ($auth->hasIdentity ()) {
			// Identity exists; get it
			$usertype = $auth->getStorage ()->read ()->usertype;
		}
		return $usertype;
	}
}
?>