<?php
require ('CommonController.php');
class ErrorController extends CommonController {
	
	function init() {
		parent::init();
	}
	
	public function noprivAction () {
		$this->Smarty->assign ("li_menu", "nopriv");	
		$this->Smarty->display ( 'nopriv.php' );
	}
	
	public function lockAction () {
		Zend_Auth::getInstance ()->clearIdentity ();
		$this->Smarty->assign ("li_menu", "lock");	
		$this->Smarty->display ( 'lock.php' );
	}
	
	public function iprestrictAction () {
		Zend_Auth::getInstance ()->clearIdentity ();
		$this->Smarty->assign ("li_menu", "iprestrict");	
		$this->Smarty->display ( 'iprestrict.php' );
	}
}
?>