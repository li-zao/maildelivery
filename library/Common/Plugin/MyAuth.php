<?php
require_once	'Zend/Controller/Plugin/Abstract.php';

/**
 * Implement the privilege controller.
 */
class Common_Plugin_MyAuth	extends	Zend_Controller_Plugin_Abstract 
{
	/**
	 * An instance of Zend_Auth
	 * @var Zend_Auth
	 */
	private $_auth;
	
	/**
	 * An instance of Custom_Acl
	 * @var Custom_Acl
	 */
	private $_acl;
	
	/**
	 * Redirect to a new controller when the user has a invalid indentity.
	 * @var array
	 */
	private $_noauth=array(	'module'=>'default',
							'controller'=>'index',
							'action'=>'login');
	/**
	 * Redirect to 'error' controller when the user has a vailid identity 
	 * but no privileges
	 * @var array
	 */
	private $_nopriv=array(	'module'=>'default',
							'controller'=>'error',
							'action'=>'nopriv');
	
	private $_locked=array(	'module'=>'default',
							'controller'=>'error',
							'action'=>'lock');
	
	private $_iprestrict=array(	'module'=>'default',
							'controller'=>'error',
							'action'=>'iprestrict');
							
	private $_licenserestrict=array(	'module'=>'default',
							'controller'=>'error',
							'action'=>'licenserestrict');
	
	/**
	 * Constructor.
	 * @return void
	 */
	
	public function	__construct($auth,$acl)
	{
		$this->_auth = $auth;
		$this->_acl = $acl;
	}
	
	/**
	 * Track user privileges.
	 * @param Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function	preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$role = 'guest';
		
		$module = $request->module;
		$controller = $request->controller;
		$action = $request->action;
		$resource = "$module:$controller";
		
		$auth = Zend_Auth::getInstance ();
		$access_str = "";
		
		$cur_lang = Zend_Registry::get ('default_lang');
		$plang = $request->get ( 'lang' );
		if ($plang == "zh" || $plang == "en") {
			setcookie("locale", $plang, 0, '/');
		}
		
		if ($plang == "zh" || $plang == "en") {
			$cur_lang = $plang;
		} else if (isset($_COOKIE['locale'])) {
			$cur_lang = $_COOKIE['locale'];
		} else {
			if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
				if (strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'en') === 0) {
					$cur_lang = 'en';
				} else if (strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'zh') === 0) {
					$cur_lang = 'zh';
				}		
			}
		}
		Zend_Registry::set ( 'cur_locale', $cur_lang );
		if ($auth->hasIdentity ()) {
			// Identity exists; get it
			$data = $auth->getStorage ()->read ();
			$role = $auth->getStorage ()->read ()->role;
			$access_str = $auth->getStorage ()->read ()->access;
			$lock = $auth->getStorage ()->read ()->lock;
			if ($lock == '0') {
				$module = $this->_locked['module'];
				$controller = $this->_locked['controller'];
				$action = $this->_locked['action'];
				$request->setModuleName($module);
				$request->setControllerName($controller);
				$request->setActionName($action);
				return;
			}
			$trustip = $auth->getStorage ()->read ()->trustip;
			$licenseinfo = $auth->getStorage ()->read ()->licenseinfo;
			if ($trustip != "" && $trustip != null) {
				$ip_access = false;
				$ips = preg_split ( '/[\r\n]/', $trustip );
				foreach ($ips as $ip) {
					if ($ip != "" && $ip != null) {
						if ($_SERVER["REMOTE_ADDR"] == $ip) {
							$ip_access = true;
							break;
						}
					}
				}
				if (!$ip_access) {
					$module = $this->_iprestrict['module'];
					$controller = $this->_iprestrict['controller'];
					$action = $this->_iprestrict['action'];
					$request->setModuleName($module);
					$request->setControllerName($controller);
					$request->setActionName($action);
					return;
				}
			}
		}
		$access = new Access ($access_str,$role);
		
		if (!$this->_acl->has($resource)){
			$resource = null;
		}
		
		if (!$access->isAllowed($role,$resource,$action)) {
			if ($role == 'guest') {
				$module = $this->_noauth['module'];
				$controller = $this->_noauth['controller'];
				$action = $this->_noauth['action'];
			} else {
				$module = $this->_nopriv['module'];
				$controller = $this->_nopriv['controller'];
				$action = $this->_nopriv['action'];
			}
		}
		$request->setModuleName($module);
		$request->setControllerName($controller);
		$request->setActionName($action);
	}
    
}