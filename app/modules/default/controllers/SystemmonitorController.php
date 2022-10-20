<?php
require ('CommonController.php');
require ('page.class.php');
class SystemmonitorController extends CommonController {
	
	public $smtptask;
	public $trustiptable;
	public $securityparam;
	public $smtpaccesslog;
	
	function init() {
		parent::init ();
		$this->smtptask = new SmtpTask ();
		$this->trustiptable = new TrustipTable ();
		$this->securityparam = new SecurityParam();
		$this->smtpaccesslog = new SmtpAccessLog ();
	}
	public function defaultAction() {	
		$this->_helper->getHelper ( 'Redirector' )->setGotoSimple ( "testshow", "setting" );
		return;
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
	
	/**
	 *	networksetting
	 */
	
	public function systemmonitorAction () {
		$info = @exec("sudo df -h", $return_array, $return_status);
		// subarea  var  usr home opt
		$echo_str = "";
		foreach ($return_array as $item) {
			$num = 0;
			if ((strpos($item, "Filesystem") === false) && (strpos($item, "/dev/shm") === false)) {
				$temp_array = explode(" ", $item);
				foreach ($temp_array as $t) {
					if ($t != "") {
						$num++;
						if ($num == '2') {
							$echo_str .= $t."&";
						}
						if ($num == '3') {
							$echo_str .= $t."&";
						}
						if ($num == '5') {
							$echo_str .= $t."&";
						}
						if ($num == '6') {
							$echo_str .= $t."@";
						}
					}
				}
			}
		}
		$this->Smarty->assign ("sysstr", $echo_str);	
		$this->Smarty->assign ("li_menu", "systemmonitor");	
		$this->Smarty->display ( 'systemmonitor.php' );
	}
	
	public function getsysinfoAction () {
		@exec ("sudo free | awk '{ print  $2 }' | tail -n 3", $free_array, $free_val);
		$free_total = $free_array[0];
		@exec ("sudo vmstat 1 2", $re_array, $re_val);
		$info = explode(" ", $re_array[3]);
		$num = 0;
		$free = 0;
		$free_cpu = 0;
		foreach ($info as $item) {
			if ($item != "") {
				$num++;
				if ($num == '4') {
					$free = $item;
				}
				if ($num == '15') {
					$free_cpu = $item;
				}
			}
		}
		$cpu_usage = (100-$free_cpu);
		if ($cpu_usage == 0) {
			$cpu_usage = 2;
		}
		$mem_usage = number_format((($free_total-$free)/$free_total)*100, 2);
		echo $cpu_usage."&".$mem_usage;
	}
	
	/**
	 *	normal mailqueue 
	 */
	
	public function mailqueueAction(){
		$source = $this->_request->get ( 'source' );
		$num = $this->_request->get ( 'num' );
		if ($num == "" || $num == null) {
			$num = 10;
			$this->setSimpleSearchKey ("num", $num);
		}
		$this->Smarty->assign ("anum", $num);
		$curpage = $this->_request->get ( 'page' );
		if ($curpage == "" || $curpage == null) {
			$curpage = 1;
			$this->setSimpleSearchKey ("curpage", $curpage);
		}
		$this->Smarty->assign ("curpage", $curpage);
		$parameter = "";
		$sql_where = "";
		$mailsize1 = $this->_request->get ( 'mailsize1' );
		if ($mailsize1 != "" && $mailsize1 != null) {
			if ($sql_where == "") {
				$sql_where = "and mailsize>='".$mailsize1."' ";
			} else {
				$sql_where .= "and mailsize>='".$mailsize1."' ";
			}
			$parameter .= "&mailsize1=".$mailsize1;
			$this->setSimpleSearchKey ("mailsize1", $mailsize1);
			$this->Smarty->assign ("mailsize1", $mailsize1);
		}
		$mailsize2 = $this->_request->get ( 'mailsize2' );
		if ($mailsize2 != "" && $mailsize2 != null) {
			if ($sql_where == "") {
				$sql_where = "and mailsize<='".$mailsize2."' ";
			} else {
				$sql_where .= "and mailsize<='".$mailsize2."' ";
			}
			$parameter .= "&mailsize2=".$mailsize2;
			$this->setSimpleSearchKey ("mailsize2", $mailsize2);
			$this->Smarty->assign ("mailsize2", $mailsize2);
		}
		$srcIP = $this->_request->get ( 'srcIP' );
		$srcIP = preg_replace("/\'/", "", $srcIP);
		if ($srcIP != "" && $srcIP != null) {
			if ($sql_where == "") {
				$sql_where = "and srcIP like '%".$srcIP."%' ";
			} else {
				$sql_where .= "and srcIP like '%".$srcIP."%' ";
			}
			$parameter .= "&srcIP=".$srcIP;
			$this->setSimpleSearchKey ("srcIP", $srcIP);
			$this->Smarty->assign ("srcIP", $srcIP);
		}
		$title = $this->_request->get ( 'title' );
		$title=str_replace(array('#', '&', "'", '"'), array('','','',''), $title);
		if ($title != "" && $title != null) {
			if ($sql_where == "") {
				$sql_where = "and title like '%".$title."%' ";
			} else {
				$sql_where .= "and title like '%".$title."%' ";
			}
			$parameter .= "&title=".$title;
			$this->setSimpleSearchKey ("title", $title);
			$this->Smarty->assign ("title", $title);
		}
		$forward = $this->_request->get ( 'forward' );
		$forward = preg_replace("/\'/", "", $forward);
		if ($forward != "" && $forward != null) {
			if ($sql_where == "") {
				$sql_where = "and forward like '%".$forward."%' ";
			} else {
				$sql_where .= "and forward like '%".$forward."%' ";
			}
			$parameter .= "&forward=".$forward;
			$this->setSimpleSearchKey ("forward", $forward);
			$this->Smarty->assign ("forward", $forward);
		}
		$status = $this->_request->get ( 'status' );
		if ($status != "" && $status != null && $status != "all") {
			$sql_where .= " and status= ".$status." ";
			$parameter .= "&status=".$status;
			$this->Smarty->assign ("status", $status);
		}
		$sendfrom = $this->_request->get ( 'sendfrom' );
		$sendfrom = preg_replace("/\'/", "", $sendfrom);
		if ($sendfrom != "" && $sendfrom != null) {
			if ($sql_where == "") {
				$sql_where = "and sendfrom like '%".$sendfrom."%' ";
			} else {
				$sql_where .= "and sendfrom like '%".$sendfrom."%' ";
			}
			$parameter .= "&sendfrom=".$sendfrom;
			$this->setSimpleSearchKey ("sendfrom", $sendfrom);
			$this->Smarty->assign ("sendfrom", $sendfrom);
		}
		$delilvertype = $this->_request->get ( 'delilvertype' );
		if (is_numeric($delilvertype)) {
			if ($sql_where == "") {
				if ($delilvertype == 1) {
					$sql_where = "and taskid>0 ";
				} else {
					$sql_where = "and taskid<=0 ";
				}
			} else {
				if ($delilvertype == 1) {
					$sql_where .= "and taskid>0 ";
				} else {
					$sql_where .= "and taskid<=0 ";
				}
			}
			$parameter .= "&delilvertype=".$delilvertype;
			$this->setSimpleSearchKey ("delilvertype", $delilvertype);
			$this->Smarty->assign ("delilvertype", $delilvertype);
		}
		$securityParam = $this->securityparam->getSecurityParam();
		$securityParam_queuetime = 5;
		if (count($securityParam) > 0) {
			if ($securityParam[0]["queuetime"] != null) {
				$securityParam_queuetime = $securityParam[0]["queuetime"];
			}
		}
        
		$securityParam_queuetime_str = date("Y-m-d 00:00:01",strtotime("-".$securityParam_queuetime." day"));
		$settime = $this->_request->get ( 'settime' );
		if (!empty($settime)) {
			if ($settime == "day") {
				$day = date("Y-m-d", time());
				if ($sql_where == "") {
					$sql_where = "and (inqueuetime like '%".$day."%' or inqueuetime='0000-00-00 00:00:00')";
				} else {
					$sql_where .= "and (inqueuetime like '%".$day."%' or inqueuetime='0000-00-00 00:00:00')";
				}
			} else if ($settime == "month") {
				$month = date("Y-m", time());
				if ($sql_where == "") {
					$sql_where = "and (inqueuetime like '%".$month."%' or inqueuetime='0000-00-00 00:00:00')";
				} else {
					$sql_where .= "and (inqueuetime like '%".$month."%' or inqueuetime='0000-00-00 00:00:00')";
				}
            }
            $this->Smarty->assign ("settime", $settime);
        }    
		$inqueuetime1 = $this->_request->get ( 'inqueuetime1' );
		if ($inqueuetime1 != "" && $inqueuetime1 != null) {
			$this->Smarty->assign ("inqueuetime1", $inqueuetime1);
			$inqueuetime1 = $inqueuetime1 < $securityParam_queuetime_str ? $securityParam_queuetime_str : $inqueuetime1;
			if ($sql_where == "") {
				$sql_where = " and inqueuetime>='".$inqueuetime1."' ";
			} else {
				$sql_where .= " and inqueuetime>='".$inqueuetime1."' ";
			}
			$description.="邮件时间:大于".$inqueuetime1.",";
			$parameter .= "&inqueuetime1=".$inqueuetime1;
			$this->setSimpleSearchKey ("inqueuetime1", $inqueuetime1);
		}
		$inqueuetime2 = $this->_request->get ( 'inqueuetime2' );
		if ($inqueuetime2 != "" && $inqueuetime2 != null) {
			if ($sql_where == "") {
				$sql_where = " and inqueuetime<='".$inqueuetime2."' ";
			} else {
				$sql_where .= " and inqueuetime<='".$inqueuetime2."' ";
			}
			$description.="邮件时间:小于".$inqueuetime2.",";
			$parameter .= "&inqueuetime2=".$inqueuetime2;
			$this->setSimpleSearchKey ("inqueuetime2", $inqueuetime2);
			$this->Smarty->assign ("inqueuetime2", $inqueuetime2);
		}
		$uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		if ($role == "tasker"){
			$tlids_arr = $this->dbAdapter->fetchAll("select id from mr_task_log where uid =".$uid);
			if(count($tlids_arr) == 0){
				$sql_where .= " and 1=0 ";
			}else{
				foreach($tlids_arr as $tlid){
					$tlids .= $tlid['id'] . ',';
				}
				$tlids = rtrim($tlids,',');
				$sql_where .= " and (taskid !=0) and tlid in(".$tlids.") ";
			}
			
		}else if($role == "stasker"){
			$sql_where .= " and (taskid !=0)";
		} 
		$sql_where .= "and (status!=2)";
		$this->setSimpleSearchKey ("sql_where", $sql_where);
		$this->setSimpleSearchKey ("parameter", $parameter);
	
		$usertype = $this->getCurrentUserType ();
		$search_permission = CommonUtil::getSearchPermission($uid, $role);// 预留方法，方法暂时返回空值，用不到
		$ips_arr = "";
		$tmp_str = "";
		$ips = "";
		if ( $usertype == 1 ) {
			$ips_arr = $this->trustiptable->getGroupByCon ( "ips", $uid );
			if ( !empty ( $ips_arr ) ) {
				foreach ( $ips_arr as $ip ) {
					if ( $ip['ips'] != "" ) {
						if ( $tmp_str == "" ) {
							$tmp_str = "('".$ip['ips'];
						} else {
							$tmp_str .= "','".$ip['ips'];
						}
					}
				}
				if ( $tmp_str != "" ) {
					$tmp_str .= "')";
					$ips = " and srcIP in ".$tmp_str;
				}
			} else {
				$ips = " and srcIP in ('')";
			}
		} 
		$total = $this->smtptask->getAllCountByCon($sql_where, $ips);
		$page = new Page ($total, $num, $parameter);
		if ($curpage > $page->pageNum) {
			$curpage = 1;
			$page->page = 1;
			$page->limit = "limit 0, ".$num;
		}
		$infos = $this->smtptask->getAllInfosByCon($sql_where." order by inqueuetime desc "."{$page->limit}", $ips);//from mr_smtp_task
		if($infos){
			foreach($infos as &$val){
				$val['forward']=str_replace(array(':0', ':1'), '',$val['forward']);
			}
			unset($val);
		}
		$mode = $this->_request->get ( 'mode' );
		if( $mode == "search"){
			$userid = $this->getCurrentUserID();
			$uname = $this->getcurrentuser();
			$role = $this->getCurrentUserRole();
			$description='该用户进行查询正常邮件的操作';
			$description_en='The user performs the operation of querying the normal mail';
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询操作', $description, 'Query operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		}
		$this->Smarty->assign ("page", $page->fpage());
		$this->Smarty->assign ("curpage", $curpage);
		$this->Smarty->assign ("infos", $infos);
		$this->Smarty->assign ("li_menu", "mailqueue");
		$this->Smarty->display('mailqueue.php');
	}
	
	public function getsmtptasklogAction () {
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$id = $this->_request->get ( 'id' );
		$infos = $this->smtptask->getInfoByTid ($id);
		$echo_array =  array();
		$log_str = "";
		foreach ($infos as $info) {
			$echo_array['taskid'] = $info['taskid'];
			$echo_array['sendfrom'] = $info['sendfrom'];
			$echo_array['forward'] = str_replace(array(':0', ':1'), '', $info['forward']);
			$echo_array['size'] = CommonUtil::ConvertDataFormat ($info['mailsize']);
			$echo_array['msgid'] = $info['msgid'];
			if (strpos($info['log'], "\r\n") !== false) {
				$info['log'] = str_replace("\r\n", "<br />", $info['log']);
			} else if (strpos($info['log'], "\n") !== false) {
				$info['log'] = str_replace("\n", "<br />", $info['log']);
			}
			$log_str .= "<font color='red'>当前投递结果</font>：</br>".$info['log'];
		}
		$echo_array['log'] = $log_str;
		echo json_encode($echo_array);
	}
	
	/**
	 *	In the past 24 hours mail statistics
	 */
	//num
	public function mailstatisticsAction () {
		$timespan = $this->_request->get ( 'timespan' );
		$fromcrond = $this->_request->get ( 'fromcrond' );
		if (empty($timespan)) {
			$timespan = "24";
		}
		$this->Smarty->assign ("fromcrond", $fromcrond);
		$this->Smarty->assign ("timespan", $timespan);
		$total_stats = $this->smtptask->getTotalStats();// unless current hour
		$cur_hour_stats = $this->smtptask->getCurrentHourStats();
		// total_stats
		$this->Smarty->assign ("total_smtp_success", $cur_hour_stats['smtp']['success']+$total_stats['smtp']['success']);
		$this->Smarty->assign ("total_smtp_fail", $cur_hour_stats['smtp']['failure']+$total_stats['smtp']['failure']);
		$this->Smarty->assign ("total_smtp_softfailure", $cur_hour_stats['smtp']['softfailure']+$total_stats['smtp']['softfailure']);
		$this->Smarty->assign ("total_smtp_total_data", $cur_hour_stats['smtp']['total']+$total_stats['smtp']['total']);
		$this->Smarty->assign ("total_task_success", $cur_hour_stats['task']['success']+$total_stats['task']['success']);
		$this->Smarty->assign ("total_task_fail", $cur_hour_stats['task']['failure']+$total_stats['task']['failure']);
		$this->Smarty->assign ("total_task_softfailure", $cur_hour_stats['task']['softfailure']+$total_stats['task']['softfailure']);
		$this->Smarty->assign ("total_task_total_data", $cur_hour_stats['task']['total']+$total_stats['task']['total']);
		// cur_hour_stats
		$this->Smarty->assign ("smtp_success", $cur_hour_stats['smtp']['success']);
		$this->Smarty->assign ("smtp_fail", $cur_hour_stats['smtp']['failure']);
		$this->Smarty->assign ("smtp_softfailure", $cur_hour_stats['smtp']['softfailure']);
		$this->Smarty->assign ("smtp_total_data", $cur_hour_stats['smtp']['total']);
		$this->Smarty->assign ("task_success", $cur_hour_stats['task']['success']);
		$this->Smarty->assign ("task_fail", $cur_hour_stats['task']['failure']);
		$this->Smarty->assign ("task_softfailure", $cur_hour_stats['task']['softfailure']);
		$this->Smarty->assign ("task_total_data", $cur_hour_stats['task']['total']);
		
		$this->Smarty->assign ("li_menu", "mailstatistics");
		if ($fromcrond == "yes") {
			$this->Smarty->display('mailstatisticsFromCrond.php');
		} else {
			$this->Smarty->display('mailstatistics.php');
		}
	}
	
	public function getdiffstatsAction () {
		if ($this->_request->isPost ()) {
			$timespan = $this->_request->getPost ( 'timespan' );
			if (empty($timespan) || !is_numeric($timespan) || $timespan > 24) {
				$timespan = 24;
			}
			$stats = $this->smtptask->getPast24HourStats ($timespan);
			$curhour = $this->smtptask->getCurrentHourStats();
			
			$echo_array = array();
			$array0 = array();// task bar
			$array1 = array();// smtp bar
			$array2 = array();// task pie
			$array3 = array();// smtp pie
			$i = 1;
			$n = 0;
			while ($i <= $timespan) {
				$mkhour  = mktime(date("H") - $timespan + $i , 0, 0, date("m") , date("d"), date("Y"));
				$hour = date('H',$mkhour);
				$i++;
				$n++;
				$array1[$n]['failure'] = 0;	
				$array1[$n]['softfailure'] = 0;
				$array1[$n]['success'] = 0;
				$array1[$n]['total'] = 0;
				$array1[$n]['hour'] = $hour;
				$array0[$n]['failure'] = 0;
				$array0[$n]['softfailure'] = 0;
				$array0[$n]['success'] = 0;
				$array0[$n]['total'] = 0;
				$array0[$n]['hour'] = $hour;
			}
			foreach ($stats['task'] as $hourstat) {
				if (!empty($hourstat)) {
					$hour = date('H',strtotime($hourstat['runtime']));
					$k = $this->ReturnJsonKey ($array0, $hour);
					$array0[$k]['failure'] = $array0[$k]['failure'] + $hourstat['failure'];
					$array0[$k]['softfailure'] = $array0[$k]['softfailure'] + $hourstat['softfailure'];
					$array0[$k]['success'] = $array0[$k]['success'] + $hourstat['success'];
					$array0[$k]['total'] = $array0[$k]['total'] + $hourstat['total'];
					$array0[$k]['hour'] = $hour;
					
					$failure_task[] = $hourstat['failure'];
					$softfailure_task[] = $hourstat['softfailure'];
					$success_task[] =  $hourstat['success'];
					$total_task[] =  $hourstat['total'];
				}
			}
			foreach ($stats['smtp'] as $hourstat) {
				if (!empty($hourstat)) {
					$hour = date('H',strtotime($hourstat['runtime']));
					$k = $this->ReturnJsonKey ($array1, $hour);
					if ($array1[$k]['hour'] == $hour) {
						$array1[$k]['failure'] = $array1[$k]['failure'] + $hourstat['failure'];
						$array1[$k]['softfailure'] = $array1[$k]['softfailure'] + $hourstat['softfailure'];
						$array1[$k]['success'] = $array1[$k]['success'] + $hourstat['success'];
						$array1[$k]['total'] = $array1[$k]['total'] + $hourstat['total'];
						$array1[$k]['hour'] = $hour;

						$failure_smtp[] = $hourstat['failure'];
						$softfailure_smtp[] = $hourstat['softfailure'];
						$success_smtp[] =  $hourstat['success'];
						$total_smtp[] =  $hourstat['total'];
					}
				}
			}
			$nh = date('H',time());
			$nk = $this->ReturnJsonKey ($array0, $nh);
			$array0[$nk]['failure'] =  intval($curhour['task']['failure']);
			$array0[$nk]['softfailure'] =  intval($curhour['task']['softfailure']);
			$array0[$nk]['success'] =  intval($curhour['task']['success']);
			$array0[$nk]['total'] =  intval($curhour['task']['total']);
			$array0[$nk]['hour'] = $nh;

			$array1[$nk]['failure'] =  intval($curhour['smtp']['failure']);
			$array1[$nk]['softfailure'] =  intval($curhour['smtp']['softfailure']);
			$array1[$nk]['success'] =  intval($curhour['smtp']['success']);
			$array1[$nk]['total'] =  intval($curhour['smtp']['total']);
			$array1[$nk]['hour'] = $nh;

			$failure_task_final = array_sum($failure_task)+$curhour['task']['failure'];
			$softfailure_task_final = array_sum($softfailure_task)+$curhour['task']['softfailure'];
			$success_task_final = array_sum($success_task)+$curhour['task']['success'];
			$total_task_final =  array_sum($total_task)+$curhour['task']['total'];

			$failure_smtp_final = array_sum($failure_smtp)+$curhour['smtp']['failure'];
			$softfailure_smtp_final = array_sum($softfailure_smtp)+$curhour['smtp']['softfailure'];
			$success_smtp_final = array_sum($success_smtp)+$curhour['smtp']['success'];
			$total_smtp_final =  array_sum($total_smtp)+$curhour['smtp']['total'];

			if ($failure_task_final == 0 && $softfailure_task_final == 0 && $success_task_final == 0) {//$wait_task_final == 0
				$failure_task_final = 0.1;
				$softfailure_task_final = 0.1;
				$success_task_final = 0.1;
			}
			$array2["failure"] = $failure_task_final;
			$array2["softfailure"] = $softfailure_task_final;
			$array2["success"] = $success_task_final;
			$array2["total"] = $total_task_final;
			if ($failure_smtp_final == 0 && $softfailure_smtp_final == 0 && $success_smtp_final == 0) {//$wait_smtp_final == 0
				$failure_smtp_final = 0.1;
				$softfailure_smtp_final = 0.1;
				$success_smtp_final = 0.1;
			}
			$array3["failure"] = $failure_smtp_final;
			$array3["softfailure"] = $softfailure_smtp_final;
			$array3["success"] = $success_smtp_final;
			$array3["total"] = $total_smtp_final;
			$echo_array[0] = $this->DealArray($array0);//$array0;//
			$echo_array[1] = $this->DealArray($array1);//$array1;//
			$echo_array[2] = $array2;
			$echo_array[3] = $array3;
			
			echo json_encode($echo_array);
		}
	}
	
	public function DealArray ($array) {
		$new_array = array();
		$new_array['hour'] = "";
		$new_array['success'] = "";
		$new_array['softfailure'] = "";
		$new_array['failure'] = "";
		foreach ($array as $item) {
			if (empty($item['success'])) {
				$item['success'] = "0";
			}
			if (empty($item['softfailure'])) {
				$item['softfailure'] = "0";
			}
			if (empty($item['failure'])) {
				$item['failure'] = "0";
			}
			if ($new_array['hour'] == "") {
				$new_array['hour'] = $item['hour'];
			} else {
				$new_array['hour'] .= "," . $item['hour'];
			}
			if ($new_array['success'] == "") {
				$new_array['success'] = $item['success'];
			} else {
				$new_array['success'] .= "," . $item['success'];
			}
			if ($new_array['softfailure'] == "") {
				$new_array['softfailure'] = $item['softfailure'];
			} else {
				$new_array['softfailure'] .= "," . $item['softfailure'];
			}
			if ($new_array['failure'] == "") {
				$new_array['failure'] = $item['failure'];
			} else {
				$new_array['failure'] .= "," . $item['failure'];
			}
		}
		return $new_array;
	}
	
	public function ReturnJsonKey ($arr, $hour) {
		foreach ($arr as $k => $v) {
			if ($v['hour'] == $hour) {
				return $k;
			}
		}
	}
	
	/**
	 *	Search Logs
	 */
	
	public function searchlogsAction () {
		$num = $this->_request->get ( 'num' );
		if ($num == "" || $num == null) {
			$num = 10;
		}
		
		$this->Smarty->assign ("anum", $num);
		$curpage = $this->_request->get ( 'page' );
		if ($curpage == "" || $curpage == null) {
			$curpage = 1;
		}
		$parameter = "";
		$sql_where = "";
		$srcIP = $this->_request->get ( 'srcip' );
		if ($srcIP != "" && $srcIP != null) {
			$sql_where = "and srcIP='".$srcIP."' ";
			$parameter .= "&srcip=".$srcIP;
			$this->Smarty->assign ("srcip", $srcIP);
		}
		$title = $this->_request->get ( 'title' );
		$title = mysql_escape_string($title);
		if ($title != "" && $title != null) {
			if ($sql_where == "") {
				$sql_where = "and title='".$title."' ";
			} else {
				$sql_where .= "and title='".$title."' ";
			}
			$parameter .= "&title=".$title;
			$this->Smarty->assign ("title", stripslashes($title));
		}
		$forward = $this->_request->get ( 'forward' );
		$forward = mysql_escape_string($forward);
		if ($forward != "" && $forward != null) {
			if ($sql_where == "") {
				$sql_where = "and forward like '%".$forward."%' ";
			} else {
				$sql_where .= "and forward like '%".$forward."%' ";
			}
			$parameter .= "&forward=".$forward;
			$this->Smarty->assign ("forward", stripslashes($forward));
		}
		$overseas = $this->_request->get ( 'overseas' );
		if ($overseas != "" && $overseas != null && $overseas != "all") {
			if ($sql_where == "") {
				if ($overseas == "0") {
					$sql_where = "and taskid > '0' ";
				}else{
					$sql_where = "and taskid = '0' ";
				}
			} else {
				if ($overseas == "0") {
					$sql_where .= "and taskid > '0' ";
				}else{
					$sql_where .= "and taskid = '0' ";
				}
			}
			$parameter .= "&taskid=".$overseas;
			$this->Smarty->assign ("overseas", $overseas);
		}
		$sendfrom = $this->_request->get ( 'sendfrom' );
		$sendfrom = mysql_escape_string($sendfrom);
		if ($sendfrom != "" && $sendfrom != null) {
			if ($sql_where == "") {
				$sql_where = "and sendfrom like '%".$sendfrom."%' ";
			} else {
				$sql_where .= "and sendfrom like '%".$sendfrom."%' ";
			}
			$parameter .= "&sendfrom=".$sendfrom;
			$this->Smarty->assign ("sendfrom", stripslashes($sendfrom));
		}
		$status = $this->_request->get ( 'status' );
		if ($status != "" && $status != null && $status != "all") {
			$sql_where .= " and status= ".$status." ";
			$parameter .= "&status=".$status;
			$this->Smarty->assign ("status", $status);
		}
		$this->Smarty->assign ("sendtime1", "");
		$this->Smarty->assign ("sendtime2", "");
		$settime = $this->_request->get ( 'settime' );
		if ($settime != "" && $settime != null) {
			if ($settime == "day") {
				$day = date("Y-m-d", time());
				if ($sql_where == "") {
					$sql_where = "and (inqueuetime like '%".$day."%' or inqueuetime='0000-00-00 00:00:00')";
				} else {
					$sql_where .= "and (inqueuetime like '%".$day."%' or inqueuetime='0000-00-00 00:00:00')";
				}
			} else if ($settime == "month") {
				$month = date("Y-m", time());
				if ($sql_where == "") {
					$sql_where = "and (inqueuetime like '%".$month."%' or inqueuetime='0000-00-00 00:00:00')";
				} else {
					$sql_where .= "and (inqueuetime like '%".$month."%' or inqueuetime='0000-00-00 00:00:00')";
				}
			} else {
				$sendtime1 = $this->_request->get ( 'sendtime1' );
				$sendtime2 = $this->_request->get ( 'sendtime2' );
				$sendtime1_time = null;
				$sendtime2_time = null;
				$today_inscope = true;
				$now_date = strtotime("now");
				if ($sendtime1 != "" && $sendtime1 != null) {
					$sendtime1_time = strtotime($sendtime1);
					if ($sendtime1_time > $now_date) {
						$today_inscope = false;
					}
				}
				if ($sendtime2 != "" && $sendtime2 != null) {
					$sendtime2_time = strtotime($sendtime2);
					if ($sendtime2_time < $now_date) {
						$today_inscope = false;
					}
				}
				$time_sql = '';
				if ($sendtime1 != "" && $sendtime1 != null && $sendtime2 != "" && $sendtime2 != null) {
					$time_sql = "(inqueuetime>='".$sendtime1."' ";
					$parameter .= "&sendtime1=".$sendtime1;
					$this->setSimpleSearchKey ("sendtime1", $sendtime1);
					$this->Smarty->assign ("sendtime1", $sendtime1);
					
					$time_sql .= "and inqueuetime<='".$sendtime2."')";
					$parameter .= "&sendtime2=".$sendtime2;
					$this->setSimpleSearchKey ("sendtime2", $sendtime2);
					$this->Smarty->assign ("sendtime2", $sendtime2);
				} else if ($sendtime1 != "" && $sendtime1 != null) {
					$time_sql .= "inqueuetime>='".$sendtime1."'";
					$parameter .= "&sendtime1=".$sendtime1;
					$this->setSimpleSearchKey ("sendtime1", $sendtime1);
					$this->Smarty->assign ("sendtime1", $sendtime1);
				} else if ($sendtime2 != "" && $sendtime2 != null) {
					$time_sql .= "inqueuetime<='".$sendtime2."'";
					$parameter .= "&sendtime2=".$sendtime2;
					$this->setSimpleSearchKey ("sendtime2", $sendtime2);
					$this->Smarty->assign ("sendtime2", $sendtime2);
				}
				if ($today_inscope) {
					$time_sql .= " or inqueuetime='0000-00-00 00:00:00'";
				}
				if (strlen($time_sql) > 0) {
					if ($sql_where == "") {
						$sql_where = "and (".$time_sql.") ";
					} else {
						$sql_where .= "and (".$time_sql.") ";
					}
				}
			}
			$parameter .= "&settime=".$settime;
			$this->Smarty->assign ("settime", $settime);
		} else {
			$day = date("Y-m-d", time());
			if ($sql_where == "") {
				$sql_where = "and (inqueuetime like '%".$day."%' or inqueuetime='0000-00-00 00:00:00')";
			} else {
				$sql_where .= "and (inqueuetime like '%".$day."%' or inqueuetime='0000-00-00 00:00:00')";
			}
			$parameter .= "&settime=day";
			$this->Smarty->assign ("settime", "day");
		}
		$uid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		if ($role == "tasker"){
			$tlids_arr = $this->dbAdapter->fetchAll("select id from mr_task_log where uid =".$uid);
			if(count($tlids_arr) == 0){
				$sql_where .= " and 1=0 ";
			}else{
				foreach($tlids_arr as $tlid){
					$tlids .= $tlid['id'] . ',';
				}
				$tlids = rtrim($tlids,',');
				$sql_where .= " and (taskid !=0) and tlid in(".$tlids.") ";
			}
			
		}else if($role == "stasker"){
			$sql_where .= " and (taskid !=0)";
		} 
		$usertype = $this->getCurrentUserType ();
		$uid = $this->getCurrentUserID ();
		$ips_arr = "";
		$tmp_str = "";
		$ips = "";
		if ( $usertype == 1 ) {
			$ips_arr = $this->trustiptable->getGroupByCon ( "ips", $uid );
			if ( !empty ( $ips_arr ) ) {
				foreach ( $ips_arr as $ip ) {
					if ( $ip['ips'] != "" ) {
						if ( $tmp_str == "" ) {
							$tmp_str = "('".$ip['ips'];
						} else {
							$tmp_str .= "','".$ip['ips'];
						}
					}
				}
				if ( $tmp_str != "" ) {
					$tmp_str .= "')";
					$ips = " and srcIP in ".$tmp_str;
				}
			} else {
				$ips = " and srcIP in ('')";
			}
		} 
		$total = $this->smtptask->getAllCountByCon($sql_where, $ips);
		$page = new Page ($total, $num, $parameter);
		if ($curpage > $page->pageNum) {
			$curpage = 1;
			$page->page = 1;
			$page->limit = "limit 0, ".$num;
		}
		$infos = $this->smtptask->getAllInfosByCon($sql_where." order by inqueuetime desc "."{$page->limit}", $ips);
		if($infos){
			foreach($infos as &$val){
				$val['forward']=str_replace(array(':0', ':1'), '',$val['forward']);
			}
			unset($val);
		}
		$mode = $this->_request->get ( 'mode' );
		if( $mode == "search"){
			$userid = $this->getCurrentUserID();
			$uname = $this->getcurrentuser();
			$role = $this->getCurrentUserRole();
			$description='该用户进行查询日志的操作';
			$description_en='The user performs the operation of querying the log';
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询操作', $description, 'Query operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		}
		$this->Smarty->assign ("curpage", $curpage);
		$this->Smarty->assign ("page", $page->fpage());
		$this->Smarty->assign ("infos", $infos); 
		$this->Smarty->assign ("li_menu", "searchlogs");
		$this->Smarty->display('searchlogs.php');
	}
	
	/**
	 *	 deny access
	 */
	
	public function denyaccessAction(){
		$source = $this->_request->get ( 'source' );
		$num = $this->_request->get ( 'num' );
		
		if ($num == "" || $num == null) {
			$num = 10;
			$this->setSimpleSearchKey ("num", $num);
		}
		$this->Smarty->assign ("anum", $num);
		$curpage = $this->_request->get ( 'page' );
		if ($curpage == "" || $curpage == null) {
			$curpage = 1;
			$this->setSimpleSearchKey ("curpage", $curpage);
		}
		$this->Smarty->assign ("curpage", $curpage);
		$parameter = "";
		$sql_where = "";
		$srcIP = $this->_request->get ( 'srcIP' );
		$srcIP = mysql_escape_string($srcIP);
		if ($srcIP != "" && $srcIP != null) {
			if ($sql_where == "") {
				$sql_where = "and srcIP like '%".$srcIP."%' ";
			} else {
				$sql_where .= "and srcIP like '%".$srcIP."%' ";
			}
			$parameter .= "&srcIP=".$srcIP;
			$this->setSimpleSearchKey ("srcIP", $srcIP);
			$this->Smarty->assign ("srcIP", stripslashes($srcIP));
		}
		$denytype = $this->_request->get ( 'denytype' );
		if ($denytype != "" && $denytype != null) {
			if ($sql_where == "") {
				$sql_where = "and type='".$denytype."' ";
			} else {
				$sql_where .= "and type='".$denytype."' ";
			}
			$parameter .= "&denytype=".$denytype;
			$this->setSimpleSearchKey ("denytype", $denytype);
			$this->Smarty->assign ("denytype", $denytype);
		}
		$sendtime1 = $this->_request->get ( 'sendtime1' );
		if ($sendtime1 != "" && $sendtime1 != null) {
			if ($sql_where == "") {
				$sql_where = "and logtime>='".$sendtime1."' ";
			} else {
				$sql_where .= "and logtime>='".$sendtime1."' ";
			}
			$parameter .= "&sendtime1=".$sendtime1;
			$this->setSimpleSearchKey ("sendtime1", $sendtime1);
			$this->Smarty->assign ("sendtime1", $sendtime1);
		}
		$sendtime2 = $this->_request->get ( 'sendtime2' );
		if ($sendtime2 != "" && $sendtime2 != null) {
			if ($sql_where == "") {
				$sql_where = "and logtime<='".$sendtime2."' ";
			} else {
				$sql_where .= "and logtime<='".$sendtime2."' ";
			}
			$parameter .= "&sendtime2=".$sendtime2;
			$this->setSimpleSearchKey ("sendtime2", $sendtime2);
			$this->Smarty->assign ("sendtime2", $sendtime2);
		}
		$this->setSimpleSearchKey ("sql_where", $sql_where);
		$this->setSimpleSearchKey ("parameter", $parameter);
		$total = $this->smtpaccesslog->getAllCountByCon($sql_where);
		$page = new Page ($total, $num, $parameter);
		if ($curpage > $page->pageNum) {
			$curpage = 1;
			$page->page = 1;
			$page->limit = "limit 0, ".$num;
		}
		$infos = $this->smtpaccesslog->getAllInfosByCon($sql_where." order by logtime desc "."{$page->limit}");//from mr_smtp_task
		
		$mode = $this->_request->get ( 'mode' );
		if( $mode == "search"){
			$userid = $this->getCurrentUserID();
			$uname = $this->getcurrentuser();
			$role = $this->getCurrentUserRole();
			$description='该用户进行查询连接日志的操作';
			$description_en='The user performs the operation of querying the connect log';
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询操作', $description, 'Query operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		}
		$this->Smarty->assign ("page", $page->fpage());
		$this->Smarty->assign ("curpage", $curpage);
		$this->Smarty->assign ("infos", $infos);
		$this->Smarty->assign ("li_menu", "denyaccess");
		$this->Smarty->display('denyaccess.php');
	}
	
	public function operateemlAction() {
		$id_list = array();
		$oper = "";
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ();
			$data = array ( 
				'idlist' => $filter->filter ( $this->_request->getPost ( 'infolist' )),
				'oper' => $filter->filter ( $this->_request->getPost ( 'oper' ))
			);
			$id_list = explode ("@", $data['idlist']);
			$oper = $data['oper'];
		} else {
			$mail_id = $this->_request->get('id');
			$oper = $this->_request->get('oper');
			$id_list[0] = $mail_id;
		}
		//delete eml
		if ($oper == "1") {
			if ($this->access->getMailQueueAccess() || $this->role == "audit") {
				foreach ($id_list as $item_id) {
					if ($item_id != "") {
						$info = $this->smtptask->getInfoByTid($item_id);
						$taskid = $this->dbAdapter->fetchOne("select taskid from mr_smtp_task where id =".$item_id);
						$arr = $this->dbAdapter->fetchALL("select * from mr_task where id =".$taskid);
						$del = $this->smtptask->delTask($item_id);
						if($del){
							$arr[0]['status'] = 8;//将发送中的正常队列邮件删除，修改mr_task 的状态为部分失败 8
							$this->dbAdapter->update('mr_task', $arr[0], 'id=' . $taskid);
							$title = $info[0]['title'];
							$forward = substr( $info[0]['forward'],0,-2);
							$description='该用户进行删除正常邮件的操作,邮件主题为: '.$title.',邮件收信人为：'.$forward;
							$description_en='The user to delete the operation of the normal mail,the mail subject is: '.$title.'the mail recipients：'.$forward;
							self::taskOperationLog($description,$description_en);
						}
					}
				}
			}
		}
		//allow mail
		else if ($oper == "2") {
			if ($this->role == "audit") {
				foreach ($id_list as $item_id) {
					if ($item_id != "") {
						$this->smtptask->updateSmtpItemStatus($item_id, 0);
					}
				}
			}
		}
		//forbid mail
		else if ($oper == "3") {
			if ($this->role == "audit") {
				foreach ($id_list as $item_id) {
					if ($item_id != "") {
						$this->smtptask->updateSmtpItemStatus($item_id, 7);
					}
				}
			}
		}
		//retry mail
		else if ($oper == "4") {
			if ($this->access->getMailQueueAccess() || $this->role == "audit") {
				foreach ($id_list as $item_id) {
					if ($item_id != "") {
						$this->smtptask->updateTask($item_id);
						$info = $this->smtptask->getInfoByTid($item_id);
						$title = $info[0]['title'];
						$forward = substr( $info[0]['forward'],0,-2);
						$description='该用户进行重试正常邮件的操作,邮件主题为: '.$title.',邮件收信人为：'.$forward;
						$description_en='The user to retry the operation of the normal mail,the mail subject is: '.$title.'the mail recipients：'.$forward;
						self::taskOperationLog($description,$description_en);
					}
				}
			}
		}
		//delete deny log
		else if ($oper == "5") {
			if ($this->access->getDenyLogsAccess()) {
				foreach ($id_list as $item_id) {
					if ($item_id != "") {
						$this->smtpaccesslog->delSmtpAccessLog ( $item_id );
					}
				}
			}
		}
		//nothing to do
		else {
		}
	}
	
	public function taskOperationLog($description,$description_en){
		$userid = $this->getCurrentUserID();
		$uname = $this->getcurrentuser();
		$role = $this->getCurrentUserRole();
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '联系人管理操作', $description, 'Download operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		return true;
	}
	
}
?>	