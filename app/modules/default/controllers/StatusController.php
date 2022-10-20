<?php
require ('CommonController.php');
class StatusController extends CommonController {
	
	function init() {
		parent::init ();
	}
	//
	//$role = $this->getCurrentUserRole();
	//	$uname = $this->getCurrentUser();
	
	//$userid = $this->getCurrentUserID();
	//
	function systemstatusAction() {
		$cur_user = $this->getCurrentUser();
		$this->Smarty->assign ("cur_user", $cur_user);	
		$this->Smarty->display ( 'charts.php' );	
	}
}
?>