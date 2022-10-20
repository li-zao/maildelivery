<?php
require ('CommonController.php');
class ListController extends CommonController {
	
	public $group;
	
	function init() {
		parent::init ();
		$this->group = new Group();
	}
	//
	//$role = $this->getCurrentUserRole();
	//	$uname = $this->getCurrentUser();
	
	//$userid = $this->getCurrentUserID();
	//
	function subsgroupAction() {
		$per_num = $this->_request->get ( 'per_num' );
		$search_cont = $this->_request->get ( 'search_cont' );
		$cur_page = $this->_request->get ( 'cur_page' );
		$page_type = $this->_request->get ( 'page_type' );
		$limit_from = 0;//default
		$limit_to = 10;//default
		if ($per_num != null && $per_num != "") {
			$limit_to = $per_num;
		}
		$this->Smarty->assign("per_num", $limit_to);
		$sql = "select * from mr_group where 1=1 ";
		$content_sql = "";
		if ($search_cont != "" && $search_cont != null) {
			$this->Smarty->assign ("search_cont", $search_cont);	
			$content_sql = "and name like '%$search_cont%' or description like '%$search_cont%' ";
		}
		$sql = $sql.$content_sql;
		$total = $this->group->getAllCountGroup($content_sql);//total of the group
		if ($total > 0) {
			$curpage = 1;
			$total_page = ceil($total/$limit_to);
			if ($cur_page != "" && $cur_page != null) {
				if ($page_type == "next") {
					$curpage = ($cur_page+1);
					if ($curpage > $total_page) {
						$curpage = $total_page;
					}
				} else if ($page_type == "pre") {
					$curpage = ($cur_page-1);
					if ($curpage < 1) {
						$curpage = 1;
					}
				} else if ($page_type == "first") {
					$curpage = 1;
				} else if ($page_type == "last") {
					$curpage = $total_page;
				}
			}
			$limit_from = ($curpage*$limit_to-$limit_to);
		} else {
			$total = 0;
			$curpage = 0;
			$total_page = 0;
		}
		
		$limit_sql = "limit $limit_from, $limit_to";
		
		$sql = $sql.$limit_sql;
		$cur_user = $this->getCurrentUser();
		$this->Smarty->assign ("cur_user", $cur_user);
		$groups = $this->group->getAllGroupInfo($sql);
		
		$this->Smarty->assign ("cur_page", $curpage);	
		$this->Smarty->assign ("total_page", $total_page);	
		$this->Smarty->assign ("groups", $groups);	
		$this->Smarty->display ( 'subsgroup.php' );	
	}
	
	
}
?>