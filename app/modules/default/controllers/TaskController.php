<?php
require('CommonController.php');
require('Des.class.php');
require('page.class.php');
require_once('excel/reader.php');
require_once('Zend/Mail.php');
require_once('Zend/Mail/Transport/Smtp.php');

class TaskController extends CommonController
{
    public $account;
    public $task;
    public $group;
    public $vocation;
    public $attachment;
    public $filter;

    function init()
    {
        header('Content-type:text/html;charset=utf-8');
        parent::init();
        $this->account = new Account ();
        $this->task = new Task ();
        $this->group = new Group ();
        $this->filter = new Filter ();
        $this->vocation = new Vocation ();
        $this->attachment = new Attachment ();
    }

	function setSimpleSearchKey($name, $value) {
		$mailcenterConsoleNamespace = new Zend_Session_Namespace ( 'outgoing_console' );
		if (isset ( $mailcenterConsoleNamespace->simpleSearch )) {
			$mailcenterConsoleNamespace->simpleSearch[$name] = $value;
		} else {
			$mailcenterConsoleNamespace->simpleSearch = array ();
			$mailcenterConsoleNamespace->simpleSearch[$name] = $value;
		}
	}
    /*向导创建任务--第一步--收件人组*/
    function createAction()
    {
        $uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		//$condition=self::checkLoginAudit($role);
		$condition= "1=1";		
        $pagenum = $_GET['pagenum'] ? $_GET['pagenum'] : 10;
        $gname = $_GET['gname'];
		$gname_sql=str_replace(array("'", "_"), array('', "\_"), $gname);
        $remark = $_GET['remark'];
		$remark_sql=str_replace(array("'", "_"), array('', "\_"), $remark);
        $createtime = $_GET['createtime'];
		$createtime=str_replace(array("'"), array(''), $createtime);
		$parameter = "";
		$parameter = "&pagenum=".$pagenum;
		$this->Smarty->assign('pagenum',$pagenum);
		
        $where = ' 1=1 ';
		if($gname!=""){
            $where .= " and gname like '%" . $gname_sql . "%' ";
			$parameter .= "&gname=".$gname_sql;
        }
		if($remark!=""){
            $where .= " and remark like '%" . $remark_sql . "%' ";
			$parameter .= "&remark=".$remark_sql;
        }
		if($createtime!=""){
            $where .= " and createtime like '%" . $createtime . "%'";
			$parameter .= "&createtime=".$createtime;
        }
		$search = $_GET['search'];
		if($search){
			$description='该用户进行查询联系人组的操作';
			$description_en='The user performs the operation of query contact group';
			BehaviorTrack::addBehaviorLog($uname, $role, $uid, '查询操作', $description, 'Query operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		}
		$this->Smarty->assign('search', $search);
        $this->Smarty->assign('gname', $gname);
        $this->Smarty->assign('remark', $remark);
        $this->Smarty->assign('createtime', $createtime);
		
        $result_group = $this->group->getSearchgroup($where);
		$total=count($result_group);
		$page=new page($total,$pagenum,$parameter);
		
		$result_group = $this->group->getSearchgroup($where." order by createtime desc "."{$page->limit}");
		//统计每个组下面有多少个联系人
        foreach ($result_group as $k => $val) {
            if (!empty($val['tablename'])) {
                $total_person = $this->group->getAllCountperson($val['tablename']);
                $result_group[$k]['person_num'] = $total_person['num'];
            }
        }

        if (!empty($_GET['id'])) {
            $draftdata = $this->task->getOnetask($_GET['id']);
            $arrgroups = explode(',', $draftdata['groups']);
            $tid = $draftdata['id'];
            $fid = $draftdata['fid'];

        }
        if ($arrgroups[0] == 'all') {
            $gid = $arrgroups[0];
        }
		if($_GET['step']){
			 $this->Smarty->assign('confirm', $_GET['step']);	
		}
        $filterdata = $this->filter->selectAllFilter($condition);
		$this->Smarty->assign('page',$page->fpage());
        $this->Smarty->assign('gid', $gid);
        $this->Smarty->assign('arrgroups', $arrgroups);
        $this->Smarty->assign('uid', $uid);
        $this->Smarty->assign('tid', $tid);
        $this->Smarty->assign('fid', $fid);
        $this->Smarty->assign("data", $result_group);
        $this->Smarty->assign('filterdata', $filterdata);
        $this->Smarty->assign("li_menu", "create");
        $this->Smarty->display('createtask.php');
    }

    /*向导创建任务--第二步--任务设置*/
    function settaskAction()
    {
		$draftdata = $this->task->getOnetask($_GET['id']);
        $task_name = $draftdata['task_name'] ? $draftdata['task_name'] : "新建任务_" . $draftdata['createtime'];
        $uid = $draftdata['uid'] ? $draftdata['uid'] : '';
		$role = $this->getCurrentUserRole();
		$condition=self::checkLoginAudit($role);
		$userinfo = $this->account->getTaskerUsers($uid);
		$arr=array();
        foreach ($userinfo as $val) {
            $arr['sender'] = $val['username'];
            $arr['sendemail'] = $val['mail'];
            $arr['replyemail'] = $val['mail'];
        }
        //任务分类
        $catdata = $this->vocation->getAllTpls($uid);
		//$catdata = $this->vocation->selectAllType($condition);
		if($_GET['step']){
			 $this->Smarty->assign('confirm', $_GET['step']);	
		}
		
		//需要邮件主题加AD的域名
		$domainAD = Zend_Registry::get('domainAD');
		$this->Smarty->assign("domainAD", $domainAD);
		
		//插入变量
		$basic_variable = $this->dbAdapter->fetchAll("select showname,name from mr_group_extension where hidden=1");
		$define_variable = $this->dbAdapter->fetchAll("select showname,name from mr_group_extension where hidden=0");
		$this->Smarty->assign("basic_variable",$basic_variable);
		$this->Smarty->assign("define_variable",$define_variable);
		
		$this->Smarty->assign('tid', $_GET['id']);
        $this->Smarty->assign('uid', $draftdata['uid']);
		$this->Smarty->assign('task_name', $task_name = $draftdata['task_name'] ? $draftdata['task_name'] : "新建任务_" . $draftdata['createtime']);
        $this->Smarty->assign('subject', $subject = $draftdata['subject'] ? $draftdata['subject'] : '');
        $this->Smarty->assign('cid', $cid = $draftdata['cid'] ? $draftdata['cid'] : '');
        $this->Smarty->assign('is_insert_ad', $is_insert_ad = $draftdata['is_insert_ad'] ? $draftdata['is_insert_ad'] : ''); 
        $this->Smarty->assign('sender', $sender = $draftdata['sender'] ? $draftdata['sender'] : $arr['sender']);
        $this->Smarty->assign('sendemail', $sendemail = $draftdata['sendemail'] ? $draftdata['sendemail'] : $arr['sendemail']);
        $this->Smarty->assign('replyemail', $replyemail = $draftdata['replyemail'] ? $draftdata['replyemail'] : $arr['replyemail']);
        $this->Smarty->assign("catdata", $catdata);
        $this->Smarty->assign("li_menu", "create");
        $this->Smarty->display('settask.php');
    }

    /*向导创建任务--第三步--内容设计*/
    function designtaskAction()
    {
        $cycle_week_data = array(1 => '周一', 2 => '周二', 3 => '周三', 4 => '周四', 5 => '周五', 6 => '周六', 7 => '周日', 15 => '周一至周五', 67 => '周六至周日');
        $cycle_month_data = array(1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8', 9 => '9', 10 => '10', 11 => '11', 12 => '12', 13 => '13', 14 => '14', 15 => '15', 16 => '16', 17 => '17', 18 => '18', 19 => '19', 20 => '20', 21 => '21', 22 => '22', 23 => '23', 24 => '24', 25 => '25', 26 => '26', 27 => '27', 28 => '28', 29 => '29', 30 => '30', 31 => '31');
        //获取模板分类
        //$this->vocation=new Vocation();
		$draftdata = $this->task->getOnetask($_GET['id']);
        $uid = $draftdata['uid'] ? $draftdata['uid'] : '';
		$role = $this->getCurrentUserRole();
		$condition=self::checkLoginAudit($role);
        $tplrows = $this->vocation->selectAllType($condition);
        if ($tplrows) {
            $this->Smarty->assign('tplrows', $tplrows);
        }

        if (!empty($draftdata)) {
            $this->Smarty->assign('content', $draftdata['data']);
            $this->Smarty->assign('sendtype', $draftdata['sendtype']);
            $this->Smarty->assign('sendtime', $draftdata['sendtime']);
            $this->Smarty->assign('cycle_time', $draftdata['cycle_time']);
            $this->Smarty->assign('cycle_end_time', $draftdata['cycle_end_time']);
            $this->Smarty->assign('cycle_type', $draftdata['cycle_type']);
            $this->Smarty->assign('cycle_week', $draftdata['cycle_week']);
            $this->Smarty->assign('cycle_month', $draftdata['cycle_month']);
        }

        $attdata = $this->attachment->seltaskattach($_GET['id'],$uid);
        if (!empty($attdata)) {
            foreach ($attdata as $val) {
                $filenames[] = array('tmp_id' => $val['id'], 'filename' => $val['truename'], 'path' => $val['path']);
                $files[] = $val['truename'];
                $path[] = $val['path'];
            }
            $filename = implode(',', $files);
            $path = implode(',', $path);
            $this->Smarty->assign('filename', $filename);
            $this->Smarty->assign('path', $path);
            $this->Smarty->assign('filenames', $filenames);
            $this->Smarty->assign('files', $files);
        }
      
        $attachs = $this->attachment->searchTaskattach($condition);

		//订阅
        $usersubscription = $this->dbAdapter->fetchAll("select * from mr_usersubscription where ".$condition);
		if (!empty($usersubscription)) {
            foreach ($usersubscription as $key => $val) {
				$string = $this->randomkeys(1)."#". $val['id'] ."#". $this->randomkeys(3);
                $des = new Des("lg!@2015");
                $usersubscription[$key]['key'] = urlencode($des->encrypt($string));
            }
        }

		if($draftdata['isreport'] == 1){
			$isreport=$draftdata['isreport'];
			$reportemail=$draftdata['reportemail'];
		}else{
			$userinfo = $this->account->getTaskerUsers($uid);
			foreach ($userinfo as $val) {
				$reportemail = $val['mail'];
			}
        }
		if($_GET['step']){
			 $this->Smarty->assign('confirm', $_GET['step']);	
		}
		
		//插入变量
		$basic_variable = $this->dbAdapter->fetchAll("select showname,name from mr_group_extension where hidden=1");
		$define_variable = $this->dbAdapter->fetchAll("select showname,name from mr_group_extension where hidden=0");
		$this->Smarty->assign("basic_variable",$basic_variable);
		$this->Smarty->assign("define_variable",$define_variable);
        $this->Smarty->assign("isreport", $isreport);
        $this->Smarty->assign("reportemail", $reportemail);
        $this->Smarty->assign("usersubscription", $usersubscription);
        $this->Smarty->assign('tid', $_GET['id']);
        $this->Smarty->assign('cycle_week_data', $cycle_week_data);
        $this->Smarty->assign('cycle_month_data', $cycle_month_data);
        $this->Smarty->assign('attachs', $attachs);
        $this->Smarty->assign("li_menu", "create");
        $this->Smarty->display('taskdesign.php');
    }

    /*向导创建任务--第四步--确认信息*/
    function confirmtaskAction()
    {
        $draftdata = $this->task->getOnetask($_GET['id']);
		$role = $this->getCurrentUserRole();
		$condition=self::checkLoginAudit($role);
        if (!empty($draftdata)) {
            $w = "";
            if ($draftdata['fid'] != 0) {
                $filter = $this->filter->getOnefilter($draftdata['fid']);
                $w = " where " . $filter[0]['condition'];
            }
            //根据组id获取组名
            if ($draftdata['groups'] == 'all') {
                //var_dump($draftdata[0]['fid']);
                $where = " ".$condition."";
                $gnames = $this->group->getAllGroupInfo($where);
                foreach ($gnames as $k => $val) {
                    if (!empty($val['tablename'])) {
                        $total_person = $this->group->getCountfilterGroup($val['tablename'], $w);
                        $total += $total_person;
                    }
                }
                $gid = $draftdata['groups'];
            } else {
                $where = "  id in(" . $draftdata['groups'] . ") and ".$condition."";
                $gnames = $this->group->getAllGroupInfo($where);
                foreach ($gnames as $k => &$val) {
                    if (!empty($val['tablename'])) {
                        $total_person = $this->group->getCountfilterGroup($val['tablename'], $w);
                        $gnames[$k]['person_num'] = $total_person;
                        $total += $total_person;
                    }
                }
            }
            $arr = array("tnum" => $total, "task_name" => $draftdata['task_name'], "sendemail" => $draftdata['sendemail']);
        }
	
        $this->Smarty->assign('tid', $_GET['id']);
        $this->Smarty->assign("arrtask", $arr);
        $this->Smarty->assign("gid", $gid);
        $this->Smarty->assign("gnames", $gnames);
        $this->Smarty->assign("li_menu", "create");
        $this->Smarty->display('taskconfirm.php');
    }

    //*向导创建任务完成把所有数据插入数据库
    function inserttaskAction()
    {
		$uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$condition=self::checkLoginAudit($role);
		
        $arr['uid'] = $uid;
	    if($_POST['fid']) {
			$arr['fid']=$_POST['fid'];
            $filter = $this->filter->getOnefilter($_POST['fid']);
            $w = " where " . $filter[0]['condition'];
        }
        if (!empty($_POST['gnames']) || !empty($_POST['groupnames'])) {
            $arr['groups'] = $_POST['gnames'] ? $_POST['gnames'][0] : implode(',', $_POST['groupnames']);
            if ($arr['groups'] == 'all') {
                $where = " ".$condition."";
            } else {
                $where = " id in(" . $arr['groups'] . ") and ".$condition."";
            }
	
            $gnames = $this->group->getAllGroupInfo($where);
            foreach ($gnames as $val) {
                if (!empty($val['tablename'])) {
                    $total_person = $this->group->getCountfilterGroup($val['tablename'], $w);
                    $total += $total_person;
                }
            }
            $arr['total'] = $total;
        }
		
  	   $arr['createtime'] = date('Y-m-d H:i:s', time());	   
	   $arr['draft'] = 1;
       $userinfo = $this->account->getAccountInfoByID($uid);
       $arr['checkpass'] = self::setTaskaudit($userinfo);
	   if ($arr['checkpass'] == 3) {
            $arr['status'] = 2;
       } else {
            $arr['status'] = 1;
       }
	   if($_POST['taskname']){
			$arr['task_name'] = preg_replace("/[\s]+/is", " ", $_POST['taskname']);
			//$arr['task_name']=$_POST['taskname'];
	   }	
	   if($_POST['subject']){
			//$arr['subject']=$_POST['subject'];
			$arr['subject'] = preg_replace("/[\s]+/is", " ", $_POST['subject']);
	   }
	   if($_POST['domainAD']){
			$arr['domainAD']=$_POST['domainAD'];
	   }
	   if($_POST['sender']){
			$arr['sender']=$_POST['sender'];
	   }	 
	   if($_POST['sendemail']){
			$arr['sendemail']=$_POST['sendemail'];
	   }	
	   if($_POST['replyemail']){
			$arr['replyemail']=$_POST['replyemail'];
	   }	 
	   if($_POST['cid']){
			$arr['cid']=$_POST['cid'];
	   }	
	   if($_POST['is_insert_ad']){
			$arr['is_insert_ad']=$_POST['is_insert_ad'];
	   }	
	   if($_POST['content']){
			$arr['data']=$_POST['content'];
	   }	
	   if($_POST['random']){
			$arr['random']=$_POST['random'];
	   }
	   if($_POST['isreport']){
			if($_POST['isreport'] == 1){
				$arr['isreport'] = $_POST['isreport'];
				$arr['reportemail'] = $_POST['reportemail'];
			}else{
				$arr['isreport']='';
				$arr['reportemail']='';
			}
		}
		if($_POST['is_server_send'] != ''){
			$arr['sendtype'] = $_POST['is_server_send'];
			//当发送类型为0时为立即发送。发送时间为当前时间
			if ($arr['sendtype'] == 0) {
				$arr['sendtime'] = date('Y-m-d H:i:s', time());
			} else {
				$arr['sendtime'] = $_POST['start_time'];
			}
			if ($arr['sendtype'] == 2) {
				$arr['cycle_time'] = $_POST['cycle_time'];
				$arr['cycle_end_time'] = $_POST['next_end_time'];
				$arr['cycle_type'] = $_POST['cycle_type'];
				$arr['cycle_week'] = $_POST['cycle_week'];
				$arr['cycle_month'] = $_POST['cycle_month'];
				switch ($arr['cycle_type']) {
					case 2:
						unset($arr['cycle_month']);
						break;
					case 3:
						unset($arr['cycle_week']);
						break;
					default:
						unset($arr['cycle_month'], $arr['cycle_week']);
				}
			}
		}
		if($_POST['path']){
			$path = trim($_POST['path'], ',');
			$filename = trim($_POST['filename'], ',');
		}
		//测试邮件
		$testemail = $_POST['testemail'];
		if($_POST['act'] == 'test_send'){
			if ($testemail != "") {
				$newarr['testmailbox'] = $testemail;
				if ($_POST['edit'] == 'edit_draft') {
					$newarr['subject'] = $_POST['subject'];
					$newarr['data'] = $_POST['content'];
					self::addattachment($_POST['path'], $_POST['filename'], $_POST['uid'], $_POST['tid']);
					$rel = self::testmailsend($_POST['tid'], $testemail, $_POST['subject'], $_POST['content']);
				} else {
					$draftdata = $this->task->getOnetask($_POST['tid']);
					$rel = self::testmailsend($_POST['tid'], $testemail, $draftdata['subject'], $draftdata['data']);
				}
				if ($rel == "Success") {
					$rows = $this->task->updateTask($newarr, $_POST['tid']);
					$description = "该用户在发送测试邮件，接收邮箱为：" . $newarr['testmailbox'];
					$description_en = "The user to send a test message,the receiving mailbox: " . $newarr['testmailbox'];
					$operationName = '发送测试任务';
					$operationName_en = 'send test task';
					self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
					exit("Success"); 
				} elseif($rel == "Error") {
					exit("Error");
				} else {
					exit('No'); 
				}
				
			}
		}
	   if($_POST['tid'] == ''){
			$tid = $this->task->insertTask($arr);
			if ($tid) {
				$this->_redirect('/task/settask?id='.$tid);
			}
	   }else{
			//保存到草稿
			if($_POST['act'] == 'draft'){
				$description = "该用户将新建任务存稿，任务名称为：" . $arr['task_name'];
                $description_en = "The user will be the new task manuscripts, the task name is: " . $arr['task_name'];
                $operationName = '保存任务到草稿';
                $operationName_en = 'Save task to draft';
                self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
				exit('Success');
			}
			$rows = $this->task->updateTask($arr, $_POST['tid']);
			if($rows){
				self::addattachment($path, $filename, $uid, $_POST['tid']);
				//直接发送
				if($_POST['act'] == 'confirm'){
					$arr['draft'] = 0;
					$rows = $this->task->updateTask($arr, $_POST['tid']);
					$result = $this->task->getOnetask($_POST['tid']);
					if ($arr['checkpass'] == 3 && $result['sendtype'] == 2) {
					$arrPlan = array('status' => $arr['status'], 'cycle_type' => $result['cycle_type'], 'cycle_time' => $result['cycle_time'], 'cycle_end_time' => $result['cycle_end_time'], 'cycle_week' => $result['cycle_week'], 'cycle_month' => $result['cycle_month']);
							switch ($arrPlan['cycle_type']) {
								case 2:
									unset($arrPlan['cycle_month']);
									break;
								case 3:
									unset($arrPlan['cycle_week']);
									break;
								default:
									unset($arrPlan['cycle_month'], $arrPlan['cycle_week']);
							}
						self::setCycleTask($_POST['tid'], $arrPlan);
					}
					$description = "该用户提交新建任务，任务名称为：" . $arr['task_name'];
					$description_en = "The user submits a new task, the task name is: " . $arr['task_name'];
					$operationName = '提交新建任务';
					$operationName_en = 'submit a new task';
					self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
					exit('Success');
				}
				
				if($_POST['act'] == 'edit'){
					$step=trim($_POST['step']);
					switch($step){
						case 'one':
							$this->_redirect('/task/create?id='.$_POST['tid'].'&step='.$_POST['old_step']);
							break;
						case 'two':
							$this->_redirect('/task/settask?id='.$_POST['tid'].'&step='.$_POST['old_step']);
							break;
						case 'three':
							$this->_redirect('/task/designtask?id='.$_POST['tid'].'&step='.$_POST['old_step']);
							break;
						default :
							$this->_redirect('/task/confirmtask?id='.$_POST['tid']);
							break;
					}
						
				}
				
				if($_POST['act'] == 'save_draft' && $_POST['old_step'] == 'confirm'){
					$this->_redirect('/task/confirmtask?id='.$_POST['tid']);
				}else{
					$step=trim($_POST['step']);
					$return=trim($_POST['return']);
					switch($step){
						case 'one':
							$this->_redirect('/task/settask?id='.$_POST['tid']);
							break;
						case 'two':
							if($return == 'up'){
								$this->_redirect('/task/create?id='.$_POST['tid']);
							}else{
								$this->_redirect('/task/designtask?id='.$_POST['tid']);
							} 
							break;
						case 'three':
							//exit;
							if($return == 'up'){
								$this->_redirect('/task/settask?id='.$_POST['tid']);
							}else{
								$this->_redirect('/task/confirmtask?id='.$_POST['tid']);
							} 
							break;
						default :
							$this->_redirect('/task/confirmtask?id='.$_POST['tid']);
							break;
					}
				}	
			}
	   }
	  
    }
	
	 function searchgroupsAction() {	
		if ($this->_request->isPost ()) {
			$where ="1=1";
			$groups = $this->group->getAllGroupInfo($where);
			if (!empty($groups)) {
				foreach ($groups as $g_k => $g_v) {
					$total_person = $this->group->getAllCountperson($g_v['tablename']);
					$groups[$g_k]['count'] = $total_person['num'];
					$count += $total_person['num'];
			 	}
				exit(json_encode($groups));
			}
		 }
	 }
	 
    //处理上传文件
	 function uploadaddrAction() 
	 {
		 if ($this->_request->isPost ()) {
			$uid = $this->getCurrentUserID();
            $tablename = 'mr_group_' . self::uuid();
			$arr = array('gname' => $_POST['gname'], 'createtime' => date("Y-m-d H:i:s"), 'uid' => $uid, 'tablename' => $tablename);
			$this->dbAdapter->insert('mr_group', $arr);
			$gid = $this->dbAdapter->lastInsertId();
			self::createTable($tablename, $arr);
			
			//$url = 'task/addtask';
			//self::dealUpFile($tablename, $gid, $url);
			$tmp = $_FILES['fileToUpload']['tmp_name'];
			$filename = $_FILES['fileToUpload']['name'];
			//$_SESSION['uploadExcel'] = $filename;
			if (empty ($tmp)) { 
				echo 1;//请选择文件
				exit;
			} 
			$brr = array();
			//取自定义字段
			$res = $this->dbAdapter->fetchAll("select * from mr_group_extension");
			$save_path = "xls/";
			$extend=strrchr ($filename,'.');
			$file_name = $save_path.date('Ymdhis') . $extend; //上传后的文件保存路径和名称 
			$result=move_uploaded_file($tmp,$file_name);
			if($result){
				if($extend == ".xlsx"){
					$objReader = PHPExcel_IOFactory::createReader('Excel2007');
				}else if($extend == ".xls"){
					$objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format 
				}
				$objPHPExcel = $objReader->load($file_name); 
				
				//$sheet = $objPHPExcel->getSheet(0); 
				//$highestRow = $sheet->getHighestRow();           //取得总行数
				//$highestColumn = $sheet->getHighestColumn(); //取得总列数
				$objWorksheet = $objPHPExcel->getActiveSheet();
				$highestRow = $objWorksheet->getHighestRow(); 
				$highestColumn = $objWorksheet->getHighestColumn();
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
				for($row = 2;$row <= $highestRow;$row++){
					for($col = 0;$col < $highestColumnIndex;$col++){
						$strs[$row][$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
					}
				}
				//导入到联系人组中
				$value = "";
				if(!empty($strs)){
					foreach($strs as $sk=>$sv){
						if(!empty($sv)){
							$email = "/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/i";
							$phone = "/^0{0,1}(13[0-9]|15[0-9]|18[0-9])[0-9]{8}$/";
							$mail = trim($sv[1]);
							if(!preg_match($email,$mail)){
								continue;
							}
							if($sv[2] == "男"){
								$strs[$sk][2] = 1;
							}else if($sv[2] == "女"){
								$strs[$sk][2] = 2;
							}else{
								$strs[$sk][2] = 1; 
							}
							if(!empty($sv[4])){
								if(!preg_match($phone,$sv[4])){
									$strs[$sk][4] = "";
									$sv[4] = "";
								}
							}
							$strs[$sk][3] = strtotime($sv[3]);
							$value .= "(";
							foreach($sv as $key=>$val){
								$value .= "'".$val."',";
							}
						}
						$value .= "'".$uid."'),";
					}
				}
				$value = rtrim($value,",");
				$type = "(";
				foreach($res as $rk=>$rv){
					$type .= $rv['name'].",";
				}
				$type .= "uid)";
				
				$type_num = count(explode(',',$type));
				if ( $type_num != ($highestColumnIndex +1) ){//上传联系人表格字段,与现有的联系人属性个数不吻合
					echo 2;//上传失败
					exit;
				}
				if($value){
					$sql1 = "insert into ".$tablename." ".$type." values ".$value;
					$query1 = $this->dbAdapter->query($sql1);
				}
				//导入到总表
				if(!empty($strs)){
					foreach($strs as $sk=>$sv){
						if(!empty($sv)){
							$email = "/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/i";
							$mail = trim($sv[1]);
							if(!preg_match($email,$mail)){
								continue;
							}
							array_push($brr,$sv[1]);
							$number = $this->dbAdapter->fetchOne("select count(*) from mr_subscriber where mailbox='".$sv[1]."'");//  and uid=".$uid
							if($number>0){
								continue;
							}
							$subscriber_value .= "(";
							foreach($sv as $key=>$val){
								$subscriber_value .= "'".$val."',";
							}
						}
						$subscriber_value .= "'".$uid."','".$gid."'),";
					}
				}
				$subscriber_value = rtrim($subscriber_value,",");
				$type = "(";
				foreach($res as $rk=>$rv){
					$type .= $rv['name'].",";
				}
				$type .= "uid,groups)";
				if($subscriber_value){
					$sql2 = "insert into mr_subscriber ".$type." values ".$subscriber_value;
					$query2 = $this->dbAdapter->query($sql2);
				}
				if($brr){
					foreach($brr as $key=>$val){
						if($val){
							$groups = $this->dbAdapter->fetchOne("select groups from mr_subscriber where mailbox='".$val."'");
							$ext = explode(",",$groups);
							if($groups){
								if(!in_array($gid,$ext)){
									$newgroups = $groups.",".$gid;
									$this->dbAdapter->query("update mr_subscriber set groups='".$newgroups."' where mailbox='".$val."'");
								}
							}else{
								$this->dbAdapter->query("update mr_subscriber set groups='".$gid."' where mailbox='".$val."'");
							}
						}
					}
				}
				
			}
			$uname = $this->getCurrentUser();
			
			$role = $this->getCurrentUserRole(); 
			$userid = $this->getCurrentUserID();
			$description = "该用户进行上传地址操作.上传文件名字为：".$_FILES['fileToUpload']['tmp_name'];
			$description_en = "The user upload address operation. The file name is: ".$_FILES['fileToUpload']['tmp_name'];
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '上传操作', $description, 'UPload operation', $description_en, $_SERVER["REMOTE_ADDR"]);
				
			unlink("/var/www/maildelivery/".$file_name);
			if (! empty ( $_FILES ['fileToUpload'] ['error'] )) {
				switch ($_FILES ['fileToUpload'] ['error']) {
					default :
						echo 2;//上传失败
						exit;
				}
			} else {
				echo 3;//上传成功
			}
        }
	 }
	
    /*快速创建列表*/
    function addtaskAction()
    {	
		$uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$condition=self::checkLoginAudit($role);
        $createtime = date('Y-m-d H:i:s', time());
		$groups = $this->dbAdapter->fetchAll("select * from mr_group where 1=1 order by createtime desc");
		$count = $this->dbAdapter->fetchOne("select count(id) from mr_subscriber");
        if (!empty($groups)) {
            foreach ($groups as $g_k => $g_v) {
                $total_person = $this->group->getAllCountperson($g_v['tablename']);
                $groups[$g_k]['count'] = $total_person['num'];
            }
        }
        //获取模板分类
		$catdata = $this->vocation->getAllTpls($uid);
        //$catdata = $this->vocation->selectAllType($condition);
        if ($catdata) {
            $this->Smarty->assign('rows', $catdata);
        }
		
        $userinfo = $this->account->getAccountInfoByID($uid);
        $attachs = $this->attachment->searchTaskattach($condition);
        $filters = $this->filter->selectAllFilter($condition);

		//订阅
        $usersubscription = $this->dbAdapter->fetchAll("select * from mr_usersubscription where uid=".$uid);
        if (!empty($usersubscription)) {
            foreach ($usersubscription as $key => $val) {               
                $string = $this->randomkeys(1)."#". $val['id'] ."#". $this->randomkeys(3);
                $des = new Des("lg!@2015");
                $usersubscription[$key]['key'] = urlencode($des->encrypt($string));
            }
        }
		
		//需要邮件主题加AD的域名
		$domainAD = Zend_Registry::get('domainAD');
		$this->Smarty->assign("domainAD", $domainAD);
		
		//插入变量
		$basic_variable = $this->dbAdapter->fetchAll("select showname,name from mr_group_extension where hidden=1");
		$define_variable = $this->dbAdapter->fetchAll("select showname,name from mr_group_extension where hidden=0");
		
		$this->Smarty->assign("basic_variable",$basic_variable);
		$this->Smarty->assign("define_variable",$define_variable);
        
        $this->Smarty->assign('uid', $uid);
        $this->Smarty->assign('sender', $userinfo['username']);
        $this->Smarty->assign('sendemail', $userinfo['mail']);

        $this->Smarty->assign("groups", $groups);
        $this->Smarty->assign("count", $count);

        $this->Smarty->assign("filters", $filters);
        $this->Smarty->assign("usersubscription", $usersubscription);
        $this->Smarty->assign("catdata", $catdata);
        $this->Smarty->assign("attachs", $attachs);
        $this->Smarty->assign("currtime", $createtime);
        $this->Smarty->assign("li_menu", "addtask");
        $this->Smarty->display('taskadd.php');
    }

    //获取随机数
    function randomkeys($length)
    {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 51)}; //生成php随机数
        }
        return $key;
    }

    //加密函数
    function encrypt($data, $key)
    {
        $key = md5($key);
        $x = 0;
        $len = strlen($data);
        $l = strlen($key);
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

    //解密函数
    function decrypt($data, $key)
    {
        $key = md5($key);
        $x = 0;
        $data = base64_decode($data);
        $len = strlen($data);
        $l = strlen($key);
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }

    //获取域名
    function ajaxgetdomainnameAction()
    {	
        $serviceinfo = $this->dbAdapter->fetchRow("select domainname,serviceport from mr_console");
        echo json_encode($serviceinfo);
    }

    //附件搜索
    function searchattachAction()
    {
        $uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$condition=self::checkLoginAudit($role);
        if ($_POST['searchattach'] == 'search' && $_POST['attachname'] != '') {
            $where = " " . $condition . " and truename like '%" . $_POST['attachname'] . "%'";
            $attachs = $this->attachment->searchTaskattach($where);
        } else {
            $attachs = $this->attachment->searchTaskattach($condition);
        }
        exit(json_encode($attachs));
    }

    /*草稿*/
    function drafttaskAction()
    {
        $num = $_GET['num'] ? $_GET['num'] : 10;
        $uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$condition=self::checkLoginAudit($role);
        $task_name = $_GET['taskname'];
		$task_name=str_replace(array("'"), array(''), $task_name);
        $cid = $_GET['cid'];
        $stattime = $_GET['stattime'];
        $lasttime = $_GET['lasttime'];
		$parameter = "";
		$parameter = "&num=".$num;
		$this->Smarty->assign('num',$num);
        $where = "";
        if (isset($task_name) && !empty($task_name)) {
            if (empty($where)) {
                $where = " and task_name like '%" . $task_name . "%' ";
            } else {
                $where .= " and task_name like '%" . $task_name . "%' ";
            }
			$parameter .= "&task_name=".$task_name;
        }
        if (isset($cid) && !empty($cid)) {
            if (empty($where)) {
                $where = " and cid = " . $cid . " ";
            } else {
                $where .= " and cid = " . $cid . " ";
            }
			$parameter .= "&cid=".$cid;
        }
        if (isset($stattime) && !empty($stattime)) {
            if (empty($where)) {
                $where = " and createtime >= '" . $stattime . "' ";
            } else {
                $where .= " and createtime >= '" . $stattime . "' ";
            }
			$parameter .= "&stattime=".$stattime;
        }
        if (isset($lasttime) && !empty($lasttime)) {
            if (empty($where)) {
                $where = " and createtime  <= '" . $lasttime . "' ";
            } else {
                $where .= " and createtime <= '" . $lasttime . "' ";
            }
			$parameter .= "&lasttime=".$lasttime;
        }
		$search = $_GET['search'];
		if($search){
			$userid = $this->getCurrentUserID();
			$description='该用户进行查询任务草稿箱的操作';
			$description_en='The user performs the operation of querying task draft';
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询操作', $description, 'Query operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		}
		$this->Smarty->assign('search', $search);
        //列表页分页
        //列表页分页查询所有任务草稿
        if (isset($where) && !empty($where)) {
            $where .= " and draft = 1 and " . $condition . "";
            $this->Smarty->assign("task_name", $task_name);
            $this->Smarty->assign("cid", $cid);
            $this->Smarty->assign("status", $status);
            $this->Smarty->assign("stattime", $stattime);
            $this->Smarty->assign("lasttime", $lasttime);
        } else {
            $where = " and draft = 1 and " . $condition . "";
        }
		
        $total = $this->task->getAllCountTask($where);
        $page = new Page($total, $num, $parameter);
        $where .= " order by createtime desc {$page->limit}";
        $draftdata = $this->task->selectAllTask($where);
		if (!empty($draftdata)) {
			foreach ($draftdata as &$draftval) {
				$username = $this->dbAdapter->fetchOne("select username from mr_accounts where id =".$draftval['uid']."");
				$draftval['username'] = $username;
				if($draftval['uid'] == $uid){
					$draftval['mine'] = 1;
				}else{
					$draftval['mine'] = 0;
				}
			}
		}
        //搜索中的任务分类
        $catdata = $this->vocation->selectAllType($condition);
        $this->Smarty->assign("catdata", $catdata);
        $this->Smarty->assign("page", $page->fpage());
        $this->Smarty->assign("draftdata", $draftdata);
        $this->Smarty->assign("li_menu", "drafttask");
        $this->Smarty->display('taskdraft.php');
    }

    /*快速创建任务获取数据插入数据库*/
    function quickctaskAction()
    {
        //添加任务
        if ($this->_request->isPost() || !empty($_POST)) {
            //$arr=array();
            $uid = $this->getCurrentUserID();
            $arr['task_name'] = preg_replace("/[\s]+/is", " ", $this->_request->getPost('task_name'));
            $arr['cid'] = $this->_request->getPost('cid');
            $arr['uid'] = $uid;
            $arr['sender'] = $this->_request->getPost('sender');
            $arr['sendemail'] = $this->_request->getPost('sendemail');
            $arr['replyemail'] = $this->_request->getPost('replyemail');
            $arr['subject'] = preg_replace("/[\s]+/is", " ", $this->_request->getPost('subject'));
			$arr['domainAD'] =$this->_request->getPost('domainAD');
            $arr['data'] = $this->_request->getPost('content');
			if($this->_request->getPost('isreport') == 1){
				$arr['isreport'] = $this->_request->getPost('isreport');
				$arr['reportemail'] = $this->_request->getPost('reportemail');
			}else{
				$arr['isreport']='';
				$arr['reportemail']='';
			}
            $path = $this->_request->getPost('path');
            $filename = $this->_request->getPost('filename');
            $arr['sendtype'] = $this->_request->getPost('is_server_send');
            //当发送类型为0时为立即发送。发送时间为当前时间
            if ($arr['sendtype'] == 0) {
                $arr['sendtime'] = date('Y-m-d H:i:s', time());
            } else {
                $arr['sendtime'] = $this->_request->getPost('start_time');
            }
            if ($arr['sendtype'] == 2) {
                $arr['cycle_time'] = $this->_request->getPost('cycle_time');
                $arr['cycle_end_time'] = $this->_request->getPost('next_end_time');
                $arr['cycle_type'] = $this->_request->getPost('cycle_type');
                $arr['cycle_week'] = $this->_request->getPost('cycle_week');
                $arr['cycle_month'] = $this->_request->getPost('cycle_month');
                switch ($arr['cycle_type']) {
                    case 2:
                        unset($arr['cycle_month']);
                        break;
                    case 3:
                        unset($arr['cycle_week']);
                        break;
                    default:
                        unset($arr['cycle_month'], $arr['cycle_week']);
                }
            }
            $userinfo = $this->account->getAccountInfoByID($uid);
            $arr['checkpass'] = self::setTaskaudit($userinfo);

            if (!empty($_POST['fid'])) {
                $arr['fid'] = $_POST['fid'];
            } else {
                $arr['fid'] = 0;
            }

            $gnames = rtrim($_POST['gnames'], ',');
			$mailbox_str = "";
			//检索出所有人或所有组里的mailbox
            if ($gnames != '') {

            if ($gnames == 'all') {
                $arr['groups'] = $gnames;
				$mailbox_arr = $this->dbAdapter->fetchAll("select mailbox from mr_subscriber");
				foreach( $mailbox_arr as $mk => $mv){
					$mailbox_str .= $mailbox_arr[$mk]['mailbox'] . ",";
				}
				$mailboxs = explode("," , trim( $mailbox_str ,","));
            } else {
                $groups = explode(",", $gnames);
                if ($groups) {
                    foreach ($groups as $val) {
                        $where = " gname = '" . $val . "' ";
                        $result = $this->group->getAllGroupInfo($where);
                        $groupid .= $result[0]['id'] . ",";
                    }
                    $arr['groups'] = rtrim($groupid, ',');
                }
				$group_arr = explode(",", $arr['groups']);
				foreach ( $group_arr as $g){
					$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id = '" . $g . "' ");
					$mailbox_arr = $this->dbAdapter->fetchAll("select mailbox from " . $tablename );
					
					foreach( $mailbox_arr as $mk => $mv){
						$mailbox_str .= $mailbox_arr[$mk]['mailbox'] . ",";
					}
					
				}
				$mailboxs = explode("," , trim( $mailbox_str ,","));
				$mailboxs = array_flip($mailboxs); //去重
				$mailboxs = array_keys($mailboxs); //取键名即邮箱地址
            }
			$mailbox_str2 = implode("','" , $mailboxs);
        }
						
			//使用筛选器过滤,获取过滤后地址数
			$condition = "1=1";
			if($arr['fid'] != 0){
				$condition = $this->dbAdapter->fetchOne("select `condition` from mr_filter where id =" .$arr['fid'] );
			}
			$tnum = $this->dbAdapter->fetchOne("select count(id) from mr_subscriber where mailbox in( '" . $mailbox_str2 . "') and " . $condition . "");
           
			//判断有无手动输入的地址，计算总地址数
            $email_list = $this->_request->getPost('email_list');
            if (!empty($email_list)) {
                $arr['receivers'] = rtrim($email_list, ',');
                $arr['total'] = $tnum + count(explode(',', $arr['receivers']));
            } else {
                $arr['total'] = $tnum;
				$arr['receivers']='';
            }
            if ($_POST['random'] == null) {
                $arr['random'] = 0;
            } else {
                $arr['random'] = $this->_request->getPost('random');
            }

            if ($_POST['is_insert_ad'] == null) {
                $arr['is_insert_ad'] = 0;
            } else {
                $arr['is_insert_ad'] = $_POST['is_insert_ad'];
            }

            //判断是否存为草稿
            $draft = $this->_request->getPost('draft');
            $testemail = $this->_request->getPost('test_email');
            if ($testemail != "") {
                $arr['testmailbox'] = $testemail;
            }
            if (empty($_POST['taskId'])) {
                $arr['createtime'] = $this->_request->getPost('createtime');
                if ($draft == 1) {
                    $arr['draft'] = 1;
                    if ($arr['checkpass'] == 3) {
                        $arr['status'] = 2;
                    }
                    $rows = $this->dbAdapter->insert('mr_task', $arr);
                    if ($rows) {
                        $tid = $this->dbAdapter->lastInsertId();
                        self::addattachment($path, $filename, $uid, $tid);
                        $description = "该用户将新建任务存稿，任务名称为：" . $arr['task_name'];
                        $description_en = "The user will be the new task manuscripts, the task name is: " . $arr['task_name'];
                        $operationName = '保存任务到草稿';
                        $operationName_en = 'Save task to draft';
                        self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
                        $this->_redirect('/task/drafttask');
                    }
                } else {
                    //如果id为空获取到的数据插入数据库
                    if ($arr['checkpass'] == 3) {
                        $arr['status'] = 2;
                    } else {
                        $arr['status'] = 1;
                    }
                    $rows = $this->dbAdapter->insert('mr_task', $arr);
                    if ($rows) {
                        $tid = $this->dbAdapter->lastInsertId();
                        self::addattachment($path, $filename, $uid, $tid);
                        if ($arr['checkpass'] == 3 && $arr['sendtype'] == 2) {
                            $arrPlan = array('status' => $arr['status'], 'cycle_type' => $arr['cycle_type'], 'cycle_time' => $arr['cycle_time'], 'cycle_end_time' => $arr['cycle_end_time'], 'cycle_week' => $arr['cycle_week'], 'cycle_month' => $arr['cycle_month']);
                            switch ($arrPlan['cycle_type']) {
                                case 2:
                                    unset($arrPlan['cycle_month']);
                                    break;
                                case 3:
                                    unset($arrPlan['cycle_week']);
                                    break;
                                default:
                                    unset($arrPlan['cycle_month'], $arrPlan['cycle_week']);
                            }
                            self::setCycleTask($tid, $arrPlan);
                        }
						$description = "该用户提交新建任务，任务名称为: " . $arr['task_name'];
						$description_en = "The user submits a new task, the task name is " . $arr['task_name'];
						$operationName = '提交新建任务';
						$operationName_en = 'submit a new task';
						self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
                        $this->_redirect('/task/listtask');
                    }
                }
            } else {
                //如果id不为空则根据所获得数据修改数据库相关内容
                $taskId = $this->_request->getPost('taskId');
                $arr['modifiedtime'] = $this->_request->getPost('modifiedtime');
                //判断是否存为草稿
                if ($draft == 1) {
                    $arr['draft'] = 1;
                    if ($arr['checkpass'] == 3) {
                        $arr['status'] = 2;
                    }
                    $rows = $this->dbAdapter->update('mr_task', $arr, 'id=' . $taskId);
                    if ($rows) {
                        $tid = $taskId;
                        self::editattachment($path, $filename, $uid, $tid);
                        $description = "该用户在编辑任务后存稿，任务名为: " . $arr['task_name'];
                        $description_en = "The user is editing tasks save drafts,the task name is " . $arr['task_name'];
                        $operationName = '编辑任务';
                        $operationName_en = 'edit task';
                        self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
                        $this->_redirect('/task/drafttask');
                    }
                } else {
                    $arr['draft'] = 0;
                    if ($arr['checkpass'] == 3) {
                        $arr['status'] = 2;
                    } else {
                        $arr['status'] = 1;
                    }
                    $rows = $this->dbAdapter->update('mr_task', $arr, 'id=' . $taskId);
                    if ($rows) {
                        $tid = $taskId;
                        self::editattachment($path, $filename, $uid, $tid);
                        if ($arr['checkpass'] == 3 && $arr['sendtype'] == 2) {
                            $arrPlan = array('status' => $arr['status'], 'cycle_type' => $arr['cycle_type'], 'cycle_time' => $arr['cycle_time'], 'cycle_end_time' => $arr['cycle_end_time'], 'cycle_week' => $arr['cycle_week'], 'cycle_month' => $arr['cycle_month']);
                            switch ($arrPlan['cycle_type']) {
                                case 2:
                                    unset($arrPlan['cycle_month']);
                                    break;
                                case 3:
                                    unset($arrPlan['cycle_week']);
                                    break;
                                default:
                                    unset($arrPlan['cycle_month'], $arrPlan['cycle_week']);
                            }
                            self::setCycleTask($tid, $arrPlan);
                        }
                        $description = "该用户在编辑任务后提交，任务名为：" . $arr['task_name'];
                        $description_en = "The user submitted in the encoding task,the task name is: " . $arr['task_name'];
                        $operationName = '编辑任务';
                        $operationName_en = 'edit task';
                        self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
                        $this->_redirect('/task/listtask');
                    }
                }
            }
        }
    }


    /*任务列表*/
    function listtaskAction()
    {
		$uid = $this->getCurrentUserID();
        $uname = $this->getcurrentuser();
        $role = $this->getCurrentUserRole();
		$num = $_GET['num'] ? $_GET['num'] : 10;
		if ($num == "" || $num == null) {
			$num = 10;
		}
		$this->Smarty->assign ("anum", $num);
		$curpage = $_GET['curpage'];
		if ($curpage == "" || $curpage == null) {
			$curpage = 1;
		}
        $wh = "";
		if( $role == 'stasker'){
			$wh='  1=1 and uid > 1';
		}else{
			$wh=self::checkLoginAudit($role);
		}
		if($role == 'sadmin'){
			$userinfo = $this->account->getAllUsers();			
		}elseif($role == 'admin'){
			$userinfo = $this->account->getAdminUsers($uid);			
		}elseif($role == 'stasker'){
			$userinfo = $this->account->getSTaskerUsers($uid);	
		}else{
			$userinfo = $this->account->getTaskerUsers($uid);	
		}
        if ($role == 'sadmin' or $role == 'admin') {
            $statusdata = array(1 => '待审核', 2 => '准备发送', 3 => '发送中', 4 => '发送成功', 5 => '停止', 6 => '审核失败', 7 => '发送失败', 8 => '部分失败');
        } elseif ($role == 'stasker') {
			$statusdata = array(1 => '待审核', 9 => '审核中', 2 => '准备发送', 3 => '发送中', 4 => '发送成功', 5 => '停止', 6 => '审核失败', 7 => '发送失败', 8 => '部分失败'); 
        } else{
            $statusdata = array(1 => '审核中', 2 => '准备发送', 3 => '发送中', 4 => '发送成功', 5 => '停止', 6 => '审核失败', 7 => '发送失败', 8 => '部分失败');
        }		
        $task_name = str_replace(array("'", "_"), array('', "\_"), $_GET['taskname'] );
        $cid = $_GET['cid'];
        $status = $_GET['status'];
        $username = str_replace(array("'", "_"), array('', "\_"), $_GET['uname'] );
        $subject = str_replace(array("'", "_"), array('', "\_"), $_GET['subject'] );
        $where = "";
		$parameter = "";
		
        if (isset($task_name) && !empty($task_name)) {
            if (empty($where)) {
                $where = " and task_name like '%" . $task_name . "%' ";
            } else {
                $where .= " and task_name like '%" . $task_name . "%' ";
            }
			$parameter .= "&task_name=".$task_name;
			$this->Smarty->assign ("task_name", stripslashes($task_name));
        }
        
        if (isset($cid) && !empty($cid)) {
            if (empty($where)) {
                $where = " and cid = " . $cid . " ";
            } else {
                $where .= " and cid = " . $cid . " ";
            }
			$parameter .= "&cid=".$cid;
			$this->Smarty->assign ("cid", $cid);
        }

        if (isset($username) && !empty($username)) {
            $id = $this->dbAdapter->fetchOne("select id from mr_accounts where username = '" . $username . "' ");
            if ($id) {
                if (empty($where)) {
                    $where = " and uid = '" . $id . "'";
                } else {
                    $where .= " and uid = '" . $id . "'";
                }
            } else {
                if (empty($where)) {
                    $where = " and uid = ''";
                } else {
                    $where .= " and uid = ''";
                }
            }
			$parameter .= "&username=".$username;
			$this->Smarty->assign ("username", stripslashes($username));
        }

        if (isset($subject) && !empty($subject)) {
            if (empty($where)) {
                $where = " and subject like '%" . $subject . "%' ";
            } else {
                $where .= " and subject like '%" . $subject . "%' ";
            }
			$parameter .= "&subject=".$subject;
			$this->Smarty->assign ("subject", stripslashes($subject));
        }

        if (isset($status) && !empty($status) && $status != "all") {
            if ($role == 'admin') {
                if ($_GET['status'] == 9) {
                    $status = 1;
                    if (empty($where)) {
                        $where = " and status = " . $status . " and uid = " . $uid . " ";
                    } else {
                        $where .= " and status = " . $status . " and uid = " . $uid . " ";
                    }
                } elseif ($_GET['status'] == 1) {
                    if (empty($where)) {
                        $where = " and status = " . $status . " and uid <> " . $uid . " ";
                    } else {
                        $where .= " and status = " . $status . " and uid <> " . $uid . " ";
                    }
                } else {
                    if (empty($where)) {
                        $where = " and status = " . $status . " ";
                    } else {
                        $where .= " and status = " . $status . " ";
                    }
                }
            } elseif ($role == 'stasker') {
                if ($_GET['status'] == 9) {
                    $status = 1;
                    if (empty($where)) {
                        $where = " and status = " . $status . " and uid = " . $uid . " ";
                    } else {
                        $where .= " and status = " . $status . " and uid = " . $uid . " ";
                    }
                } elseif ($_GET['status'] == 1) {
                    if (empty($where)) {
                        $where = " and status = " . $status . " and uid <> " . $uid . " ";
                    } else {
                        $where .= " and status = " . $status . " and uid <> " . $uid . " ";
                    }
                } else {
                    if (empty($where)) {
                        $where = " and status = " . $status . " ";
                    } else {
                        $where .= " and status = " . $status . " ";
                    }
                }
            } else {
                if (empty($where)) {
                    $where = " and status = " . $status . " ";
                } else {
                    $where .= " and status = " . $status . " ";
                }
            }
			$parameter .= "&status=".$status;
			$this->Smarty->assign("status", $status);
        }
		$settime = $_GET['settime'];
		$sendtime1 = $_GET['sendtime1'];
		$sendtime2 = $_GET['sendtime2'];
		if ($settime != "" && $settime != null) {
			if ($settime == "day") {
				$day = date("Y-m-d", time());
				if ($where == "") {
					$where = "and (createtime like '%".$day."%' or createtime='0000-00-00 00:00:00')";
				} else {
					$where .= "and (createtime like '%".$day."%' or createtime='0000-00-00 00:00:00')";
				}
			} else if ($settime == "month") {
				$month = date("Y-m", time());
				if ($where == "") {
					$where = "and (createtime like '%".$month."%' or createtime='0000-00-00 00:00:00')";
				} else {
					$where .= "and (createtime like '%".$month."%' or createtime='0000-00-00 00:00:00')";
				}
			} else {
				$sendtime1_time = null;
				$sendtime2_time = null;
				$today_inscope = false;
				$now_date = strtotime("now");
				
				if ($sendtime1 != "" && $sendtime1 != null) {
					$sendtime1_time = strtotime($sendtime1);
					if ($sendtime1_time > $now_date) {
						$today_inscope = true;
					}
				}
				if ($sendtime2 != "" && $sendtime2 != null) {
					$sendtime2_time = strtotime($sendtime2);
					if ($sendtime1_time > $sendtime2_time) {
						$today_inscope = true;
					}
				}
				$time_sql = '';
				if ($sendtime1 != "" && $sendtime1 != null && $sendtime2 != "" && $sendtime2 != null) {
					$time_sql = "(createtime>='".$sendtime1."' ";
					$parameter .= "&sendtime1=".$sendtime1;
					$this->setSimpleSearchKey ("sendtime1", $sendtime1);
					$this->Smarty->assign ("sendtime1", $sendtime1);
					
					$time_sql .= "and createtime<='".$sendtime2."')";
					$parameter .= "&sendtime2=".$sendtime2;
					$this->setSimpleSearchKey ("sendtime2", $sendtime2);
					$this->Smarty->assign ("sendtime2", $sendtime2);
				} else if ($sendtime1 != "" && $sendtime1 != null) {
					$time_sql .= "createtime>='".$sendtime1."'";
					$parameter .= "&sendtime1=".$sendtime1;
					$this->setSimpleSearchKey ("sendtime1", $sendtime1);
					$this->Smarty->assign ("sendtime1", $sendtime1);
				} else if ($sendtime2 != "" && $sendtime2 != null) {
					$time_sql .= "createtime<='".$sendtime2."'";
					$parameter .= "&sendtime2=".$sendtime2;
					$this->setSimpleSearchKey ("sendtime2", $sendtime2);
					$this->Smarty->assign ("sendtime2", $sendtime2);
				}
				if ($today_inscope) {
					$time_sql .= " and createtime='0000-00-00 00:00:00'";
				}
				if (strlen($time_sql) > 0) {
					if ($where == "") {
						$where = "and (".$time_sql.") ";
					} else {
						$where .= "and (".$time_sql.") ";
					}
				} 
			}
			$parameter .= "&settime=".$settime;
			$this->Smarty->assign ("settime", $settime);
		} else {
			$day = date("Y-m-d", time());
			if ($where == "") {
				$where = "and (createtime like '%".$day."%' or createtime='0000-00-00 00:00:00')";
			} else {
				$where .= "and (createtime like '%".$day."%' or createtime='0000-00-00 00:00:00')";
			}
			$parameter .= "&settime=day";
			$this->Smarty->assign ("settime", "day");
		}
		
		//列表页分页
        if (isset($where) && !empty($where)) {
            $where .= " and draft<>1 and ". $wh;
        } else {
            $where = "and draft<>1 and ". $wh;
        }
        $total = $this->task->getAllCountTask($where);
		//$page = new Page($total, $num, "");
		$page = new Page ($total, $num, $parameter);
        if ($curpage > $page->pageNum) {
			$curpage = 1;
			$page->page = 1;
			$page->limit = "limit 0, ".$num;
		}
        $where .= " order by createtime desc "."{$page->limit}";
        $taskdata = $this->task->selectAllTask($where);
        if (!empty($taskdata)) {
            foreach ($taskdata as &$taskval) {
                foreach ($userinfo as $vals) {
                    if ($taskval['uid'] == $vals['id']) {
                        $taskval['username'] = $vals['username'];
                        $taskval['role'] = $vals['role'];
                    }
                }
				// $statistics=new Statistics();
				// $realnum=$statistics->getSendNum("SELECT count(id) as num FROM `mr_task` WHERE id = ".$taskval['id']."");
				// if($realnum != 0){
					// $taskval['total']=$realnum;
				// }
            }
            foreach ($taskdata as &$val) {
                switch ($val['status']) {
                    case 1:
                        if ($val['checkpass'] == 3) {
                            $val['taskstatus'] = "准备发送";
                        } elseif ($val['role'] == 'sadmin' or $val['role'] == 'admin') {
                            $val['taskstatus'] = "准备发送";
                        } elseif ($val['role'] == 'stasker' && $role == 'stasker') {
                            $val['taskstatus'] = "审核中";
                        } elseif ($val['role'] == 'tasker' && $role == 'tasker') {
                            $val['taskstatus'] = "审核中"; 
						} elseif ($val['role'] == 'tasker' && $role == 'stasker') {
                            $val['taskstatus'] = "审核中";
                        } else {
                            $val['taskstatus'] = "待审核";
                        }
                        break;
                    case 2:
                        $val['taskstatus'] = "准备发送";
                        break;
                    case 3:
                        $val['taskstatus'] = "发送中";
                        break;
                    case 4:
                        $val['taskstatus'] = "发送成功";
                        break;
                    case 5:
                        $val['taskstatus'] = "停止";
                        break;
                    case 6:
                        $val['taskstatus'] = "<span style='color:red'>审核失败</span>";
                        break;
                    case 7:
                        $val['taskstatus'] = "<span style='color:blue'>发送失败</span>";
                        break;
                    case 8:
                        $val['taskstatus'] = "部分失败";
                        break;
                }
            }
        }
		
		$mode = $_GET['mode'];
		if( $mode == "search"){
			$userid = $this->getCurrentUserID();
			$description='该用户进行查询任务列表的操作';
			$description_en='The user performs the operation of querying task list';
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询操作', $description, 'Query operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		}
        //搜索中的任务分类
		$catdata = $this->vocation->getAllTpls($uid);
		$catdata = $this->vocation->selectAllType($wh);
        $this->Smarty->assign('role', $role);
        $this->Smarty->assign('num', $num);
        $this->Smarty->assign("page", $page->fpage());
        $this->Smarty->assign("statusdata", $statusdata);
        $this->Smarty->assign("catdata", $catdata);
        $this->Smarty->assign("taskdata", $taskdata);
        $this->Smarty->assign("li_menu", "listtask");
        $this->Smarty->display('tasklist.php');
    }

	public function checkLoginAudit($role) {
		$uid = $this->getCurrentUserID();
		$where='';
		if($role == 'sadmin') {
			$where=' 1=1 ';
		} elseif($role == 'admin') {
			$userinfo = $this->account->getAdminUsers($uid);
			 if(!empty($userinfo)) {
                foreach ($userinfo as $val) {
                    $id[] = $val['id'];
                }
            }
            $str = implode(",", $id);
            $where = " 1=1 and uid in(" . $str . ") ";
		}elseif($role == 'stasker'){
			$userinfo = $this->account->getSTaskerUsers($uid);
			 if(!empty($userinfo)) {
                foreach ($userinfo as $val) {
                    $id[] = $val['id'];
                }
				$str = implode(",", $id);
				$where = " 1=1 and uid in(" . $str . ") ";
            }
		}else{
			$where=" 1=1 and uid = ".$uid." ";
		}
		return $where;
	}

    /*任务分类*/
    function typetaskAction()
    {
        $num = $_GET['num'] ? $_GET['num'] : 10;
        $uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
        $typename = mysql_escape_string( $_GET['typename'] );
        $description = mysql_escape_string( $_GET['description'] );
		$where=self::checkLoginAudit($role);
		$wh=$where;
		if($role == 'sadmin'){
			$userinfo = $this->account->getAllUsers();			
		}elseif($role == 'admin'){
			$userinfo = $this->account->getAdminUsers($uid);			
		}elseif($role == 'stasker'){
			$userinfo = $this->account->getSTaskerUsers($uid);	
		}else{
			$userinfo = $this->account->getTaskerUsers($uid);	
		}
		$parameter = "";
		$parameter = "&num=".$num;
		$this->Smarty->assign('setnum',$num);
		
		if (isset($typename) && !empty($typename)) {
			$where .= " and vocation_name like '%" . $typename . "%' ";
			$parameter .= "&vocation_name=".$typename;
		}
		if (isset($description) && !empty($description)) {
			$where .= " and vocation_body like '%" . $description . "%' ";
			$parameter .= "&vocation_body=".$description;
		}
		
		$this->Smarty->assign("typename",stripslashes($typename));
		$this->Smarty->assign("description", stripslashes($description));
		
        $total = $this->vocation->getAllCountType($where);
        $page = new Page($total, $num, $parameter);
        $where .= " order by id desc {$page->limit}";
				
        $catdata = $this->vocation->selectAllType($where);
        if (!empty($catdata)) {
            foreach ($catdata as &$val) {
                $cid = $val['id'];
                $val['draftnum'] = self::typedrafttask($val['id'], $wh);
                $val['tasknum'] = self::typecounttask($val['id'], $wh);
                $val['total'] = $val['draftnum'] + $val['tasknum'];
				if($val['uid'] == $uid){
					$val['mine'] = 1;
				}else{
					$val['mine'] = 0;
				}
				foreach ($userinfo as $vals) {
					//var_dump($val['uid']);
                     if ($val['uid'] == $vals['id']) {
                        $val['username'] = $vals['username'];
                    } 
                }
            }
			unset($val);
			unset($vals);
        }
		
		$search = $_GET['search'];
		if($search){
			$style=$this->_request->get('style');
			if( empty($style) ){
				$description='该用户进行查询任务分类的操作';
				$description_en='The user performs the operation of querying task classification';
				BehaviorTrack::addBehaviorLog($uname, $role, $uid, '查询操作', $description, 'Query operation', $description_en, $_SERVER["REMOTE_ADDR"]);
			}
		}
		$this->Smarty->assign('search', $search);
        $this->Smarty->assign('num', $num);
        $this->Smarty->assign("page", $page->fpage());
        $this->Smarty->assign("catdata", $catdata);
        $this->Smarty->assign("li_menu", "typetask");
        $this->Smarty->display('tasktype.php');
    }

    //统计任务分类下的任务草稿数量
    function typedrafttask($cid, $where)
    {
        $draftnum = $this->dbAdapter->fetchOne("select count(id) from `mr_task` where cid = " . $cid . " and draft = 1 and " . $where . "");
        return $draftnum;
    }

    //统计任务分类下的任务数量
    function typecounttask($cid, $where)
    {
        $tasknum = $this->dbAdapter->fetchOne("select count(id) from `mr_task` where cid = " . $cid . " and draft = 0 and " . $where . "");
        return $tasknum;
    }


    //任务编辑
    function edittaskAction()
    {
        $uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$condition=self::checkLoginAudit($role);
       
       // $where = " uid = " . $uid . "";
        $allGroups = $this->group->getAllGroupInfo($condition);
        if (!empty($allGroups)) {
            foreach ($allGroups as $g_k => $g_v) {
                $total_person = $this->group->getAllCountperson($g_v['tablename']);
                $allGroups[$g_k]['count'] = $total_person['num'];
                $countAll += $total_person['num'];
            }
        }
        //获取模板分类
        $this->Smarty->assign('countAll', $countAll);
        $this->Smarty->assign('groups', $allGroups);
        $tid = $this->_request->get('id');
        $eidtdata = $this->task->getOnetask($tid);
        $uid = $eidtdata['uid'];
        $cid = $eidtdata['cid'];
        $fid = $eidtdata['fid'];
        $task_name = $eidtdata['task_name'];
        $modifiedtime = date('Y-m-d H:i:s', time());
        $random = $eidtdata['random'];
        $draft = $eidtdata['draft'];
        $subject = $eidtdata['subject'];
		$domainAD = $eidtdata['domainAD'];
		$is_insert_ad = $eidtdata['is_insert_ad'];
        $content = $eidtdata['data'];
        $total = $eidtdata['total'];
        $receivers = $eidtdata['receivers'];
        $testmailbox = $eidtdata['testmailbox'];
        $sendtype = $eidtdata['sendtype'];
        $sendtime = $eidtdata['sendtime'];
        $cycle_time = substr($eidtdata['cycle_time'], 0, 5);
        $cycle_end_time = $eidtdata['cycle_end_time'];
        $cycle_type = $eidtdata['cycle_type'];
        $cycle_week = $eidtdata['cycle_week'];
        $cycle_month = $eidtdata['cycle_month'];
        $sender = $eidtdata['sender'];
        $sendemail = $eidtdata['sendemail'];
        $replyemail = $eidtdata['replyemail'];
		$isreport = $eidtdata['isreport'];
		$reportemail = $eidtdata['reportemail'];
        $w = '';
        if ($eidtdata['fid'] != 0) {
            $filter = $this->filter->getOnefilter($eidtdata['fid']);
            $w = " where " . $filter[0]['condition'];
        }
		if ($eidtdata['groups'] == 'all') {
			$gnames[0] = $eidtdata['groups'];
            $groupIds = $eidtdata['groups'];
		}else{
			if($eidtdata['groups'] != ''){
				$where = " id in(" . $eidtdata['groups'] . ") and ".$condition."";
				$groupinfo = $this->group->getAllGroupInfo($where);
				foreach ($groupinfo as $val) {
					if (!empty($val['tablename'])) {
						$groupIds .= $val['gname'] . ",";
						$total_person = $this->group->getCountfilterGroup($val['tablename'], $w);
						$tnum += $total_person;
					}
				}
				unset($val);
				if ($groupIds) {
					$gnames = explode(",", $groupIds);
					array_pop($gnames);
				}
			}else{
				$gnames = null;
			}
		}
        $this->Smarty->assign('gnames', $gnames);
        $this->Smarty->assign('count', $total);

        if ($sendtype == 2) {
            if ($cycle_type == 2) {
                $cycle_month = "";
            } elseif ($cycle_type == 3) {
                $cycle_week = "";
            } else {
                $cycle_month = "";
                $cycle_week = "";
            }
        } else {
            $cycle_end_time = "";
            $cycle_type = "";
            $cycle_week = "";
            $cycle_month = "";
        }
        //判断星期几
        $cycle_week_data = array(1 => '周一', 2 => '周二', 3 => '周三', 4 => '周四', 5 => '周五', 6 => '周六', 7 => '周日', 15 => '周一至周五', 67 => '周六至周日');
        $cycle_month_data = array(1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8', 9 => '9', 10 => '10', 11 => '11', 12 => '12', 13 => '13', 14 => '14', 15 => '15', 16 => '16', 17 => '17', 18 => '18', 19 => '19', 20 => '20', 21 => '21', 22 => '22', 23 => '23', 24 => '24', 25 => '25', 26 => '26', 27 => '27', 28 => '28', 29 => '29', 30 => '30', 31 => '31');

        //获取模板分类
        $catdata = $this->vocation->selectAllType($condition);
        if ($catdata) {
            $this->Smarty->assign('rows', $catdata);
        }
        
        $attachmentdata = $this->attachment->seltaskattach($tid, $uid);
        foreach ($attachmentdata as &$val) {
            $id = $val['id'];
            $path = $val['path'];
            $truename = $val['truename'];
            $filepath .= $val['path'] . ',';
            $filename .= $val['truename'] . ',';
            $files[] = $val['truename'];
        }
		unset($val);
        $attachs = $this->attachment->searchTaskattach($condition);
        if ($fid != null) {
            $filterone = $this->filter->getOnefilter($fid);
        }
        $filters = $this->filter->selectAllFilter($condition);
		//订阅
        $usersubscription = $this->dbAdapter->fetchAll("select * from mr_usersubscription where ".$condition);
		if (!empty($usersubscription)) {
            foreach ($usersubscription as $key => $val) {
                $string = $this->randomkeys(1)."#". $val['id'] ."#". $this->randomkeys(3);
                $des = new Des("lg!@2015");
                $usersubscription[$key]['key'] = urlencode($des->encrypt($string));
            }
			unset($val);
        }
        $this->Smarty->assign("usersubscription", $usersubscription);
		
		//插入变量
		$basic_variable = $this->dbAdapter->fetchAll("select showname,name from mr_group_extension where hidden=1");
		$define_variable = $this->dbAdapter->fetchAll("select showname,name from mr_group_extension where hidden=0");
		$this->Smarty->assign("basic_variable",$basic_variable);
		$this->Smarty->assign("define_variable",$define_variable);
		
        $this->Smarty->assign("taskId", $tid);
        $this->Smarty->assign("uid", $uid);
        $this->Smarty->assign("cid", $cid);
        $this->Smarty->assign("groupIds", $groupIds);
        $this->Smarty->assign("tnum", $tnum);
        $this->Smarty->assign("task_name", $task_name);
        $this->Smarty->assign("modifiedtime", $modifiedtime);
        $this->Smarty->assign("random", $random);
        $this->Smarty->assign("draft", $draft);
        $this->Smarty->assign("subject", $subject);
		$this->Smarty->assign("domainAD", $domainAD);
		$this->Smarty->assign("is_insert_ad", $is_insert_ad);
        $this->Smarty->assign("content", $content);
        $this->Smarty->assign("receivers", $receivers);
        $this->Smarty->assign("testmailbox", $testmailbox);
        $this->Smarty->assign("sendtype", $sendtype);
        $this->Smarty->assign("sendtime", $sendtime);
        $this->Smarty->assign("cycle_time", $cycle_time);
        $this->Smarty->assign("cycle_end_time", $cycle_end_time);
        $this->Smarty->assign("cycle_week_data", $cycle_week_data);
        $this->Smarty->assign("cycle_month_data", $cycle_month_data);
        $this->Smarty->assign("cycle_week", $cycle_week);
        $this->Smarty->assign("cycle_type", $cycle_type);
        $this->Smarty->assign("cycle_month", $cycle_month);
        $this->Smarty->assign("sender", $sender);
        $this->Smarty->assign("sendemail", $sendemail);
        $this->Smarty->assign("replyemail", $replyemail);
        $this->Smarty->assign("isreport", $isreport);
        $this->Smarty->assign("reportemail", $reportemail);
        $this->Smarty->assign("filepath", $filepath);
        $this->Smarty->assign("filename", $filename);
        $this->Smarty->assign("attachmentdata", $attachmentdata);
        $this->Smarty->assign("files", $files);
        $this->Smarty->assign("attachs", $attachs);
        $this->Smarty->assign("catdata", $catdata);
        $this->Smarty->assign("filterone", $filterone);
        $this->Smarty->assign("filtersId", $filterone[0]['id']);
        $this->Smarty->assign("filters", $filters);

        $url_page = $_SERVER['HTTP_REFERER'];
        $this->Smarty->assign("url_page", $url_page);
        $this->Smarty->assign("li_menu", "drafttask");
        $this->Smarty->display('edittask.php');
    }

    function viewtaskAction()
    {
        $userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$condition=self::checkLoginAudit($role);
        
       // $where = " userid = " . $userid . "";
        $allGroups = $this->group->getAllGroupInfo($condition);
        if (!empty($allGroups)) {
            foreach ($allGroups as $g_k => $g_v) {
                $total_person = $this->group->getAllCountperson($g_v['tablename']);
                $allGroups[$g_k]['count'] = $total_person['num'];
                $countAll += $total_person['num'];
            }
        }
        //获取模板分类
        $this->Smarty->assign('countAll', $countAll);
        $this->Smarty->assign('groups', $allGroups);
		$this->Smarty->assign('login', $role);

        $tid = $this->_request->get('id');
        $eidtdata = $this->task->getOnetask($tid);
        //var_dump($eidtdata);exit;
        $uid = $eidtdata['uid'];
		if( $userid == $uid ){
			$mine = 1;
		}
        $cid = $eidtdata['cid'];
        $fid = $eidtdata['fid'];
        $task_name = $eidtdata['task_name'];
        $createtime = date('Y-m-d H:i:s', time());
        $random = $eidtdata['random'];
        $draft = $eidtdata['draft'];
        $subject = $eidtdata['subject'];
		$domainAD = $eidtdata['domainAD'];
		$is_insert_ad = $eidtdata['is_insert_ad'];
        $content = $eidtdata['data'];
        $total = $eidtdata['total'];
        $receivers = $eidtdata['receivers'];
        $testmailbox = $eidtdata['testmailbox'];
        $sendtype = $eidtdata['sendtype'];
        $sendtime = $eidtdata['sendtime'];
        $cycle_time = substr($eidtdata['cycle_time'], 0, 5);
        $cycle_end_time = $eidtdata['cycle_end_time'];
        $cycle_type = $eidtdata['cycle_type'];
        $cycle_week = $eidtdata['cycle_week'];
        $cycle_month = $eidtdata['cycle_month'];
        $sender = $eidtdata['sender'];
        $sendemail = $eidtdata['sendemail'];
        $replyemail = $eidtdata['replyemail'];
		$isreport = $eidtdata['isreport'];
		$reportemail = $eidtdata['reportemail'];
        $w = '';
        if ($eidtdata['fid'] != 0) {
            $filter = $this->filter->getOnefilter($eidtdata['fid']);
            $w = " where " . $filter[0]['condition'];
        }
		if ($eidtdata['groups'] == 'all') {
			$gnames[0] = $eidtdata['groups'];
            $groupIds = $eidtdata['groups'];
		}else{
			if($eidtdata['groups'] != ''){
				$where = " id in(" . $eidtdata['groups'] . ") and ".$condition."";
				$groupinfo = $this->group->getAllGroupInfo($where);
				foreach ($groupinfo as $val) {
					if (!empty($val['tablename'])) {
						$groupIds .= $val['gname'] . ",";
						$total_person = $this->group->getCountfilterGroup($val['tablename'], $w);
						$tnum += $total_person;
					}
				}
				unset($val);
				if ($groupIds) {
					$gnames = explode(",", $groupIds);
					array_pop($gnames);
				}
			}else{
				$gnames = null;
			}	
		}
        $this->Smarty->assign('gnames', $gnames);
        $this->Smarty->assign('count', $total);
		
        if ($sendtype == 2) {
            if ($cycle_type == 2) {
                $cycle_month = "";
            } elseif ($cycle_type == 3) {
                $cycle_week = "";
            } else {
                $cycle_month = "";
                $cycle_week = "";
            }
        } else {
            $cycle_end_time = "";
            $cycle_type = "";
            $cycle_week = "";
            $cycle_month = "";
        }
        //判断星期几
        $cycle_week_data = array(1 => '周一', 2 => '周二', 3 => '周三', 4 => '周四', 5 => '周五', 6 => '周六', 7 => '周日', 15 => '周一至周五', 67 => '周六至周日');
        $cycle_month_data = array(1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8', 9 => '9', 10 => '10', 11 => '11', 12 => '12', 13 => '13', 14 => '14', 15 => '15', 16 => '16', 17 => '17', 18 => '18', 19 => '19', 20 => '20', 21 => '21', 22 => '22', 23 => '23', 24 => '24', 25 => '25', 26 => '26', 27 => '27', 28 => '28', 29 => '29', 30 => '30', 31 => '31');

        //获取模板分类
        $catdata = $this->vocation->selectAllType($condition);
        if ($catdata) {
            $this->Smarty->assign('rows', $catdata);
        }
        
        $attachmentdata = $this->attachment->seltaskattach($tid, $uid);
        foreach ($attachmentdata as &$val) {
            $id = $val['id'];
            $path = $val['path'];
            $truename = $val['truename'];
            $filepath .= $val['path'] . ',';
            $filename .= $val['truename'] . ',';
            $files[] = $val['truename'];
        }
		unset($val);
        $attachs = $this->attachment->searchTaskattach($condition);
        if ($fid != null) {
            $filterone = $this->filter->getOnefilter($fid);
        }
        $filters = $this->filter->selectAllFilter($condition);
		//订阅
        $usersubscription = $this->dbAdapter->fetchAll("select * from mr_usersubscription where ".$condition);
        if (!empty($usersubscription)) {
            foreach ($usersubscription as $key => $val) {
                $string = $this->randomkeys(1)."#". $val['id'] ."#". $this->randomkeys(3);
                $des = new Des("lg!@2015");
                $usersubscription[$key]['key'] = urlencode($des->encrypt($string));
            }
			unset($val);
        }
		$this->Smarty->assign("usersubscription",$usersubscription);
		
		//插入变量
		$basic_variable = $this->dbAdapter->fetchAll("select showname,name from mr_group_extension where hidden=1");
		$define_variable = $this->dbAdapter->fetchAll("select showname,name from mr_group_extension where hidden=0");
		$this->Smarty->assign("basic_variable",$basic_variable);
		$this->Smarty->assign("define_variable",$define_variable);
		
        $this->Smarty->assign("taskId", $tid);
        $this->Smarty->assign("uid", $uid);
        $this->Smarty->assign("cid", $cid);
        $this->Smarty->assign("groupIds", $groupIds);
        $this->Smarty->assign("tnum", $tnum);
        $this->Smarty->assign("task_name", $task_name);
        $this->Smarty->assign("createtime", $createtime);
        $this->Smarty->assign("random", $random);
        $this->Smarty->assign("draft", $draft);
        $this->Smarty->assign("subject", $subject);
		$this->Smarty->assign("domainAD", $domainAD);
		$this->Smarty->assign("is_insert_ad", $is_insert_ad);
        $this->Smarty->assign("content", $content);
        $this->Smarty->assign("receivers", $receivers);
        $this->Smarty->assign("testmailbox", $testmailbox);
        $this->Smarty->assign("sendtype", $sendtype);
        $this->Smarty->assign("sendtime", $sendtime);
        $this->Smarty->assign("cycle_time", $cycle_time);
        $this->Smarty->assign("cycle_end_time", $cycle_end_time);
        $this->Smarty->assign("cycle_week_data", $cycle_week_data);
        $this->Smarty->assign("cycle_month_data", $cycle_month_data);
        $this->Smarty->assign("cycle_week", $cycle_week);
        $this->Smarty->assign("cycle_type", $cycle_type);
        $this->Smarty->assign("cycle_month", $cycle_month);
        $this->Smarty->assign("sender", $sender);
        $this->Smarty->assign("sendemail", $sendemail);
        $this->Smarty->assign("replyemail", $replyemail);
        $this->Smarty->assign("isreport", $isreport);
        $this->Smarty->assign("reportemail", $reportemail);
        $this->Smarty->assign("filepath", $filepath);
        $this->Smarty->assign("filename", $filename);
        $this->Smarty->assign("attachmentdata", $attachmentdata);
        $this->Smarty->assign("files", $files);
        $this->Smarty->assign("attachs", $attachs);
        $this->Smarty->assign("catdata", $catdata);
        $this->Smarty->assign("filterone", $filterone);
        $this->Smarty->assign("filtersId", $filterone[0]['id']);
        $this->Smarty->assign("filters", $filters);
		 $this->Smarty->assign("mine", $mine);

        $url_page = $_SERVER['HTTP_REFERER'];
        $this->Smarty->assign("url_page", $url_page);
        $this->Smarty->assign("li_menu", "listtask");
        $this->Smarty->display('viewtask.php');
    }

    //返回组中联系人数量
    public function ajaxgroupcountAction()
    {
        $uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$condition=self::checkLoginAudit($role);
        $gname = $_POST['gname'];
        $groupid = rtrim($_POST['groupid'], ',');
        $where = '';
        $w = "";
        if (!empty($_POST['fid'])) {
            $filter = $this->filter->getOnefilter($_POST['fid']);
            $w = " where " . $filter[0]['condition'];
        }
        if ($gname == 'all') {
            $gnames = 'all';
            $where = '';
        } else {
            $groups = explode(',', $groupid);
            foreach ($groups as $k => $val) {
                if ($val == $gname) {
                    unset($groups[$k]);
                } else {
                    $gnames .= $val . ',';
                }
            }
            $where = " gname = '" . $_POST['gname'] . "'";
        }
		if (isset($where) && !empty($where)) {
            $where .= " and " . $condition . "";
        } else {
            $where = " " . $condition . "";
        }

        $tablenames = $this->group->getAllGroupInfo($where);
        foreach ($tablenames as $g_k => $g_v) {
            $total_person = $this->group->getCountfilterGroup($g_v['tablename'], $w);
            $count += $total_person;
        }

		//echo $count;exit;
        $arr['gnames'] = $gnames;
        $arr['count'] = $count;
        exit(json_encode($arr));
    }

    //复制草稿任务
    function copytaskAction()
    {
        if (!empty($_POST['tid'])) {
            $id = $_POST['tid'];
            $task_name = preg_replace("/[\s]+/is", " ", $_POST['task_name']);
            $result = $this->dbAdapter->fetchAll("select * from `mr_task` where id=" . $id . "");
            foreach ($result as &$val) {
                $val['id'] = "";
                $val['task_name'] = $task_name;
                $val['createtime'] = date('Y-m-d H:i:s', time());
            }
            $rel = $this->dbAdapter->fetchAll("select id from `mr_task` where task_name='" . $task_name . "'");
            //判断如果不为空,该任务名已经存在
            if (!empty($rel)) {
                echo "error";
            } else {
                $rows = $this->dbAdapter->insert('mr_task', $val);
				$description = "该用户复制草稿任务： ".$result[0]['subject']."，新草稿名为：" . $task_name;
                $description_en = "The user copies the draft task: ".$result[0]['subject'].", the new task name is: " . $task_name;
                $operationName = '复制草稿任务';
                $operationName_en = 'the draft copy task';
                self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
                echo 1;
            }
        }
    }

    /*提交草稿任务*/
    function subtaskAction()
    {
        if (!empty($_POST['tid'])) {
            $tid = $_POST['tid'];
            $result = $this->dbAdapter->fetchAll("select * from `mr_task` where id=" . $tid . "");
            //echo $result[0]['task_name'];exit;
            if (!empty($result[0]['task_name']) && !empty($result[0]['subject']) && !empty($result[0]['total']) && $result[0]['total'] != 0 && !empty($result[0]['sender']) && !empty($result[0]['sendemail']) && !empty($result[0]['sendtime'])) {
                if ($result[0]['checkpass'] == 3) {
                    $rows = $this->dbAdapter->update('mr_task', array('draft' => 0, 'status' => 2), 'id=' . $tid);
                    if ($result[0]['sendtype'] == 2) {
                        $arrPlan = array('status' => 2, 'cycle_type' => $result[0]['cycle_type'], 'cycle_time' => $result[0]['cycle_time'], 'cycle_end_time' => $result[0]['cycle_end_time'], 'cycle_week' => $result[0]['cycle_week'], 'cycle_month' => $result[0]['cycle_month']);
                        switch ($arrPlan['cycle_type']) {
                            case 2:
                                unset($arrPlan['cycle_month']);
                                break;
                            case 3:
                                unset($arrPlan['cycle_week']);
                                break;
                            default:
                                unset($arrPlan['cycle_month'], $arrPlan['cycle_week']);
                        }
                        self::setCycleTask($tid, $arrPlan);
                    }
                } else {
                    $rows = $this->dbAdapter->update('mr_task', array('draft' => 0, 'status' => 1), 'id=' . $tid);
                }
                if ($rows) {
                    $description = "该用户提交草稿箱里任务，任务名为：" . $result[0]['task_name'];
					$description_en = "The user submitted a draft task, the task name is: " . $result[0]['task_name'];
					$operationName = '提交草稿任务';
					$operationName_en = 'submit a draft task';
					self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
                    echo $rows;
                } else {
                    echo 'error';
                }
            }
        }
    }

	/*停止任务*/
    function stopAction(){
        if (!empty($_POST['tids'])) {
			$tids = rtrim($_POST['tids'], ',');
			//找到循环任务即 cycle_type !=0,将cycle_type =1,2,3 改为 -1,-2,-3
			$cycle_tasks = $this->dbAdapter->fetchAll("select id,cycle_type from `mr_task` where cycle_type > 0 and id in(" . $tids . ")");
            
			if(count($cycle_tasks) > 0){
				for($i =0; $i<count($cycle_tasks); $i++){
					$ctype = $cycle_tasks[$i]['cycle_type'];
					$cid = $cycle_tasks[$i]['id'];
					if($ctype > 0 ){
						$ctype = "-" . $ctype;
					}
                   
                    $result_cycle = $this->dbAdapter->query("UPDATE mr_task SET status = 5,cycle_type = ".$ctype." WHERE id =".$cid." and (status=2 or status=3)");
                    
					$tids = ",".$tids.",";
					$id =",".$cid.",";
					$tids = str_replace($id,",",$tids);
				}
			}
            $tids = trim($tids,",");
			//mr_task 2 准备发送，3 发送中，4发送完成，5停止，7发送失败，8部分失败
			$result = $this->dbAdapter->query("UPDATE mr_task SET status = 5 WHERE id in(".$tids.")and (status=2 or status=3)");
			$this->dbAdapter->query("UPDATE mr_task_log SET status = 5 WHERE tid in(".$tids.")and (status=2 or status=3)");
			//mr_smtp_task 0 Start 1投递中，2投递成功，3投递失败，4重试，6拦截，7停止
			$this->dbAdapter->query("UPDATE mr_smtp_task SET status = 7 WHERE taskid in(".$tids.")and status in(0,1,4)");
            
			if ($result) {
				$tids_arr = explode(',',$tids);
				foreach($tids_arr as $val){
					$sql = 'SELECT task_name FROM mr_task WHERE id = '.$val;
					$taskname = $this->dbAdapter->fetchOne($sql);
					
					$description = "该用户停止任务，任务名为: " . $taskname;
					$description_en = "The user stop task, the task name is: " . $taskname;
					$operationName = '停止任务';
					$operationName_en = 'stop task';
					self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
				}
				echo 'ok';exit;
			}
        }
    }
	
	/*启动任务*/
    function openAction(){
        if (!empty($_POST['tids'])) {
			$tids = rtrim($_POST['tids'], ',');
			
			//找到循环任务 cycle_type !=0,cycle_type = -1,-2,-3改为1,2,3 
			$cycle_tasks = $this->dbAdapter->fetchAll("select id,cycle_type from `mr_task` where cycle_type <> 0 and id in(" . $tids . ")");
			if(count($cycle_tasks) > 0){
				for($i =0; $i<count($cycle_tasks); $i++){
					$ctype = $cycle_tasks[$i]['cycle_type'];
					$cid = $cycle_tasks[$i]['id'];
					if($ctype < 0 ){
						$ctype = str_replace("-","",$ctype);
						$result_cycle = $this->dbAdapter->query("UPDATE mr_task SET status = 2,cycle_type = ".$ctype." WHERE id =".$cid." and status = 5");
					}
					$tids = ",".$tids.",";
					$id =",".$cid.",";
					$tids = str_replace($id,",",$tids);
				}
				$tids = trim($tids,",");
			}
			
			//mr_task,2 准备发送，3 发送中，4发送完成，5,停止，7发送失败，8部分失败
			$result = $this->dbAdapter->query("UPDATE mr_task SET status = 3 WHERE id in(".$tids.")and status = 5");
			$this->dbAdapter->query("UPDATE mr_task_log SET status = 3 WHERE tid in(".$tids.")and status = 5");
			//mr_smtp_task 1投递中，2投递成功，3投递失败，4重试，6拦截，7停止
			$this->dbAdapter->query("UPDATE mr_smtp_task SET status = 1 WHERE taskid in(".$tids.")and status = 7");
			
			if ($result) {
				$tids_arr = explode(',',$tids);
				foreach($tids_arr as $val){
					$sql = 'SELECT task_name FROM mr_task where id = '.$val;
					$taskname = $this->dbAdapter->fetchOne($sql);
					
					$description = "该用户启动任务，任务名为: " . $taskname;
					$description_en = "The user open task, the task name is: " . $taskname;
					$operationName = '启动任务';
					$operationName_en = 'open task';
					self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
				}
				echo 'ok';exit;
			}
        }
    }
	
    /*删除任务（或草稿）*/
    function deltaskAction()
    {
        //删除任务（或草稿）
        if (!empty($_POST['tid'])) {
			$type = $_POST['type'];
			if( is_numeric($_POST['tid']) ){
				$id = $_POST['tid'];
			}else{
				$id = substr(($_POST['tid']),5);
			}
			$taskname = $this->dbAdapter->fetchOne("select task_name from `mr_task` where id= " . $id . "");
            $result = $this->dbAdapter->delete('mr_task', 'id=' . $id);
			$result_log = $this->dbAdapter->delete('mr_task_log', 'tid in(' . $id . ')');
			$result_mails = $this->dbAdapter->delete('mr_smtp_task', 'taskid in(' . $id.')');
            if ($result) {
				if( $type == "draft"){
					$description = '该用户删除任务草稿,草稿名为：' . $taskname;
					$description_en = 'The user to delete task draft, the draft name is: ' . $taskname;
					$operationName = '删除任务草稿';
					$operationName_en = 'delete the task draft';
					self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
					echo $result;exit;
				}else{
					$description = '该用户删除任务,任务名为：' . $taskname;
					$description_en = 'The user to delete the task, task name is: ' . $taskname;
					$operationName = '删除任务';
					$operationName_en = 'delete the task';
					self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
					echo $result;exit;
				}
                
            }
        }
		//批量删除任务（或草稿）
        if (!empty($_POST['tids'])) {
			$type = $_POST['type'];
            $tids = rtrim($_POST['tids'], ',');
			$tasknames = "";
			$infos = $this->dbAdapter->fetchAll("select task_name from `mr_task` where id in( " . $tids . ")");
			foreach($infos as $val){
				$tasknames .= $val['task_name'].",";
			}
			$tasknames = trim($tasknames ,",");
            $result = $this->dbAdapter->delete('mr_task', 'id in(' . $tids . ')');
			$result_log = $this->dbAdapter->delete('mr_task_log', 'tid in(' . $tids . ')');
			$result_mails = $this->dbAdapter->delete('mr_smtp_task', 'taskid in(' . $tids.')');
            if ($result != '') {
				if( $type == "draft"){
					$description = "该用户批量删除任务草稿，草稿名为：".$tasknames;
					$description_en = "The user batch delete task draft，the draft name is: ".$tasknames;
					$operationName = '删除任务草稿';
					$operationName_en = 'delete task draft';
					self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
					echo 'ok';exit;
				}else{
					$description = "该用户批量删除任务，任务名为: ".$tasknames;
					$description_en = "The user batch delete task，the task name is: ".$tasknames;
					$operationName = '删除任务';
					$operationName_en = 'delete task';
					self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
					echo 'ok';exit;
				}
            }
        }
    }

    /*删除任务分类*/
    function deltaskcatAction()
    {
        //删除任务
        if (!empty($_POST['cid'])) {
            $id = $_POST['cid'];
			$vocation_name = $this->dbAdapter->fetchOne("select vocation_name from `mr_vocation` where id= " . $_POST['cid'] . "");
            $result = $this->dbAdapter->delete('mr_vocation', 'id=' . $id);
            //$result=1;
            if ($result) {
                $description = '该用户删除任务分类,分类名为：' . $vocation_name;
                $description_en = 'The user to delete the task classification, classification name is: ' . $vocation_name;
				$operationName = '删除任务分类';
				$operationName_en = 'delete the task classification';
				self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
                echo $result;
            }
        }
        if (!empty($_POST['cids'])) {
            $cids = rtrim($_POST['cids'], ',');
			$names = "";
			$infos = $this->dbAdapter->fetchAll("select vocation_name from `mr_vocation` where id in( " . $cids . ")");
			foreach($infos as $val){
				$names .= $val['vocation_name'].",";
			}
			$names = trim($names ,",");
            $result = $this->dbAdapter->delete('mr_vocation', 'id in(' . $cids . ')');
            if ($result != '') {
			    $description = '该用户批量删除任务分类，分类名为: '.$names;
                $description_en = 'The user to delete the task classification,the task classification name is:'.$names;
				$operationName = '删除任务分类';
				$operationName_en = 'delete the task classification';
				self::taskOperationLog($description, $description_en, $operationName, $operationName_en);	
                echo 'ok';
            }
        }
    }

    //验证任务信息
    function checktaskAction()
    {
        if (!empty($_POST['taskname'])) {
			$role = $this->getCurrentUserRole();
			$condition=self::checkLoginAudit($role);
            $task_name = preg_replace("/[\s]+/is", " ", $_POST['taskname']);
            if (!empty($_POST['id'])) {
                $rel = $this->dbAdapter->fetchAll("select id from `mr_task` where task_name='" . $task_name . "' and id <>" . $_POST['id'] . " and " . $condition . "");
            } else {
                $rel = $this->dbAdapter->fetchAll("select id from `mr_task` where task_name='" . $task_name . "' and " . $condition . "");
            }
            //判断如果不为空,该任务名已经存在
            if (!empty($rel)) {
                echo "error";
            }
        }
    }

    //审核任务
    function audittaskAction()
    {
        if (!empty($_POST)) {
            if ($_POST['checkpass'] == 1) {
                $rows = $this->dbAdapter->update('mr_task', array('status' => 2, 'checkpass' => $_POST['checkpass'], 'checkinfo' => $_POST['checkinfo']), 'id=' . $_POST['tid']);
                if ($rows) {
                    $result = $this->dbAdapter->fetchAll("select sendtype,cycle_type,cycle_time,cycle_end_time,cycle_week,cycle_month from `mr_task` where id= " . $_POST['tid'] . "");
                    if ($result[0]['sendtype'] == 2) {
                        $arrPlan = array('status' => 2, 'cycle_type' => $result[0]['cycle_type'], 'cycle_time' => $result[0]['cycle_time'], 'cycle_end_time' => $result[0]['cycle_end_time'], 'cycle_week' => $result[0]['cycle_week'], 'cycle_month' => $result[0]['cycle_month']);
                        switch ($arrPlan['cycle_type']) {
                            case 2:
                                unset($arrPlan['cycle_month']);
                                break;
                            case 3:
                                unset($arrPlan['cycle_week']);
                                break;
                            default:
                                unset($arrPlan['cycle_month'], $arrPlan['cycle_week']);
                        }
                        self::setCycleTask($_POST['tid'], $arrPlan);
                    }
                }
            } else {
                $rows = $this->dbAdapter->update('mr_task', array('status' => 6, 'checkpass' => $_POST['checkpass'], 'checkinfo' => $_POST['checkinfo']), 'id=' . $_POST['tid']);
            }
            if ($rows) {
				$taskname = $this->dbAdapter->fetchOne("select task_name from `mr_task` where id= " . $_POST['tid'] . "");
                $description = '该用户进行任务审核,任务名为：' . $taskname;
                $description_en = 'The user task auditing, task name is: ' . $taskname;
				$operationName = '审核任务';
				$operationName_en = 'the audit task';
				self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
                echo 'ok';
            }
        }
    }

    //审核信息
    function auditinfoAction()
    {
        if (!empty($_POST['tid'])) {
            $data = $this->dbAdapter->fetchAll("select task_name,status,checkpass,checkinfo from `mr_task` where id = " . $_POST['tid'] . "");
            if (!empty($data[0])) {
                $json = json_encode($data[0]);
                $str = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $json);
                echo $str;
            }
        }
    }

    //发送测试邮件
    function testmailsend($tid, $testemail, $subject, $content)
    {
		$uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
        $cur_username = $this->getCurrentUser();
		$condition=self::checkLoginAudit($role);
		$sendmail = new Sendmail ();
		$mailauth = $sendmail->getAllInfos ();
		if (empty($mailauth)) {
			return "No";
		}
		$account = $mailauth[0];
		$config = array('name' => $account['smtpserver'], 'port' => $account['smtpserverport'], 'auth' => 'login',
            'username' => $account['authuser'],
            'password' => $account['authpwd']);
        
        SystemConsole::selfMadeMail($account, "MailData邮件分发投递系统", "测试连通性邮件：" . $subject, $content, trim($testemail), "", $cur_username);
        return 'Success';
    }

    //添加任务附件到数据库
    function addattachment($path, $filename, $uid, $tid)
    {
        if (!empty($path) && !empty($uid) && !empty($tid)) {
			$role = $this->getCurrentUserRole();
			$condition=self::checkLoginAudit($role);
            $arrName = explode(',', trim($filename, ','));
            $arrPath = explode(',', trim($path, ','));
            $arrPath = array_combine($arrName, $arrPath);
            $createtime = date('Y-m-d H:i:s', time());
            foreach ($arrPath as $key => $val) {
                $newPath = $val;
                $aliasname = trim(substr($newPath, strrpos($newPath, '\\')), '\\');
                $filename = $key;
                $arrs = array('uid' => $uid, 'tid' => $tid, 'truename' => $filename, 'aliasname' => $aliasname, 'path' => $newPath, 'createtime' => $createtime);
                $rows = $this->dbAdapter->fetchAll("select id from `mr_attachment` where tid = " . $tid . " and " . $condition . " and aliasname = '" . $aliasname . "'");
                if ($rows) {
                    continue;
                } else {
                    $result = $this->dbAdapter->insert('mr_attachment', $arrs);
                    $description = '该用户创建任务时上传任务附件,附件名为：' . $filename;
                    $description_en = 'The user create task uploading attachments,the attached is: ' . $filename;
                    $operationName = '上传附件';
                    $operationName_en = 'uploading attachments';
                    self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
                }
            }
        }
        return $result;
    }

    //编辑任务添加任务附件到数据库
    function editattachment($path, $filename, $uid, $tid)
    {
        if (!empty($path) && !empty($uid) && !empty($tid)) {
			$role = $this->getCurrentUserRole();
			$condition=self::checkLoginAudit($role);
            $arrName = explode(',', trim($filename, ','));
            $arrPath = explode(',', trim($path, ','));
            $arrPath = array_combine($arrName, $arrPath);
            $createtime = date('Y-m-d H:i:s', time());
            foreach ($arrPath as $key => $val) {
                $newPath = $val;
                $aliasname = trim(substr($newPath, strrpos($newPath, '\\')), '\\');
                $filename = $key;
                $arrs = array('uid' => $uid, 'tid' => $tid, 'truename' => $filename, 'aliasname' => $aliasname, 'path' => $newPath, 'createtime' => $createtime);

                $rows = $this->dbAdapter->fetchAll("select id from `mr_attachment` where tid = " . $tid . " and " . $condition . " and aliasname = '" . $aliasname . "'");
                if ($rows) {
                    continue;
                } else {
                    $result = $this->dbAdapter->insert('mr_attachment', $arrs);
                    $description = '该用户编辑任务时上传任务附件,附件名为：' . $filename;
                    $description_en = 'The user edit task task uploading attachments,the attached is:' . $filename;
                    $operationName = '上传附件';
                    $operationName_en = 'uploading attachments';
                    self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
                }
            }
        }
        return $result;
    }

    /**
     *选择模板
     */
    public function selecttplAction()
    {
        if ($this->_request->isPost()) {
            $style = $this->_request->getPost('style_id');
            if ($style == "-1" || $style == "-2") {
                $where = "tpl_style=" . $style;
            } else {
                $uid = $this->getCurrentUserID();
				$role = $this->getCurrentUserRole();
				$condition=self::checkLoginAudit($role);
                $where = "tpl_style=" . $style . " and " . $condition;
            }

            $count_sql = "SELECT id FROM mr_template WHERE " . $where;
            // echo $count_sql;
            // exit;
            $rows = $this->dbAdapter->fetchAll($count_sql);
            $totalNum = count($rows);
            if ($totalNum == 0) {
                exit('<span>亲！您还没有添加附件</span>');
            }
            if ($this->_request->getPost('currentPage')) {
                $page = $this->_request->getPost('currentPage');
            } else {
                $page = 1;
            }
            $pageNum = 6;
            $total_page = ceil($totalNum / $pageNum);
            // echo $total_page;
            $offset = ($page - 1) * $pageNum;
            $prePage = $page - 1;
            if ($prePage > 0) {
                $prePage = $page - 1;
            } else {
                $prePage = $page;
            }
            $nextPage = $page + 1;
            if ($nextPage > $total_page) {
                $nextPage = $total_page;
            } else {
                $nextPage = $page + 1;
            }
            $select_sql = "select id,tpl_name,tpl_img from mr_template where " . $where . " order by id limit {$offset},{$pageNum} ";
            // echo $select_sql;
            $row = $this->dbAdapter->fetchAll($select_sql);
            // echo "<pre>";
            // print_r($row);
            // echo "</pre>";
            // $str="";
            $str = '<div class="row-fluid"  style="">';
            foreach ($row as $val) {
                $str .= "<div class='choseone' id='" . $val['id'] . "'><dl class='inline' >";
                $str .= '<dt class="text-center">' . $val['tpl_name'] . '</dt>';
                $str .= '<dd style="margin-right: 12px; margin-left: 13px;" class="actions">
					<sapn href="/templet/createtempl/tid/' . $val['id'] . '">';
                $str .= '<a href="javascript:void(0)" number="' . $val['id'] . '" onclick=stpl(this)><img src="/dist/thumb_images/' . $val['tpl_img'] . '" alt="" class="img-rounded" style="height: 133px; width: 103px;"></a>';
                $str .= '</span></dd></dl></div>';
            }
            $str .= "</div>";
            $str .= '
					<div class="row-fluid" style="' . $style . '" id="sel_tpl">
						<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
								<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;">
			&nbsp;共有<b>' . $totalNum . '</b>条记录&nbsp;&nbsp;<b>' . $page . '</b>/<b>' . $total_page . '</b>&nbsp;
							<a onclick="selPages(1)" id="DataTables_Table_0_first" class="first ui-corner-tl ui-corner-bl fg-button ui-button ui-state-default" tabindex="0">
					            首页
					        </a>
					        <a onclick="selPages(' . $prePage . ')" id="DataTables_Table_0_previous" class="previous fg-button ui-button ui-state-default" tabindex="0">
					            上一页
					        </a>';
            $listNum = 6;
            $inum = floor($listNum / 2);
            for ($i = $inum - 2; $i >= 1; $i--) {
                $pages = $page - $i;
                if ($pages <= 0) {
                    continue;
                }
                $str .= "&nbsp;<a onclick='selPages({$pages})' class='ui-corner-tr ui-corner-br fg-button ui-button ui-state-default rush' page='{$page}'>{$pages}</a>&nbsp;";
            }
            $str .= "&nbsp;<a><span style='width:20px;background-color:#26B779' class='ui-corner-tr ui-corner-br fg-button ui-button ui-state-default rush'>" . ($pages + 1) . "</span></a>&nbsp;";
            for ($i = 1; $i < $inum; $i++) {
                $pages = $page + $i;
                if ($pages <= $total_page) {
                    $str .= "&nbsp;<a  onclick='selPages({$pages})' class='ui-corner-tr ui-corner-br fg-button ui-button ui-state-default rush' page='{$pages}'>{$pages}</a>&nbsp;";
                } else {
                    break;
                }
            }
            $str .= '
					        <a onclick="selPages(' . $nextPage . ')" id="DataTables_Table_0_next" class="next fg-button ui-button ui-state-default" tabindex="0">
					            下一页
					        </a>
					        <a onclick="selPages(' . $total_page . ')" id="DataTables_Table_0_last" class="last ui-corner-tr ui-corner-br fg-button ui-button ui-state-default" tabindex="0">
					            末页
					        </a>
								</div>
						</div>
    				</div>
    				';

            echo $str;
        }
    }

    /*获取模板内容*/
    public function  tplcontAction()
    {
        if ($this->_request->isPost()) {
            $tpls_id = $this->_request->getPost('contents');
            $this->templet = new Template();
            $rows = $this->templet->getOneTpl($tpls_id);
            if ($rows) {
                echo stripcslashes($rows->tpl_body);
            }
        }
    }

    //判断新建的组是否存在
    public function ajaxgroupAction()
    {
        $uid = $this->getCurrentUserID();
        $gname = $_POST['gname'];
        if ($gname) {
            $arr = $this->dbAdapter->fetchRow("select gname from mr_group where gname='" . $gname . "'");
        }
        if (!empty($arr)) {
            echo json_encode($arr);
        } else {
            echo 0;
        }
    }

    //用于生成一个唯一的字符
    public static function uuid()
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '_';
        $uuid .= substr($chars, 8, 4) . '_';
        $uuid .= substr($chars, 12, 4) . '_';
        $uuid .= substr($chars, 16, 4) . '_';
        $uuid .= substr($chars, 20, 12);
        return $uuid;
    }

    //用于统计联系人组的成员个数
    public function getcountAction()
    {
        $uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
        $groups = rtrim($_POST['group'], ',');

        if ($groups == 'all') {
            $groups = $this->dbAdapter->fetchAll("select tablename from mr_group");
            foreach ($groups as $g_k => $g_v) {
                $groups[$g_k]['count'] = $this->dbAdapter->fetchOne("select count(id) from " . $g_v['tablename']);
                $count = $this->dbAdapter->fetchOne("select count(id) from mr_subscriber");
            }
        } else {
            $arr = explode(",", $groups);
            foreach ($arr as $k => $v) {
                if ($arr[$k]) {
					$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where gname='" . $arr[$k] . "'");
                    $count = $this->dbAdapter->fetchOne("select count(id) from " .$tablename. "") + $count;
                }
            }
        }
		$gname = $_POST['gname'];
		if(!empty($gname)){
			$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where gname='" . $gname . "'");
			$count = $this->dbAdapter->fetchOne("select count(id) from " .$tablename. "");
        }
		echo $count;
    }

    //用于获取联系人信息
    public function ajaxgetinfoAction()
    {
		$gname = $_POST['gname'];
		$uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		//$condition=self::checkLoginAudit($role);
		if ($gname == 'all') {
			//$tablename = $this->dbAdapter->fetchAll("select tablename from mr_group where " . $condition . "");
			$tablename = $this->dbAdapter->fetchAll("select tablename from mr_group ");
			foreach ($tablename as $v) {
                $groups[] = $this->dbAdapter->fetchAll("select mailbox,username from `".$v['tablename']."`");
            }
			foreach ($groups as $v) {
				foreach ($v as $v2) {
					$group[]=$v2;
				}
			}
		}else{
			//$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where gname='" . $gname . "' and " . $condition . "");
			$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where gname='" . $gname . "'");
			$group = $this->dbAdapter->fetchAll("select mailbox,username from ".$tablename);
		}
        echo json_encode($group);
    }


    public function uploadattchmentAction()
    {
        if ($this->_request->isPost()) {
            $upload = new Zend_File_Transfer_Adapter_Http ();
            $upload_dir = $this->dbAdapter->fetchone("select spath from `mr_storage` where sname = 'attach' order by id desc");
            //$upload_dir = "D:\AppServ\www\V1.1\web\uploads";//windows
            $upload->setDestination($upload_dir);
            //$ext='RM,RMVB,AVI,WMV,MPEG,EXE,TXT,RAR,ZIP,JPG,GIF,PNG,MP3,WAV,WMA,MPG,PPT,DOC,WORD,PDF,htm,html,swf,flv,xls';
            //$upload->addValidator ('Extension', false, $ext);
            $upload->addValidator('Count', false, array('min' => 1, 'max' => 5));
            $upload->addValidator('FilesSize', false, array('min' => '0KB', 'max' => '10240KB'));
            $fileInfo = $upload->getFileInfo();
            $arr['filename'] = $fileInfo['files']['name'];
            $setname = mt_rand(1, 9999) . time() . $fileInfo['files']['name'];
            $newname = $upload_dir . '/' . $setname;
            $upload->addFilter('Rename', array('target' => $newname, 'overwrite' => false));
            if (!$upload->receive()) {
                $messages = $upload->getMessages();
                echo implode("\n", $messages);
            } else {
                $path = $upload_dir . '/' . $setname;
                $tid = $this->_request->getPost('taskId');
                $uid = $this->getCurrentUserID();
                $arr['tmp_id'] = md5(uniqid(mt_rand(1, 9999), true));
                $arr['path'] = $path;
                $str = json_encode($arr);
                echo $str;
            }
        }
    }

    //删除创建任务附件
    public function delattchAction()
    {
        if ($this->_request->isPost()) {
            $del_id = $this->_request->getPost('delid');
            $delpath = $this->_request->getPost('path');
            $del = $this->_request->getPost('del');
            if ($del == 'tag' || is_numeric($del_id) == false) {
                echo 'ok';
            } else {
                if (unlink($delpath)) {
                    echo 'ok';
                }
            }
        }
    }

    //删除编辑修改页面的附件
    public function delmodattchAction()
    {
        if ($this->_request->isPost()) {
            $del_id = $this->_request->getPost('delid');
            $delpath = $this->_request->getPost('path');
            if ($del_id) {
                $this->delattach = new Attachment();
                $info = $this->delattach->selOne($del_id);
                if (!empty($info)) {
                    $aliasname = $info->aliasname;
                    $sql = "SELECT COUNT(aliasname) FROM mr_attachment WHERE aliasname IS NOT NULL AND aliasname='" . $aliasname . "'";
                    $resoult = $this->dbAdapter->fetchOne($sql);
                    if ($resoult == 1) {
                        if (unlink($info->path)) {
                            $resoults = $this->delattach->delOne($del_id);
                            if ($resoults) {
                                echo 'ok';
                            }
                        }
                    } else {
                        $resoults = $this->delattach->delOne($del_id);
                        if ($resoults) {
                            echo 'ok';
                        }
                    }
                } else {
                    if (unlink($delpath)) {
                        echo 'ok';
                    }
                }
            }
        }
    }

    public function delfiltertaskAction()
    {
        if ($this->_request->isPost()) {
            if ($_POST['fid'] != '' || $_POST['tid']) {
                $arr['fid'] = 0;
                $rows = $this->dbAdapter->update('mr_task', $arr, 'id=' . $_POST['tid']);
                if ($rows) {
					$taskname = $this->dbAdapter->fetchOne("select taskname from `mr_task` where id= " . $_POST['tid'] . "");
                    $description = '该用户进行删除任务中已选择的过滤器,任务名为：' . $taskname;
                    $description_en = 'The filter has been selected the user to delete a task,task name is:' . $taskname;
                    $operationName = 'operationName';
                    $operationName_en = 'operationName_en';
                    self::taskOperationLog($description, $description_en, $operationName, $operationName_en);
                    echo 'ok';
                }
            } else {
                echo 'ok';
            }
        }
    }

    function calc($size, $digits = 2)
    {
        $unit = array('', 'K', 'M', 'G', 'T', 'P');
        $base = 1024;
        $i = floor(log($size, $base));
        $n = count($unit);
        if ($i >= $n) {
            $i = $n - 1;
        }
        return round($size / pow($base, $i), $digits) . ' ' . $unit[$i] . 'B';
    }

    //check buffer
    public function checkbufferAction()
    {
		if($this->_request->isPost()){
			$tid = $this->_request->getPost('tid');
			if($tid != ''){
				$results = $this->dbAdapter->fetchAll('SELECT log FROM mr_smtp_task WHERE taskid = '.$tid);
				 if($results){
					foreach($results as &$val){
						$logs[] = self::dealLog($val['log']);
					}
					$str=join('<br />', $logs); 
					exit(json_encode($str));
				}
			}
            $postId = $this->_request->getPost('valsid');
			if($postId != ''){
				$result = $this->dbAdapter->fetchAll('SELECT log FROM mr_smtp_task WHERE id = '.$postId);
				if($result){
					echo json_encode(self::dealLog($result[0]['log']));
				}
			}	
        }

    }
	
	public function dealLog($log) {
        $new = array();
        foreach(preg_split('/\n/', $log) as $v){
            if($v){
                $new[] = $v;
            }
		}
	  return join('<br />', $new);
    }

    //check forward
    public function checkforwardAction()
    {
        if ($this->_request->isPost()) {
            $post_id = $this->_request->getPost('valsid');
            $sql = "SELECT  *  FROM mr_smtp_task_log WHERE id ={$post_id}";
            $resoult = $this->dbAdapter->fetchAll($sql);
            //check smtp_task_log
            $log_sql = "SELECT *  FROM mr_smtp_task WHERE id={$resoult[0]['tid']} ";
            $log_resoult = $this->dbAdapter->fetchAll($log_sql);
            $num = count($log_resoult);


            foreach($resoult as &$logs){
            	$logs['log'] = nl2br($logs['log']);
            }
            // $new_resoult=join('',$final_logs);
            // $log_resoult[$num-1]['log']=$new_resoult;
            if ($log_resoult) {
                array_push($resoult, $log_resoult[$num - 1]);
            }
            if ($resoult) {
                if ($resoult[0]['log'] == null) {
                    $resoult[0]['log'] = "";
                }
                $str = json_encode($resoult);
                echo $str;
            }
        }
    }

    //taskdata
    public function taskdataAction()
    {
        $settime = $this->_request->getPost('settime');
        exit;
        if ($settime) {
            $date = date('Y-m-d H:i', time());
            $h = mktime(date('H') - 1, date('i'), date('s'), date('m'), date('d'), date('Y'));
            $nh = date('Y-m-d H:i:s', $h);
            $sql = 'SELECT SUM(total) AS total,SUM(progress) AS progress,SUM(failure) AS failure FROM mr_smtp_task_stats WHERE runtime   BETWEEN  "' . $nh . '" AND "' . $date . '"';
            $maildatas = $this->dbAdapter->fetchAll($sql);
            if ($maildatas) {
                $strs = json_encode($maildatas[0]['total']);
            } else {
                $strs = 0;
            }
            echo $strs;
        }
        $sql = 'SELECT * FROM mr_task_stats  ';
        $resoult = $this->dbAdapter->fetchAll($sql);
        if ($resoult) {
            foreach ($resoult as $vals) {
                $arrdata[] = $vals['total'];
            }
            $num = count($arrdata);
            if ($num < 24) {
                for ($i = 0; $i <= 24 - $num; $i++) {
                    array_push($arrdata, 0);
                }
            }
            $arrdata = array_reverse($arrdata);
            $strs = json_encode($arrdata);

        } else {
            $arr = array(0);
            $strs = json_encode($arr);
        }
        echo $strs;
    }

    //smtp_data
    //taskdata
    public function tasksmtpAction()
    {
        $d = mktime(date('H'), (date('i') - date('i') % 5), date('s'), date('m'), date('d'), date('Y'));
        $q = mktime(date('H'), (date('i') - date('i') % 5), date('s'), date('m'), date('d') - 1, date('Y'));
        $nd = date('Y-m-d H:i', $d);
        $nq = date('Y-m-d H:i', $q);

        $sql = 'SELECT * FROM mr_smtp_task_stats WHERE runtime BETWEEN "' . $nq . '" AND "' . $nd . '"';
        // echo $sql;
        $resoult = $this->dbAdapter->fetchAll($sql);
        // echo "<pre>";
        // print_r($resoult);
        // echo "</pre>";
        if ($resoult) {
            foreach ($resoult as $vals) {
                $arrdata[][] = $vals['total'];
                $arr_progress[][] = $vals['progress'];
                $arr_wait[][] = $vals['total'] + $vals['progress'] - $vals['failure'];
            }

            $arrdata = array_reverse($arrdata);
            $strs = json_encode($arrdata);

        } else {
            $arr = array(0);
            $strs = json_encode($arr);
        }
        echo $strs;
    }

    //选择过滤器，筛选收件人组
    public function selectfilterAction()
    {
        $uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$condition=self::checkLoginAudit($role);
        $count = 0;
        $w = '';
        if (!empty($_POST['fid'])) {
            $filter = $this->dbAdapter->fetchAll("select * from `mr_filter` where id = " . $_POST['fid'] . "");
            $w = " where " . $filter[0]['condition'];
			
        }
        $groups = rtrim($_POST['groups'], ',');
        if ($groups == 'all') {
            $tables = $this->dbAdapter->fetchAll("select * from mr_group where " . $condition);
            foreach ($tables as $t_k => $t_v) {
                $tables[$t_k]['count'] = $this->dbAdapter->fetchOne("select count(id) from `" . $t_v['tablename'] . "` " . $w . "");
                $tnum += $tables[$t_k]['count'];
            }
        } else {
            $group = explode(",", $groups);
            foreach ($group as $val) {
                if ($val) {
                    $result = $this->dbAdapter->fetchRow("select id,gname,tablename from mr_group where gname= '" . $val . "' and " . $condition . "");
                    $groupid .= $result['id'] . ",";
                    $tnum += $this->dbAdapter->fetchOne("select count(id) from `" . $result['tablename'] . "` " . $w . "");
                }
            }
        }
        echo $tnum;
        exit;
    }

    //设置根据不同登录用户权限创建任务的审核状态
    public function setTaskaudit($userinfo)
    {
        if (!empty($userinfo)) {
            if ($userinfo['role'] == 'stasker' && $userinfo['audit'] == 0) {
                $checkpass = 3;
            } elseif ($userinfo['role'] == 'tasker' && $userinfo['audit'] == 0) {
                $checkpass = 3;
            } elseif ($userinfo['role'] == 'sadmin' or $userinfo['role'] == 'admin') {
                $checkpass = 3;
            } else {
                $checkpass = 0;
            }
        }
        return $checkpass;
    }

    public function taskOperationLog($description, $description_en, $operationName,$operationName_en)
    {
        $userid = $this->getCurrentUserID();
        $uname = $this->getcurrentuser();
        $role = $this->getCurrentUserRole();
        BehaviorTrack::addBehaviorLog($uname, $role, $userid, $operationName, $description, $operationName_en, $description_en, $_SERVER["REMOTE_ADDR"]);
        return true;
    }

    //获取邮件地址
    function getMailBox($tablename)
    {
        $mailbox = '';
        $sign = $_POST['subchar'] ? ',' : '';
        $result = $this->dbAdapter->fetchAll("select mailbox from " . $tablename);
        foreach ($result as $val) {
            $mailbox .= $val['mailbox'] . $sign;
        }

        return $mailbox;
    }

    //获取导入字段值
    function getBatchSql($file_name, $res, $gid)
    {
        $uid = $this->getCurrentUserID(); // 如类里 需替换为 $this->uid

        $xls = new Spreadsheet_Excel_Reader();
        $xls->setOutputEncoding('utf-8'); //设置编码
        chmod($file_name, 0755);
        $xls->read($file_name); //解析文件
        $crr = array();
        $data = $data_values = '';
        for ($i = 2; $i <= $xls->sheets[0]['numRows']; $i++) {
            $username = $xls->sheets[0]['cells'][$i][1];
            $mailbox = $xls->sheets[0]['cells'][$i][2];
            $email = "/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/i";
            if (!preg_match($email, $mailbox)) {
                continue;
            }
            array_push($crr, $mailbox);
            $sex = $xls->sheets[0]['cells'][$i][3];
            $birth = $xls->sheets[0]['cells'][$i][4];
            $tel = $xls->sheets[0]['cells'][$i][5];
            $groups = $xls->sheets[0]['cells'][$i][6] ? $xls->sheets[0]['cells'][$i][6] : $gid;
            if ($res) {
                foreach ($res as $r_key => $r_val) {
                    $brr[$r_val['name']] = $xls->sheets[0]['cells'][$i][6 + $r_key + 1];
                }
            }

            $value = is_array($brr) ? join(',', $brr) . ',' : '';
            $data .= "('$username','$mailbox','$sex','$birth','$tel'," . $value . " '$uid'),";

            $count = $this->dbAdapter->fetchOne("select count(*) from mr_subscriber where mailbox='" . $mailbox . "'");
            if ($count > 0) {
                continue;
            }
            $data_values .= "('$username','$mailbox','$sex','$birth','$tel','$groups'," . $value . " '$uid'),";
        }

        return array(substr($data, 0, -1), substr($data_values, 0, -1));
    }

    function dealMailBox($crr, $gid)
    {
        if ($crr) {
            foreach ($crr as $val) {
                if ($val) {
                    $groups = $this->dbAdapter->fetchOne("select groups from mr_subscriber where mailbox='" . $val . "'");
                    $newgroups = $groups != $gid ? $groups . "," . $gid : $gid;
                    $this->dbAdapter->query("update mr_subscriber set groups='" . $newgroups . "' where mailbox='" . $val . "'");
                }
            }
        }
    }

    // 创建表
    function createTable($tablename, $arr)
    {
        $sql = "CREATE TABLE " . $tablename . "(
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`uid` INT UNSIGNED NOT NULL DEFAULT 0,
				`username` varchar(256) NOT NULL,
				`mailbox` varchar(256) NOT NULL,
				`sex` tinyint(1) NOT NULL DEFAULT 1,
				`birth` int(10) NOT NULL DEFAULT 0,
				`tel` varchar(20)," . self::getSqlAndCondtion($arr) . "
				UNIQUE KEY `name`(`uid`,`mailbox`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";

        //echo $sql;exit;
        $this->dbAdapter->query($sql);
    }

    // 获取创建表时字段类型 及添加查询字段
    function getSqlAndCondtion(&$arr)
    {
        $str = '';
        if ($ext = $this->dbAdapter->fetchAll("SELECT showname,name,type FROM mr_group_extension WHERE hidden=0 ")) {
            foreach ($ext as $e_val) {
                if ($e_val['type'] == 1) {
                    $type = "char";
                } elseif ($e_val['type'] == 2) {
                    $type = "int";
                } elseif ($e_val['type'] == 3) {
                    $type = "datetime";
                } else {
                    $type = "float";
                }
                $str .= "`" . $e_val['name'] . "` " . $type . " NOT NULL ,";
                $arr[$e_val['name']] = $_POST[$e_val['name']];
            }
        }

        return $str;
    }

    public function setCycleTask($tid, $arr)
    {
        $date = date('Y-m-d H:i', time());
        $today = date('Y-m-d', time()) . ' ' . $arr['cycle_time'];
        //return $arr;exit;
        //everyday
        if ($arr['cycle_type'] == 1) {
            if ($arr['cycle_end_time'] != '' && $today >= $date) {
                if ($arr['status'] != 2) {
                    $rows = $this->dbAdapter->update('mr_task', array('status' => 2, 'sendtime' => $today), 'id=' . $tid);
                } else {
                    $rows = $this->dbAdapter->update('mr_task', array('sendtime' => $today), 'id=' . $tid);
                }
            } else {
                if ($arr['cycle_end_time'] == '' && $today >= $date) {
                    $rows = $this->dbAdapter->update('mr_task', array('sendtime' => $today), 'id=' . $tid);
                } else {
                    $rows = $this->dbAdapter->update('mr_task', array('sendtime' => ''), 'id=' . $tid);
                }
            }
            if ($rows) {
                return true;
            }
        }
        //week
        if ($arr['cycle_type'] == 2) {
            $w = date('w', time());
            if ($w == '0') {
                $week = '67';
            } elseif ($w == '6') {
                $week = '15';
            } else {
                $week = $w;
            }
            if ($arr['cycle_end_time'] != '' && $today >= $date) {
                if ($week == $arr['cycle_week']) {
                    if ($arr['status'] != 2) {
                        $rows = $this->dbAdapter->update('mr_task', array('status' => 2, 'sendtime' => $today), 'id=' . $tid);
                    } else {
                        $rows = $this->dbAdapter->update('mr_task', array('sendtime' => $today), 'id=' . $tid);
                    }
                } else {
                    $rows = $this->dbAdapter->update('mr_task', array('sendtime' => ''), 'id=' . $tid);
                }
                if ($rows) {
                    return true;
                }
            } else {
                if ($arr['cycle_end_time'] == '' && $today >= $date) {
                    $rows = $this->dbAdapter->update('mr_task', array('sendtime' => $today), 'id=' . $tid);
                } else {
                    $rows = $this->dbAdapter->update('mr_task', array('sendtime' => ''), 'id=' . $tid);
                }
                if ($rows) {
                    return true;
                }
            }
        }

        //month
        if ($arr['cycle_type'] == 3) {
            $month = date('j', time());
            $arr['month'] = date('j', time());

            $last_month = date('t', strtotime('last month', $date));
            if ($month == $last_month) {
                switch ($last_month) {
                    case '28':
                        $month = $arr['cycle_month'];
                        break;
                    case '29':
                        $month = $arr['cycle_month'];
                        break;
                    case '30':
                        $month = $arr['cycle_month'];
                        break;
                    case '31':
                        $month = $arr['cycle_month'];
                        break;

                }
            }
            if ($arr['cycle_end_time'] != '' && $today >= $date) {
                if ($month == $arr['cycle_month']) {
                    if ($arr['status'] != 2) {
                        $rows = $this->dbAdapter->update('mr_task', array('status' => 2, 'sendtime' => $today), 'id=' . $tid);
                    } else {
                        $rows = $this->dbAdapter->update('mr_task', array('sendtime' => $today), 'id=' . $tid);
                    }
                } else {
                    $rows = $this->dbAdapter->update('mr_task', array('sendtime' => ''), 'id=' . $tid);
                }
                if ($rows) {
                    return true;
                }
            } else {
                if ($arr['cycle_end_time'] == '' && $today >= $date) {
                    $rows = $this->dbAdapter->update('mr_task', array('sendtime' => $today), 'id=' . $tid);
                } else {
                    $rows = $this->dbAdapter->update('mr_task', array('sendtime' => ''), 'id=' . $tid);
                }
                if ($rows) {
                    return true;
                }
            }
        }
    }
}
