<?php
require ('CommonController.php');
class IndexController extends CommonController {

	public $account;
	
	function init() {
		parent::init ();
		$this->account = new Account();
	}
	
	/**
	 *
	 */
	public function indexAction() {
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "login", "index" );
	}
	
	/**
	 *
	 */
	public function loginAction() {
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$username = trim(strtolower ($filter->filter ( $this->_request->getPost ( 'Username' ) ) ) );
			$orgin_password = $filter->filter ( $this->_request->getPost ( 'Password' ) );
			$password = md5 ( $orgin_password );
			$captache = $filter->filter ( $this->_request->getPost ( 'captcha' ) );
			if ($username == "" || $orgin_password == "") {
				$this->Smarty->assign ("error", "账号、密码不能为空！");
				$this->Smarty->display('login.php');
				exit();
			}
			
			$user_info = $this->account->getLoginsByName ($username);
			if (empty($user_info) || (!empty($user_info) && ($user_info['userrole'] != 'stasker' && $user_info['userrole'] != 'tasker'))) {
				$this->Smarty->assign ("error", "账号或密码错误，请重新输入！");
				$this->Smarty->display('login.php');
				exit ();
			}

			if ($_SESSION['randval'] != strtoupper($captache)) {  
				$this->Smarty->assign('username',$username);
				$this->Smarty->assign('password',$orgin_password);
				$this->Smarty->assign ("error", "验证码错误，请重新输入！");
				$this->Smarty->display('login.php');
				exit(); 
			}
			
			$whether_lock = $this->account->checkAccountName($username, 'checklock');
			if (empty($whether_lock)) {
				$this->Smarty->assign ("error", "该帐号已被锁定或异常，请联系管理员！");
				$this->Smarty->display('login.php');
				exit ();
			}
			
			$dbAdapter = Zend_Registry::get ( 'dbAdapter' );
			$authAdapter = new Zend_Auth_Adapter_DbTable ( $dbAdapter );
			$authAdapter->setTableName ( Zend_Registry::get ( 'dbprefix' ) . 'accounts' );
			$authAdapter->setIdentityColumn ( 'username' );
			$authAdapter->setCredentialColumn ( 'password' );
			$authAdapter->setIdentity ( $username );
			$authAdapter->setCredential ( $password );
			
			$auth = Zend_Auth::getInstance ();
			$result = $auth->authenticate ( $authAdapter );
			if ($result->isValid ()) {
				$data = $authAdapter->getResultRowObject ( null, 'password' );
				//write into session
				$auth->getStorage ()->write ( $data );
				$cur_time = date( "Y-m-d H:i:s", time() );
				$info = $this->account->getLoginsByName ($data->username);
				$desc = '用户登录于'.$cur_time.'成功';
				$desc_en = 'User logined at '.$cur_time;
				$this->account->updateLastAccess ($data->id, $cur_time);
				BehaviorTrack::addBehaviorLog($data->username, $data->userrole, $data->id, '用户登录', $desc, 'User login', $desc_en, $_SERVER["REMOTE_ADDR"]);
				$mailcenterConsoleNamespace = new Zend_Session_Namespace ( 'mailcenter_console' );
				$mailcenterConsoleNamespace->admin_name=$username;
				if (isset ( $mailcenterConsoleNamespace->mytasks )) {
					unset($mailcenterConsoleNamespace->mytasks);
				}
				$this->account->updateLoginsById ( $info['id'], 0 , 1);
				$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "firstpage", "createtem" );
			} else {
				$info = $this->account->getLoginsByName ( $username);
				if(isset($info) && !empty($info)){
					$logins = $info['logins'] + 1;
					$lock = 1;
					if($logins >= 3 && $logins < 5){
						$this->Smarty->assign ("error", "密码错误已超过3次，超过5次将被锁定！");
					}elseif($logins >= 5) {
						if($info['userrole'] == 'admin' || $info['userrole'] == 'sadmin'){
							$this->Smarty->assign ("error", "该帐号已被锁定或异常，请联系系统管理员！");
						}else{
							$this->Smarty->assign ("error", "该帐号已被锁定或异常，请联系任务发布员！");
						}
						$lock = 0;
					}else{
						$this->Smarty->assign ("error", "账号或密码错误，请重新输入");
					}
					$this->account->updateLoginsById ( $info['id'], $logins ,$lock);
					$cur_time = date( "Y-m-d H:i:s", time() );
					$lastaccessip = $_SERVER["REMOTE_ADDR"];
					$desc = '用户于'.$cur_time.'登录失败';
					$desc_en = 'User login failed at '.$cur_time;
					$this->account->updateLastAccess ($info['id'], $cur_time);
					BehaviorTrack::addBehaviorLog($info['username'], $info['userrole'], $info['id'], '用户登录', $desc, 'User login', $desc_en, $_SERVER["REMOTE_ADDR"]);
				}else{
					$this->Smarty->assign ("error", "账号或密码错误，请重新输入！");
				}
				$this->Smarty->display('login.php');
				exit();
			}
		} else {
			$this->Smarty->display('login.php');
		}
	}
	
	/**
	 * Logout action
	 */
	public function logoutAction() {
		//clean cookie
		Zend_Auth::getInstance ()->clearIdentity ();
		$mailcenterConsoleNamespace = new Zend_Session_Namespace ( 'mailcenter_console' );
		if (isset ( $mailcenterConsoleNamespace->mytasks )) {
			unset($mailcenterConsoleNamespace->mytasks);
		}
		if (isset ( $mailcenterConsoleNamespace->simpleSearch )) {
			unset($mailcenterConsoleNamespace->simpleSearch);
		}
		if (isset ( $mailcenterConsoleNamespace->advanceSearch )) {
			unset($mailcenterConsoleNamespace->advanceSearch);
		}
		// $this->Smarty->display('login.php');
		$this->_redirect('/index/login');
		//$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "login", "index" );
	}
	
	public function helpAction() {
		$role = $this->getCurrentUserRole();
		$check = $role;
		if ($role == "sadmin" || $role == "admin") {
			$check = "admin";
		} 
		if ($role == "stasker" || $role == "task") {
			$check = "task";
		}
		$this->Smarty->assign('check',$check);
		$this->Smarty->display ( 'help.php' );
	}
	
	public function checkaccountAction() {
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ( );
			$data = array ('name' => $filter->filter ( $this->_request->getPost ( 'name' ) ) );
		}
		if ($this->account->checkAccountName($data['name'])) {
			echo 1;
		} else {
			echo 2;
		}
	}
	
	/**
	 * retrieve password action
	 */
	public function retrievepwdAction () {
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ( );
			$mailbox = $filter->filter ( $this->_request->getPost ( 'mailbox' ) );
			$username = $filter->filter ( $this->_request->getPost ( 'username' ) );
			if ($mailbox != "" && $username != "") {
				$account = new Account ();
				$infos = $account->getAccountInfoByMail ($mailbox, $username);
				if ($infos['id'] != null && $infos['id'] != "") {
					$pattern = "23456789abcdefghijkmnpqrstuvwxyz";
					$data = array ();
					$newpwd = "";
					for ($i=0; $i<6; $i++) {
						$newpwd .= $pattern {mt_rand (0, 31)};
					}
					$data['password'] = MD5 ($newpwd); 
					$status = SystemConsole::sendmail ($username, $newpwd, $mailbox);
					if ($status == "success") {
						$account->updateAccount ($data, $infos['id']);
						echo "找回密码邮件已发送相应邮箱，请您查收！";
					} else {
						echo "发送邮件失败，请联系管理员检查“报告发送配置”是否正常！";
					}
				} else {
					echo "没有找到您输入的信息，请检查您的输入！";
				}
			} else {
				echo "找回密码失败！";
			}
		}
	}
	
	
	/**
	 * captcha action
	 */
	public function captchaAction() {
		$randval = "";
		for($i = 0; $i < 5; $i ++) {
			$randstr = mt_rand ( ord ( 'A' ), ord ( 'H' ) );
			srand ( ( double ) microtime () * 1000000 );
			$randv = mt_rand ( 0, 10 );			
			if ($randv % 2 == 0) {
				$randval .= mt_rand ( 1, 10 );
			} else {
				$randval .= chr ( $randstr );
			}
		}
		if (strlen($randval) > 4) {
			$randval = substr($randval, 0, 4);
		}
		
		$_SESSION['randval'] = $randval;
		
		$displaystr = "";
		$array = str_split($randval);
		for($i = 0; $i < count($array); $i ++) {
			$displaystr .= $array[$i];
			$displaystr .= " ";
		}
		
		$height = 21;
		$width = 82;
		$im = ImageCreateTrueColor ( $width, $height );
		$white = ImageColorAllocate ( $im, 255, 255, 255 );
		$blue = ImageColorAllocate ( $im, 25, 109, 156 );
		ImageFill ( $im, 0, 0, $white );
		srand ( ( double ) microtime () * 1000000 );
		ImageString ( $im, 5, 10, 2, $displaystr, $blue );
		for($i = 0; $i < 100; $i ++) {
			$randcolor = ImageColorallocate ( $im, rand ( 0, 255 ), rand ( 0, 255 ), rand ( 0, 255 ) );
			imagesetpixel ( $im, rand () % 70, rand () % 30, $randcolor );
		}

		
		ImageGIF ( $im );
		Header ( "Content-type: image/PNG" );


		echo $im;
		ImageDestroy ( $im );
	}

}
?>