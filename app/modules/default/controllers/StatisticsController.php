<?php
require ('CommonController.php');
require('page.class.php');
class StatisticsController extends CommonController {
	public $account;
	public $statistics;
	
	function init() {
		header('Content-type:text/html;charset=utf-8');
		parent::init ();
		$this->account = new Account ();
        $this->statistics = new Statistics ();

	}
	
	public function singletaskAction(){
		$userid 	= $this->getCurrentUserID();
		$role 		= $this->getCurrentUserRole();
		$uname 		= $this->getCurrentUser();
		$task_name	= $this->_request->get('task_name');
		$task_name=str_replace(array("'","\\"), array('',''), $task_name);
		$selecttask = $this->_request->get('selecttask');
		$timescope	= $this->_request->get('timescope');
		$task_lid	= $this->_request->get('task_id');
		$taskid		= $this->_request->get('id');
		if($role == 'stasker'){//可以查看被删除的任务发布员的
			$where = " 1=1 ";
		} else {
			$where	= self::checkLoginAudit($role);
		}
		
		if ($taskid != '') {
			$where .= " and id = '".trim($taskid)."'";
		} 
		if($task_name != ''){
			$where .= " and task_name = '".trim($task_name)."'";
			$description = "该用户查询了任务名称为:".trim($task_name)."的单次任务统计情况";
			$description_en = "This user search single task statisticstask,names is ".trim($task_name);
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询单次任务统计', $description, 'search single task statisticstask', $description_en, $_SERVER["REMOTE_ADDR"]);
		}
		$taskdata = self::selectTaskInfo($where);
		$stattime = '';
		$lasttime = '';
		if ($timescope != '') {
			$stattime=$this->_request->get('stattime');
			$lasttime=$this->_request->get('lasttime');
			$arrTime=array('timescope'=>$timescope,'stattime'=>$stattime,'lasttime'=>$lasttime);
			$times=self::setSearchtime($arrTime);
		}elseif(!empty($taskdata)){
			if($taskdata[0]['sendtime']){
				$stattime=date('Y-m-d H:i',strtotime($taskdata[0]['sendtime']));
			}else{
				$stattime=date('Y-m-d H:i',strtotime($taskdata[0]['createtime']));
			}
			$lasttime=date('Y-m-d H:i', time());
			$times=self::setSearchtime(array('timescope'=>'setval','stattime'=>$stattime,'lasttime'=>$lasttime));
			$timescope='setval';
		}else{
			$times=self::setSearchtime(array('timescope'=>'today','stattime'=>'','lasttime'=>''));
			$timescope='today';
		}
		$arrdate=array('today'=>'今日','week'=>'本周','month'=>'本月','year'=>'本年','setval'=>'自定义时间范围');
		$this->Smarty->assign ("task_name", $task_name);
		$this->Smarty->assign ("selecttask", $selecttask);
		$this->Smarty->assign ("timescope", $timescope);
		$this->Smarty->assign ("task_lid", $task_lid);
		$this->Smarty->assign ("stattime", $stattime);
		$this->Smarty->assign ("lasttime", $lasttime);
		$this->Smarty->assign ("arrdate", $arrdate);
		if(!empty($taskdata)){
			if($role == 'stasker'){//可以查看被删除的任务发布员的
				$wh = " 1=1 ";
			} else {
				$wh	= self::checkLoginAudit($role);
			}
			if($times != null){
				$stattime=date('Y-m-d H:i:s',$times['begintime']);
				$lasttime=date('Y-m-d H:i:s',$times['endtime']);
				$wh .= " and runtime >= '".$stattime."' and runtime <= '".$lasttime."' and ";
			}else{
				$wh .= " and ";
			}
			$taskresult=self::sendMailRuntime($taskdata[0]['id'],$wh);
			$task_log_id = $task_lid ? $task_lid : $taskresult[0]['id'];
			if(!empty($taskresult)){
				$sql='SELECT count(id) FROM `mr_smtp_task` WHERE taskid = '.$taskdata[0]['id'].' order by runtime desc';
				//echo $sql;
				$total=$this->statistics->getSendNum($sql);
				if($total != 0){
					//发送情况
					$sendresult=self::sendMailSuccess($taskdata[0]['id'],$task_log_id);
					$sendresult['totalpercent']=number_format($sendresult['total']/$taskresult[0]['total']*100,1).'%';
					//反馈情况
					$backresult=self::mailFeedback($taskdata[0]['id'],$task_log_id);
					$backresult['totalpercent']=number_format($backresult['total']/$taskresult[0]['total']*100,1).'%';
				}else{
					$sendresult=array();
					$backresult=array();
				}
				$fpage=array();
				$fpage['pageSize']=10; 
				$fpage['pageMax']=0; 
				$fpage['currentPage']=1;
				$w='';
				if($task_log_id == 'all'){
					$w= '';
				}else{
					$w=" and tlid = ".$task_log_id;
				}
				$sql='SELECT count(id) FROM `mr_smtp_task` WHERE taskid = '.$taskdata[0]['id'].' '.$w.' order by runtime desc';
				$fpage['total']=$this->statistics->getSendNum($sql);
				$fpage['pageMax']=ceil($fpage['total']/$fpage['pageSize']); 	
				
				if($_GET['page'] != ''){
					$fpage['currentPage']=intval($_GET['page']);
				}
				if($fpage['currentPage']<=$fpage['pageMax']){ 
					$sql2="SELECT * FROM `mr_smtp_task`  WHERE taskid = ".$taskdata[0]['id']." ".$w." order by runtime desc limit ".($fpage['currentPage']-1)*$fpage['pageSize'].",".$fpage['pageSize']."";
					$statusresult=$this->statistics->selectAllTask($sql2);
					foreach($statusresult as &$val){
						$val['status']=self::checkstatus($val['status']);
						$val['forward']=substr($val["forward"] ,0,-2);
					}
					unset($val);
				}
				if($_GET['page'] != ''){
					exit(json_encode($statusresult));
				}	

			$AreaId = $taskdata[0]['id'];
			$Total_Time_Sql = $w;
			if(!empty($AreaId)){
				//Area Statistics
				$Piece_Sql = 'SELECT COUNT(clientarea) AS countArea ,taskid,id ,clientarea FROM mr_smtp_task WHERE taskid = '.$AreaId." ".$Total_Time_Sql.' GROUP BY clientarea';
				$Piece_Resoult = $this->dbAdapter->fetchAll($Piece_Sql);
				if($Piece_Resoult){
					$Num = count($Piece_Resoult);
					$ip = new ip();
					$total = 0;
					for($i=0;$i<$Num;$i++){
						$getArea= $ip->getProvince($Piece_Resoult[$i]['clientarea']);
						$New_Piece_Area[]=array_merge($Piece_Resoult[$i],$getArea);    //   地区
						$total +=$Piece_Resoult[$i]['countArea'];
					}
					for($j=0;$j<$Num;$j++){
						$Arr_Total['total'] = $total;
						$New_Piece_Areas[]=array_merge($New_Piece_Area[$j],$Arr_Total);    //   地区
					}
					
					for($k=0;$k<$Num;$k++){
						$New_Arr_Total[$k]['cha'] = $New_Piece_Areas[$k]['cha'];      //Abbreviation
						$New_Arr_Total[$k]['name'] = $New_Piece_Areas[$k]['area'];	//area
						$New_Arr_Total[$k]['des'] = "<br>".$New_Piece_Areas[$k]['countArea']."个活动点";	//total
					}


					$str = $this->wphp_urlencode($New_Arr_Total);
					$ret = json_encode($str);
					$Piece_Resoult_Json=urldecode($ret);
					
					foreach($New_Piece_Areas as &$vals){
							$vals['total'] = $vals['countArea'] / $vals['total'] * 100;					
					}
				}

				// Receiver Statistics
				$Receiver_Sql = "SELECT id,taskid,forward as sendto,status,hasread,readcount  FROM mr_smtp_task WHERE taskid = {$AreaId} ".$Total_Time_Sql;
				$Receiver_Resoult=$this->dbAdapter->fetchAll($Receiver_Sql);
				if($Receiver_Resoult){
					$Tnums = count($Receiver_Resoult);
					for($s=0;$s<$Tnums;$s++){
						$Arr_Mails[$s]['sendto']=$Receiver_Resoult[$s]['sendto'];
						$Arr_Mails[$s]['id']=$Receiver_Resoult[$s]['id'];
					}
					for($g=0;$g<$Tnums;$g++){
					  $temp = explode('@',$Arr_Mails[$g]['sendto']);
					  $temps = substr($temp[1],0,strpos($temp[1],'.'));
					  $New_Mails[]= $temps;
					  $Receiver_Mails[$g][]= $temps;
					  $Receiver_Mails[$g][]= $Arr_Mails[$g]['id'];
					}
					$Count_Mail = array_count_values($New_Mails);
						
					$Max_nums = Zend_Registry::get('domail');
					$Final_nums = count($Count_Mail);
					if($Final_nums>$Max_nums){
						$Mail_Array=array_keys(array_slice($Count_Mail,0,$Max_nums-1,true));
					}else{
							$Mail_Array=array_keys($Count_Mail);
					}

					$f = 0;
					$Final_Mails = array();
					$Receiver_Final_Mails = array();
					foreach($Count_Mail as $key=>$mails){
						if($f<$Tnums){
							if(in_array($key,$Mail_Array)){
								$Final_Mails[$f][]= strval($key);;	
								array_push($Final_Mails[$f],floatval(number_format($mails/$Tnums*100,1)));	
							}else{
									$Other_Mails[]= $mails;	
							}
							$f++;
						}
					}
					
					foreach($Receiver_Mails as $values){
						if(in_array($values[0],$Mail_Array)){
								$Receiver_Final_Mails[$values[0]][]=$values[1];
							}
							else{
								$Receiver_Final_Mails['其他'][]=$values[1];	
							}
					}
					foreach($Receiver_Final_Mails as &$pps){
						$pps=join(',',$pps);
					}
						
					//Other total
					if($Other_Mails){
						$Other_total = array_sum($Other_Mails);
						$Other_final[0][0] = '其他';
						$Other_final[0][1] = floatval(number_format($Other_total/$Tnums*100,1));
					}else{
						$Other_final = array();

					}
					$Final_Resoult_Mails=array_merge($Final_Mails,$Other_final);


					$ret = json_encode($Final_Resoult_Mails);
					$Receiver_Resoult_Json=urldecode($ret);

					$ii = 0;
					$RFM_Num = count($Receiver_Final_Mails);
					
					foreach($Receiver_Final_Mails as $k=>$v){
						if($ii<$RFM_Num){
							$sel_sql = "SELECT id,taskid,forward as sendto,status,hasread,readcount  FROM mr_smtp_task WHERE  id IN({$v}) ".$Total_Time_Sql." GROUP BY id";
							$sel_resoult = $this->dbAdapter->fetchAll($sel_sql);
							$resoult[$ii]['totalsend'] = count($sel_resoult);
							$resoult[$ii]['sendto'] = $k;
							$resoult[$ii]['proportion'] = floatval(number_format(count($sel_resoult)/$Tnums*100,1));
							$resoult[$ii]['soft']=array();
							$resoult[$ii]['hard']=array();	
							$resoult[$ii]['success']=array();	
							foreach($sel_resoult as $v1){
								//soft quit
								if($v1['status'] == 5){
									$resoult[$ii]['soft'][]=$v1['status'];
								}
								//hard quit
								if($v1['status'] == 3){
									$resoult[$ii]['hard'][]=$v1['status'];	
								}
								if($v1['status'] == 2){
									$resoult[$ii]['success'][]=$v1['status'];
								}
								$resoult[$ii]['hasread']+=$v1['hasread'];
								$resoult[$ii]['readcount']+=$v1['readcount'];
								//
							}
						}
						$ii++;
					}
					//Receiver Statistics
					foreach($resoult as &$v2){
						$v2['success'] = number_format(count($v2['success'])/$v2['totalsend']*100,1);
						if(is_array($v2['soft'])){
							$v2['soft']=count($v2['soft']);
						}
						if(is_array($v2['hard'])){
							$v2['hard']=count($v2['hard']);
						}
					}
				}
				

					//os
					$Os_Sql = "SELECT COUNT(clientos) AS countos ,id,clientos,SUM(hasread) AS hasread,SUM(readcount) AS readcount FROM mr_smtp_task WHERE taskid={$AreaId} ".$Total_Time_Sql." GROUP BY clientos";
					$Os_Resoult = $this->dbAdapter->fetchAll($Os_Sql);
					if($Os_Resoult){
					$Os_Array = array(1=>'Windows XP',2=>'Windows 7', 3=>'Windows 8',4=>'Windows NT',5=>'Linux',6=>'Unix',7=>'Ipad', 8=>'Android',9=>'Iphone',10=>'Macintosh',11=>'Other');
						foreach($Os_Resoult as &$vals){
							foreach($Os_Array as $k=>$v){
								if($vals['clientos']==$k && $vals['clientos']!=11){
									$vals['os']=$v;
									$vals['total'] += $vals['countos'];
								}
							}
							if($vals['clientos'] == 0 || $vals['clientos']==11){
									$vals['os'] = $Os_Array[11];
									// $total_other[]=$vals['countos'] ;
									$vals['total']+=$vals['countos'] ;
								}
								$Count_Sum_Array[]=$vals['countos'];
						}

						$Count_Sum=array_sum($Count_Sum_Array);
						$Final_Data = array(0,0,0,0,0,0,0,0,0,0,0);
						foreach($Os_Resoult as $vOne){
							foreach($Final_Data as $k=>$vTwo){
								if(($vOne['clientos']-1)>=0 && ($vOne['clientos']-1) ==$k){
										$Final_Data[$k] = number_format($vOne['total']/$Count_Sum*100,1);

								}
								if(($vOne['clientos']-1)<0){
										$Final_Data[10] = number_format($vOne['total']/$Count_Sum*100,1);
									}
							}
						}
						
					foreach($Final_Data as &$vv){
								$vv =floatval(strval($vv));
						}
						$Os_Str = urldecode(json_encode($Final_Data));
					}
						
					//ps

					$Sp_Sql = "SELECT COUNT(clientsp) AS countsp ,id,clientsp,SUM(hasread) AS hasread,SUM(readcount) AS readcount FROM mr_smtp_task WHERE taskid={$AreaId} ".$Total_Time_Sql." GROUP BY clientsp";
					$Sp_Resoult = $this->dbAdapter->fetchAll($Sp_Sql);
					if($Sp_Resoult){
					
					$Sp_Array = array(1=>'联通',2=>'电信', 3=>'方正网络',4=>'零鱼沸点网络',5=>'其他');
						foreach($Sp_Resoult as &$vals){
							foreach($Sp_Array as $k=>$v){
								if($vals['clientsp']==$k && $vals['clientsp']!=5){
									$vals['sp']=$v;
									$vals['total'] += $vals['countsp'];
								}
							}
							if($vals['clientsp'] == 0 || $vals['clientsp']==5){
									$vals['sp'] = $Sp_Array[5];
									$vals['total']+=$vals['countsp'] ;
								}
								$Count_Sp_Sum_Array[]=$vals['countsp'];
						}
						

						$Count_Sp_Sum=array_sum($Count_Sp_Sum_Array);
						$Final_Sp_Data = array(0,0,0,0,0);
						foreach($Sp_Resoult as $vOne){
							foreach($Final_Sp_Data as $k=>$vTwo){
								if(($vOne['clientsp']-1)>=0 && ($vOne['clientsp']-1) ==$k){
										$Final_Sp_Data[$k] = number_format($vOne['total']/$Count_Sp_Sum*100,1);

								}
								if(($vOne['clientsp']-1)<0){
										$Final_Sp_Data[4] = number_format($vOne['total']/$Count_Sp_Sum*100,1);
									}
							}
						}
						
					foreach($Final_Sp_Data as &$vv){
								$vv =floatval(strval($vv));
						}
						$Sp_Str = urldecode(json_encode($Final_Sp_Data));
					}
					
					//broswer
					$Br_Sql = "SELECT COUNT(clientbrowser) AS countbrowser ,id,clientbrowser,SUM(hasread) AS hasread,SUM(readcount) AS readcount FROM mr_smtp_task WHERE taskid={$AreaId} ".$Total_Time_Sql." GROUP BY clientbrowser";
					$Br_Resoult = $this->dbAdapter->fetchAll($Br_Sql);
					if($Br_Resoult){
					$Br_Array = array(1=>'Firefox',2=>'MSIE', 3=>'Chrome',4=>'Safari',5=>'Opera',6=>'Other');
						foreach($Br_Resoult as &$vals){
							foreach($Br_Array as $k=>$v){
								if($vals['clientbrowser']==$k && $vals['clientbrowser']!=6){
									$vals['browser']=$v;
									$vals['total'] += $vals['countbrowser'];
								}
							}
							if($vals['clientbrowser'] == 0 || $vals['clientbrowser']==6){
									$vals['browser'] = $Br_Array[6];
									$vals['total']+=$vals['countbrowser'] ;
								}
								$Count_Br_Sum_Array[]=$vals['countbrowser'];
						}
						$Count_Br_Sum=array_sum($Count_Br_Sum_Array);
						$Final_Br_Data = array(0,0,0,0,0,0);
						foreach($Br_Resoult as $vOne){
							foreach($Final_Br_Data as $k=>$vTwo){
								if(($vOne['clientbrowser']-1)>=0 && ($vOne['clientbrowser']-1) ==$k){
										$Final_Br_Data[$k] = number_format($vOne['total']/$Count_Br_Sum*100,1);

								}
								if(($vOne['clientbrowser']-1)<0){
										$Final_Br_Data[5] = number_format($vOne['total']/$Count_Br_Sum*100,1);
									}
							}
						}
						
					foreach($Final_Br_Data as &$vv){
								$vv =floatval(strval($vv));
						}
						$Br_Str = urldecode(json_encode($Final_Br_Data));
					}
				
				}
			}
		}
		$this->Smarty->assign ("fpage", $fpage);
		$this->Smarty->assign ("task_id", $task_log_id);
		$this->Smarty->assign ("Piece_Resoult_Json", $Piece_Resoult_Json);
		$this->Smarty->assign ("Receiver_Resoult_Json", $Receiver_Resoult_Json);
		$this->Smarty->assign ("Final_Resoult_Mails", $Final_Resoult_Mails);
		$this->Smarty->assign ("Piece_Resoult", $New_Piece_Areas);
		$this->Smarty->assign ("Os_Str", $Os_Str);
		$this->Smarty->assign ("Sp_Str", $Sp_Str);
		$this->Smarty->assign ("Br_Str", $Br_Str);
		$this->Smarty->assign ("resoult", $resoult);
		$this->Smarty->assign ("Os_Resoult", $Os_Resoult);
		$this->Smarty->assign ("Sp_Resoult", $Sp_Resoult);
		$this->Smarty->assign ("Br_Resoult", $Br_Resoult);
		$this->Smarty->assign ("taskresult", $taskresult);
		$this->Smarty->assign ("sendresult", $sendresult);
		$this->Smarty->assign ("backresult", $backresult);
		$this->Smarty->assign ("statusresult", $statusresult);
		$this->Smarty->assign ("taskdata", $taskdata[0]);
		$this->Smarty->assign ("li_menu", "singletask");
		$this->Smarty->display ( 'singletask.php');	
	}	
	
	// 按任务分类统计
	public function taskclassificationAction(){
		$where = '';
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$where = self::checkLoginAudit($role);
		
		// 获取任务分类
		$sql="select id,vocation_name from mr_vocation where ".$where." order by id desc";
		$catdata=$this->statistics->getTypeinfo($sql);

		if ($this->_request->get('cid') != '') {
			$cid = $this->_request->get('cid');
		} else {
			$cid = $catdata[0]['id'];
		}
		
		$timescope = $this->_request->get('timescope');
		$task_lid = $this->_request->get('task_id');
		
		if($timescope != ''){
			$arrTime=array('timescope'=>$timescope,'stattime'=>$this->_request->get('stattime'),'lasttime'=>$this->_request->get('lasttime'));
			$times=self::setSearchtime($arrTime);
		}else{
			$times=self::setSearchtime(array('timescope'=>'today','stattime'=>'','lasttime'=>''));
		} 

		$arrdate=array('today'=>'今日','week'=>'本周','month'=>'本月','year'=>'本年','setval'=>'自定义时间范围');
		$this->Smarty->assign ("timescope", $timescope);
		$this->Smarty->assign ("task_lid", $task_lid);
		$this->Smarty->assign ("stattime", $this->_request->get('stattime'));
		$this->Smarty->assign ("lasttime", $this->_request->get('lasttime'));
		$this->Smarty->assign ("arrdate", $arrdate);
		if($times != null){
			$stattime=date('Y-m-d H:i:s',$times['begintime']);
			$lasttime=date('Y-m-d H:i:s',$times['endtime']);
			$w = " (tl.runtime >= '".$stattime."' and tl.runtime <= '".$lasttime."') and ";
		}
	
		if($cid != ''){
			$sql = "SELECT t.id as taskid,tl.id as tlid,tl.total as tnum,t.task_name,tp.id,tp.runtime,tp.forward,tp.status FROM mr_task_log tl LEFT JOIN mr_task t ON tl.tid=t.id LEFT JOIN mr_smtp_task tp ON tl.tid=tp.taskid AND tl.id=tp.tlid WHERE ".$w." t.cid=".$cid." AND (t.status =4 OR t.status = 5 OR t.status = 7 OR t.status= 8) group by taskid";
			$taskdata = $this->statistics->selectAllTask($sql);
			$fpage=array();
			$fpage['pageSize']=10; 
			$fpage['pageMax']=0; 
			$fpage['currentPage']=1;
			
			$fpage['total']=$this->statistics->getSendNum("SELECT count(tp.id) FROM mr_task_log tl LEFT JOIN mr_task t ON tl.tid=t.id LEFT JOIN mr_smtp_task tp ON tl.tid=tp.taskid AND tl.id=tp.tlid WHERE ".$w." t.cid=".$cid." AND (t.status =4 OR t.status = 5 OR t.status = 7 OR t.status= 8)");
			$fpage['pageMax']=ceil($fpage['total']/$fpage['pageSize']); 
			//ajax分页
			if($_GET['page'] != ''){
				$fpage['currentPage']=intval($_GET['page']);
			}
			if($fpage['currentPage']<=$fpage['pageMax']){ 
				$sql2="SELECT t.id as taskid,tl.id as tlid,tl.total as tnum,t.task_name,tp.id,tp.runtime,tp.forward,tp.status FROM mr_task_log tl LEFT JOIN mr_task t ON tl.tid=t.id LEFT JOIN mr_smtp_task tp ON tl.tid=tp.taskid AND tl.id=tp.tlid WHERE ".$w." t.cid=".$cid." AND (t.status =4 OR t.status = 5 OR t.status = 7 OR t.status= 8)   limit ".($fpage['currentPage']-1)*$fpage['pageSize'].",".$fpage['pageSize']."";
				$rel=$this->statistics->selectAllTask($sql2);
				foreach($rel as $val){
					$val['status']=self::checkstatus($val['status']);
					$val['forward'] = substr($val['forward'],0,-2);
					$statusresult[]=$val;
				}
				unset($val);
			}
			if($_GET['page'] != ''){
				exit(json_encode($statusresult));
			}
		}
		if(!empty($taskdata)){
			foreach($taskdata as $val){
				$taskresult[]=self::sendMailSuccess($val['taskid'],$val['tlid']);
				$tnum += $val['tnum'];
				$backs[]=self::mailFeedback($val['taskid'],$val['tlid']);
			}
			unset($val);
		}
		if(!empty($taskresult)){
			foreach($taskresult as $val2){
				$sendresult['total'] += $val2['total'];
				$sendresult['success'] += $val2['success'];
				$sendresult['hardFail'] += $val2['hardFail'];
				$sendresult['softFail'] += $val2['softFail'];	
			}
			unset($val2);
				$sendresult['totalpercent']=number_format($sendresult['total']/$tnum*100,1).'%';
				$success=number_format($sendresult['success']/$sendresult['total']*100,1);
				$sendresult['successpercent']=$success.'%';
				$hardFail=number_format($sendresult['hardFail']/$sendresult['total']*100,1);
				$sendresult['hardFailpercent']=$hardFail.'%';
				$softFail=number_format($sendresult['softFail']/$sendresult['total']*100,1);
				$sendresult['softFailpercent']=$softFail.'%';
				$succ="['成功到达数量比例',".$success."]";
				$hard="['硬退回数量比例',".$hardFail."]";
				$soft="['软退回数量比例',".$softFail."]";
				//$others="['其他',".number_format(($sendresult['total']-$sendresult['success']-$sendresult['hardFail']-$sendresult['softFail'])/$sendresult['total']*100,1)."]";
				$sendresult['data']="[".$succ.",".$hard.",".$soft.",".$others."]";
				$sendresult['data']="[".$succ.",".$hard.",".$soft."]";
		}	
		if(!empty($backs)){
			foreach($backs as $val3){
				$backresult['total'] += $val3['total'];
				$backresult['numberAt'] += $val3['numberAt'];
				$backresult['openNum'] += $val3['openNum'];
				$backresult['clickNum'] += $val3['clickNum'];
			}
			unset($val3);
				$backresult['totalpercent']=number_format($backresult['total']/$tnum*100,1).'%';
				$numberAt=number_format($backresult['numberAt']/$backresult['total']*100,1);
				$backresult['numberAtpercent']=$numberAt.'%';
				$openNum=number_format($backresult['openNum']/$backresult['total']*100,1);
				$backresult['openNumpercent']=$openNum.'%';
				$clickNum=number_format($backresult['clickNum']/$backresult['total']*100,1);
				$backresult['clickNumpercent']=$clickNum.'%';
				$total="['发送数量',".$backresult['total']."]";
				$arrive="['到达数量',".$backresult['numberAt']."]";
				$open="['打开数量',".$backresult['openNum']."]";
				$click="['点击数量',".$backresult['clickNum']."]";
				$backresult['data']="[".$total.",".$arrive.",".$open.",".$click."]";
		}
		//area
			if($cid){
				$Task_Sql="SELECT t.id as taskid,tl.id as tlid,t.total as tnum FROM mr_task_log tl LEFT JOIN mr_task t ON tl.tid=t.id LEFT JOIN mr_smtp_task tp ON tl.tid=tp.taskid AND tl.id=tp.tlid WHERE ".$w." t.cid=".$cid." AND (t.status =4 OR t.status = 5 OR t.status = 7 OR t.status= 8) group by taskid";
				
				$Task_Resoult = $this->dbAdapter->fetchAll($Task_Sql);
				if($Task_Resoult){
					
					foreach($Task_Resoult as $vt){
						$taskids .=$vt['taskid'].',';
						$tlids .=$vt['tlid'].',';
					}
					
				$Task_Str=rtrim($taskids,',');
				$Total_Time_Sql = " and tlid IN(".rtrim($tlids,',').") ";
				
				$Areas_Sql = "SELECT COUNT(clientarea) AS countarea,clientarea FROM mr_smtp_task WHERE taskid IN({$Task_Str}) ".$Total_Time_Sql." GROUP BY clientarea";
				$Areas_Resoult = $this->dbAdapter->fetchAll($Areas_Sql);
				
					if($Areas_Resoult){
						$Anums = count($Areas_Resoult);
						$ips = new ip();
						$ia = 0;
						$Total_Area = 0;
						$Areas_Data = array();
						foreach($Areas_Resoult as $va){
							$getAreas = $ips->getProvince($va['clientarea']);	
							$Areas_Data[$ia]['cha'] = $getAreas['cha'];
							$Areas_Data[$ia]['name'] = $getAreas['area'];
							$Areas_Data[$ia]['des'] =" <br/> ".$va['countarea']."个活动点";
							$Areas_Datas[$ia]['name'] = $getAreas['area'];
							$Areas_Datas[$ia]['count'] = $va['countarea'];
							$Total_Area +=$va['countarea'];
							$ia++;
						}

						$str = $this->wphp_urlencode($Areas_Data);
						$ret = json_encode($str);
						$Final_Areas_Json=urldecode($ret);

						foreach($Areas_Datas as &$vva){
							$vva['ratio'] = floatval(number_format($vva['count']/$Total_Area*100,1))." %";
						}
					}

					//
					// Receiver Statistics
					$Receiver_Sql = "SELECT id,taskid,forward as sendto,status,hasread,readcount  FROM mr_smtp_task WHERE taskid IN({$Task_Str}) ".$Total_Time_Sql;
					$Receiver_Resoult=$this->dbAdapter->fetchAll($Receiver_Sql);
					if($Receiver_Resoult){
						
						$Tnums = count($Receiver_Resoult);
						
						for($s=0;$s<$Tnums;$s++){
							$Arr_Mails[$s]['sendto']=$Receiver_Resoult[$s]['sendto'];
							$Arr_Mails[$s]['id']=$Receiver_Resoult[$s]['id'];
						}
						
						
						for($g=0;$g<$Tnums;$g++){
						  $temp = explode('@',$Arr_Mails[$g]['sendto']);
						  $temps = substr($temp[1],0,strpos($temp[1],'.'));
						  $New_Mails[]= $temps;
						  $Receiver_Mails[$g][]= $temps;
						  $Receiver_Mails[$g][]= $Arr_Mails[$g]['id'];
						}
							
						$Count_Mail = array_count_values($New_Mails);
						$Max_nums = Zend_Registry::get('domail');
						$Final_nums = count($Count_Mail);
						if($Final_nums>$Max_nums){
							$Mail_Array=array_keys(array_slice($Count_Mail,0,$Max_nums-1,true));
						}else{
								$Mail_Array=array_keys($Count_Mail);
						}
							
						$f = 0;
						$Final_Mails = array();
						$Receiver_Final_Mails = array();
						// $Mail_Array = array("qq",'126','163','sohu','gmail','hotmail','sina','21cn','139');
						foreach($Count_Mail as $key=>$mails){
							if($f<$Tnums){
								if(in_array($key,$Mail_Array)){
									$Final_Mails[$f][]= strval($key);;	
									array_push($Final_Mails[$f],floatval(number_format($mails/$Tnums*100,1)));	
								}else{
										$Other_Mails[]= $mails;	
								}
								$f++;
							}
						}
						
						foreach($Receiver_Mails as $values){
							if(in_array($values[0],$Mail_Array)){
									$Receiver_Final_Mails[$values[0]][]=$values[1];
								}else{
									$Receiver_Final_Mails['其他'][]=$values[1];	
								}
						}
						foreach($Receiver_Final_Mails as &$pps){
							$pps=join(',',$pps);
						}
							
						//Other total
						if($Other_Mails){
							$Other_total = array_sum($Other_Mails);
							$Other_final[0][0] = '其他';
							$Other_final[0][1] = floatval(number_format($Other_total/$Tnums*100,1));
						}else{
							$Other_final = array();

						}
						$Final_Resoult_Mails=array_merge($Final_Mails,$Other_final);

						$ret = json_encode($Final_Resoult_Mails);
						$Receiver_Resoult_Json=urldecode($ret);

						$iis = 0;
						$resoult = array();
						$RFM_Num = count($Receiver_Final_Mails);
						

						foreach($Receiver_Final_Mails as $k=>$v){
							if($iis<$RFM_Num){
								$sel_sql = "SELECT id,taskid,forward as sendto,status,hasread,readcount  FROM mr_smtp_task WHERE  id IN({$v}) ".$Total_Time_Sql." GROUP BY id";
								$sel_resoult = $this->dbAdapter->fetchAll($sel_sql);
								$resoult[$iis]['totalsend'] = count($sel_resoult);
								$resoult[$iis]['sendto'] = $k;
								$resoult[$iis]['proportion'] = number_format(count($sel_resoult)/$Tnums*100,1);
								$resoult[$iis]['soft']=array();
								$resoult[$iis]['hard']=array();	
								$resoult[$iis]['success']=array();	
								foreach($sel_resoult as $v1){
									//soft quit
									if($v1['status'] == 5){
										$resoult[$iis]['soft'][]=$v1['status'];
									}
									//hard quit
									if($v1['status'] == 3){
										$resoult[$iis]['hard'][]=$v1['status'];	
									}
									if($v1['status'] == 2){
										$resoult[$iis]['success'][]=$v1['status'];
									}
									$resoult[$iis]['hasread']+=$v1['hasread'];
									$resoult[$iis]['readcount']+=$v1['readcount'];
									//
								}
							}
							$iis++;
						}
						//Receiver Statistics
						foreach($resoult as &$v2){
							$v2['success'] = number_format(count($v2['success'])/$v2['totalsend']*100,1);
							if(is_array($v2['soft'])){
								$v2['soft']=count($v2['soft']);
							}
							if(is_array($v2['hard'])){
								$v2['hard']=count($v2['hard']);
							}
						}
					}
					

					//so
					$Os_Sql = "SELECT COUNT(clientos) AS countos ,id,clientos,SUM(hasread) AS hasread,SUM(readcount) AS readcount FROM mr_smtp_task WHERE taskid IN({$Task_Str}) ".$Total_Time_Sql." GROUP BY clientos";
					$Os_Resoult = $this->dbAdapter->fetchAll($Os_Sql);
					if($Os_Resoult){
					$Os_Array = array(1=>'Windows XP',2=>'Windows 7', 3=>'Windows 8',4=>'Windows NT',5=>'Linux',6=>'Unix',7=>'Ipad', 8=>'Android',9=>'Iphone',10=>'Macintosh',11=>'Other');
						foreach($Os_Resoult as &$vals){
						foreach($Os_Array as $k=>$v){
							if($vals['clientos']==$k && $vals['clientos']!=11){
								$vals['os']=$v;
								$vals['total'] += $vals['countos'];
							}
						}
						if($vals['clientos'] == 0 || $vals['clientos']==11){
								$vals['os'] = $Os_Array[11];
								// $total_other[]=$vals['countos'] ;
								$vals['total']+=$vals['countos'] ;
							}
							$Count_Sum_Array[]=$vals['countos'];
					}

					$Count_Sum=array_sum($Count_Sum_Array);
					$Final_Data = array(0,0,0,0,0,0,0,0,0,0,0);
					foreach($Os_Resoult as $vOne){
						foreach($Final_Data as $k=>$vTwo){
							if(($vOne['clientos']-1)>=0 && ($vOne['clientos']-1) ==$k){
									$Final_Data[$k] = number_format($vOne['total']/$Count_Sum*100,1);

							}
							if(($vOne['clientos']-1)<0){
									$Final_Data[10] = number_format($vOne['total']/$Count_Sum*100,1);
								}
						}
					}
					
					foreach($Final_Data as &$vv){
								$vv =floatval(strval($vv));
						}
						$Os_Str = urldecode(json_encode($Final_Data));
					}

					//sp

					$Sp_Sql = "SELECT COUNT(clientsp) AS countsp ,id,clientsp,SUM(hasread) AS hasread,SUM(readcount) AS readcount FROM mr_smtp_task WHERE taskid IN({$Task_Str}) ".$Total_Time_Sql." GROUP BY clientsp";
				$Sp_Resoult = $this->dbAdapter->fetchAll($Sp_Sql);
				if($Sp_Resoult){
				
				$Sp_Array = array(1=>'联通',2=>'电信', 3=>'方正网络',4=>'零鱼沸点网络',5=>'其他');
					foreach($Sp_Resoult as &$vals){
						foreach($Sp_Array as $k=>$v){
							if($vals['clientsp']==$k && $vals['clientsp']!=5){
								$vals['sp']=$v;
								$vals['total'] += $vals['countsp'];
							}
						}
						if($vals['clientsp'] == 0 || $vals['clientsp']==5){
								$vals['sp'] = $Sp_Array[5];
								$vals['total']+=$vals['countsp'] ;
							}
							$Count_Sp_Sum_Array[]=$vals['countsp'];
					}
					

					$Count_Sp_Sum=array_sum($Count_Sp_Sum_Array);
					$Final_Sp_Data = array(0,0,0,0,0);
					foreach($Sp_Resoult as $vOne){
						foreach($Final_Sp_Data as $k=>$vTwo){
							if(($vOne['clientsp']-1)>=0 && ($vOne['clientsp']-1) ==$k){
									$Final_Sp_Data[$k] = number_format($vOne['total']/$Count_Sp_Sum*100,1);

							}
							if(($vOne['clientsp']-1)<0){
									$Final_Sp_Data[4] = number_format($vOne['total']/$Count_Sp_Sum*100,1);
								}
						}
					}
					
				foreach($Final_Sp_Data as &$vv){
							$vv =floatval(strval($vv));
					}
					$Sp_Str = urldecode(json_encode($Final_Sp_Data));
				}
				


					//browser
				$Br_Sql = "SELECT COUNT(clientbrowser) AS countbrowser ,id,clientbrowser,SUM(hasread) AS hasread,SUM(readcount) AS readcount FROM mr_smtp_task WHERE taskid IN({$Task_Str}) ".$Total_Time_Sql." GROUP BY clientbrowser";
				$Br_Resoult = $this->dbAdapter->fetchAll($Br_Sql);
				if($Br_Resoult){
				$Br_Array = array(1=>'Firefox',2=>'MSIE', 3=>'Chrome',4=>'Safari',5=>'Opera',6=>'Other');
					foreach($Br_Resoult as &$vals){
						foreach($Br_Array as $k=>$v){
							if($vals['clientbrowser']==$k && $vals['clientbrowser']!=6){
								$vals['browser']=$v;
								$vals['total'] += $vals['countbrowser'];
							}
						}
						if($vals['clientbrowser'] == 0 || $vals['clientbrowser']==6){
								$vals['browser'] = $Br_Array[6];
								$vals['total']+=$vals['countbrowser'] ;
							}
							$Count_Br_Sum_Array[]=$vals['countbrowser'];
					}
					$Count_Br_Sum=array_sum($Count_Br_Sum_Array);
					$Final_Br_Data = array(0,0,0,0,0,0);
					foreach($Br_Resoult as $vOne){
						foreach($Final_Br_Data as $k=>$vTwo){
							if(($vOne['clientbrowser']-1)>=0 && ($vOne['clientbrowser']-1) ==$k){
									$Final_Br_Data[$k] = number_format($vOne['total']/$Count_Br_Sum*100,1);

							}
							if(($vOne['clientbrowser']-1)<0){
									$Final_Br_Data[5] = number_format($vOne['total']/$Count_Br_Sum*100,1);
								}
						}
					}
					
				foreach($Final_Br_Data as &$vv){
							$vv =floatval(strval($vv));
					}
					$Br_Str = urldecode(json_encode($Final_Br_Data));
				}

				}
			}

			if(!empty($catdata)){
				foreach($catdata as $data){
					if($data['id'] == $cid){
						$description = "该用户查询了任务分类名称为:".$data['vocation_name']."下的所有任务统计情况";
						$description_en = "This user search task classification name for ".$data['vocation_name']." under all task statistics";
						BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询任务分类统计', $description, 'search task classification', $description_en, $_SERVER["REMOTE_ADDR"]);
					}
				}
			}
			$this->Smarty->assign ("fpage", $fpage);
			$this->Smarty->assign ("catdata", $catdata);
			$this->Smarty->assign ("cid", $cid);
			$this->Smarty->assign ("Final_Areas_Json", $Final_Areas_Json);
			$this->Smarty->assign ("Final_Resoult_Mails", $Final_Resoult_Mails);
			$this->Smarty->assign ("Areas_Datas", $Areas_Datas);
			$this->Smarty->assign ("Receiver_Resoult_Json", $Receiver_Resoult_Json);
			$this->Smarty->assign ("Os_Str", $Os_Str);
			$this->Smarty->assign ("Sp_Str", $Sp_Str);
			$this->Smarty->assign ("Br_Str", $Br_Str);
			$this->Smarty->assign ("Os_Resoult", $Os_Resoult);
			$this->Smarty->assign ("Sp_Resoult", $Sp_Resoult);
			$this->Smarty->assign ("Br_Resoult", $Br_Resoult);
			$this->Smarty->assign ("resoult", $resoult);
			$this->Smarty->assign ("taskresult", $taskresult);
			$this->Smarty->assign ("sendresult", $sendresult);
			$this->Smarty->assign ("backresult", $backresult);
			$this->Smarty->assign ("statusresult", $statusresult);
			$this->Smarty->assign ("taskdata", $taskdata);
			$this->Smarty->assign ("li_menu", "taskclassification");
			$this->Smarty->display ( 'taskclassification.php');	
	}	
	
	function getWeekStartAndEnd ($year,$week=1) {
        header("Content-type:text/html;charset=utf-8");
        date_default_timezone_set("Asia/Shanghai");
        $year = (int)$year;
        $week = (int)$week;
        //按给定的年份计算本年周总数
        $date = new DateTime;
        $date->setISODate($year, 53);
        $weeks = max($date->format("W"),52);
        //如果给定的周数大于周总数或小于等于0
        if($week>$weeks || $week<=0){
            return false;
        }
        //如果周数小于10
        if($week<10){
            $week = '0'.$week;
        }
        //当周起止时间戳
        $timestamp['start'] = strtotime($year.'W'.$week);
        $timestamp['end'] = strtotime('+1 week -1 day',$timestamp['start']);
        //当周起止日期
        $timeymd['start'] = date("Y-m-d",$timestamp['start']);
        $timeymd['end'] = date("Y-m-d",$timestamp['end']);
        
        //返回起始时间戳
        return $timestamp;
        //返回日期形式
        // return $timeymd;
    }
	
	public function releasepersonAction(){
		
		if($_GET['count'] == "count"){
			$where = "" ;
			if($this->_request->get('start_time')){
				$start_time = $this->_request->get('start_time');
				$this->Smarty->assign("start_time",$start_time);
				$where = " and runtime>='".$start_time."'";
			}
			if($where){
				if($this->_request->get('last_time')){
					$last_time = $this->_request->get('last_time');
					$this->Smarty->assign("last_time",$last_time);
					$where .= " and runtime<='".$last_time."'";
				}
			}
			$uid=$this->_request->get('userid');
			$this->Smarty->assign('userid',$uid);
			
			$statisticstime = $this->_request->get('statisticstime');
			$taskid = $this->dbAdapter->fetchAll("select id from mr_task where uid=".$uid);
			if(!empty($taskid)){
				foreach($taskid as $key=>$val){
					$taskids .= $val['id'].",";
				}
				$taskids = rtrim($taskids,",");
			}
			if($taskids != ""){
				$where .= " and taskid in (".$taskids.")";
				if($statisticstime == 1){
					$arr_total = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,COUNT(id) COUNT FROM mr_smtp_task where 1=1 ".$where." GROUP BY days");
					$arr_soft_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,COUNT(id) soft_failure FROM mr_smtp_task where 1=1 and status=5 ".$where." GROUP BY days");
					$arr_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,COUNT(id) failure FROM mr_smtp_task where 1=1 and status=3 ".$where." GROUP BY days");
					$arr_done = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,COUNT(id) done FROM mr_smtp_task where 1=1 and status=2 ".$where." GROUP BY days");
					$arr_open = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,COUNT(id) open FROM mr_smtp_task where 1=1 and hasread=1 ".$where." GROUP BY days");
					$arr_skip = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,SUM(`readcount`) skip FROM mr_smtp_task where 1=1 and hasread=1 ".$where." GROUP BY days");
					$arr_unsubscribe = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,count(id) unsubscribe FROM mr_smtp_task where 1=1 and unsubscribe=1 ".$where." GROUP BY days");
					$arr_trashmail = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,count(id) trashmail FROM mr_smtp_task where 1=1 and trashmail=1 ".$where." GROUP BY days");
					$arr_total = $this->changeKey('days',$arr_total);
					$arr_soft_failure = $this->changeKey('days',$arr_soft_failure);
					$arr_failure = $this->changeKey('days',$arr_failure);
					$arr_done = $this->changeKey('days',$arr_done);
					$arr_open = $this->changeKey('days',$arr_open);
					$arr_skip = $this->changeKey('days',$arr_skip);
					$arr_unsubscribe = $this->changeKey('days',$arr_unsubscribe);
					$arr_trashmail = $this->changeKey('days',$arr_trashmail);
					if(!empty($arr_total)){
						foreach($arr_total as $key=>$val){
							$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_soft_failure[$key],(array)$arr_failure[$key],(array)$arr_done[$key],(array)$arr_open[$key],(array)$arr_skip[$key],(array)$arr_unsubscribe[$key],(array)$arr_trashmail[$key]);
						}
					}
				}else if($statisticstime == 2){
					$arr_total = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,COUNT(id) COUNT FROM mr_smtp_task where 1=1 ".$where." GROUP BY weeks");
					$arr_soft_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,COUNT(id) soft_failure FROM mr_smtp_task where 1=1 and status=5 ".$where." GROUP BY weeks");
					$arr_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,COUNT(id) failure FROM mr_smtp_task where 1=1 and status=3 ".$where." GROUP BY weeks");
					$arr_done = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,COUNT(id) done FROM mr_smtp_task where 1=1 and status=2 ".$where." GROUP BY weeks");
					$arr_open = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,COUNT(id) open FROM mr_smtp_task where 1=1 and hasread=1 ".$where." GROUP BY weeks");
					$arr_skip = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,SUM(`readcount`) skip FROM mr_smtp_task where 1=1 and hasread=1 ".$where." GROUP BY weeks");
					$arr_unsubscribe = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,count(id) unsubscribe FROM mr_smtp_task where 1=1 and unsubscribe=1 ".$where." GROUP BY weeks");
					$arr_trashmail = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,count(id) trashmail FROM mr_smtp_task where 1=1 and trashmail=1 ".$where." GROUP BY weeks");
					$arr_total = $this->changeKey('weeks',$arr_total);
					$arr_soft_failure = $this->changeKey('weeks',$arr_soft_failure);
					$arr_failure = $this->changeKey('weeks',$arr_failure);
					$arr_done = $this->changeKey('weeks',$arr_done);
					$arr_open = $this->changeKey('weeks',$arr_open);
					$arr_skip = $this->changeKey('weeks',$arr_skip);
					$arr_unsubscribe = $this->changeKey('weeks',$arr_unsubscribe);
					$arr_trashmail = $this->changeKey('weeks',$arr_trashmail);
					if(!empty($arr_total)){
						foreach($arr_total as $key=>$val){
							$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_soft_failure[$key],(array)$arr_failure[$key],(array)$arr_done[$key],(array)$arr_open[$key],(array)$arr_skip[$key],(array)$arr_unsubscribe[$key],(array)$arr_trashmail[$key]);
						}
					}
				}else if($statisticstime == 3){
					$arr_total = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,COUNT(id) COUNT FROM mr_smtp_task where 1=1 ".$where." GROUP BY months");
					$arr_soft_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,COUNT(id) soft_failure FROM mr_smtp_task where 1=1 and status=5 ".$where." GROUP BY months");
					$arr_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,COUNT(id) failure FROM mr_smtp_task where 1=1 and status=3 ".$where." GROUP BY months");
					$arr_done = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,COUNT(id) done FROM mr_smtp_task where 1=1 and status=2 ".$where." GROUP BY months");
					$arr_open = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,COUNT(id) open FROM mr_smtp_task where 1=1 and hasread=1 ".$where." GROUP BY months");
					$arr_skip = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,SUM(`readcount`) skip FROM mr_smtp_task where 1=1 and hasread=1 ".$where." GROUP BY months");
					$arr_unsubscribe = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,count(id) unsubscribe FROM mr_smtp_task where 1=1 and unsubscribe=1 ".$where." GROUP BY months");
					$arr_trashmail = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,count(id) trashmail FROM mr_smtp_task where 1=1 and trashmail=1 ".$where." GROUP BY months");
					$arr_total = $this->changeKey('months',$arr_total);
					$arr_soft_failure = $this->changeKey('months',$arr_soft_failure);
					$arr_failure = $this->changeKey('months',$arr_failure);
					$arr_done = $this->changeKey('months',$arr_done);
					$arr_open = $this->changeKey('months',$arr_open);
					$arr_skip = $this->changeKey('months',$arr_skip);
					$arr_unsubscribe = $this->changeKey('months',$arr_unsubscribe);
					$arr_trashmail = $this->changeKey('months',$arr_trashmail);
					if(!empty($arr_total)){
						foreach($arr_total as $key=>$val){
							$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_soft_failure[$key],(array)$arr_failure[$key],(array)$arr_done[$key],(array)$arr_open[$key],(array)$arr_skip[$key],(array)$arr_unsubscribe[$key],(array)$arr_trashmail[$key]);
						}
					}
				}else if($statisticstime == 4){
					$arr_total = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,COUNT(id) COUNT FROM mr_smtp_task where 1=1 ".$where." GROUP BY QUARTER(runtime)");
					$arr_soft_failure = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,COUNT(id) soft_failure FROM mr_smtp_task where 1=1 and status=5 ".$where." GROUP BY QUARTER(runtime)");
					$arr_failure = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,COUNT(id) failure FROM mr_smtp_task where 1=1 and status=3 ".$where." GROUP BY QUARTER(runtime)");
					$arr_done = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,COUNT(id) done FROM mr_smtp_task where 1=1 and status=2 ".$where." GROUP BY QUARTER(runtime)");
					$arr_open = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,COUNT(id) open FROM mr_smtp_task where 1=1 and hasread=1 ".$where." GROUP BY QUARTER(runtime)");
					$arr_skip = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,SUM(`readcount`) skip FROM mr_smtp_task where 1=1 and hasread=1 ".$where." GROUP BY QUARTER(runtime)");
					$arr_unsubscribe = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,count(id) unsubscribe FROM mr_smtp_task where 1=1 and unsubscribe=1 ".$where." GROUP BY QUARTER(runtime)");
					$arr_trashmail = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,count(id) trashmail FROM mr_smtp_task where 1=1 and trashmail=1 ".$where." GROUP BY QUARTER(runtime)");
					if(!empty($arr_total)){
						foreach($arr_total as $key=>$val){
							$arr_total[$key]['quarter'] = $val['year']."-".$val['quarter'];
						}
					}
					if(!empty($arr_soft_failure)){
						foreach($arr_soft_failure as $key=>$val){
							$arr_soft_failure[$key]['quarter'] = $val['year']."-".$val['quarter'];
						}
					}
					if(!empty($arr_failure)){
						foreach($arr_failure as $key=>$val){
							$arr_failure[$key]['quarter'] = $val['year']."-".$val['quarter'];
						}
					}
					if(!empty($arr_done)){
						foreach($arr_done as $key=>$val){
							$arr_done[$key]['quarter'] = $val['year']."-".$val['quarter'];
						}
					}
					if(!empty($arr_open)){
						foreach($arr_open as $key=>$val){
							$arr_open[$key]['quarter'] = $val['year']."-".$val['quarter'];
						}
					}
					if(!empty($arr_skip)){
						foreach($arr_skip as $key=>$val){
							$arr_skip[$key]['quarter'] = $val['year']."-".$val['quarter'];
						}
					}
					if(!empty($arr_unsubscribe)){
						foreach($arr_unsubscribe as $key=>$val){
							$arr_unsubscribe[$key]['quarter'] = $val['year']."-".$val['quarter'];
						}
					}
					if(!empty($arr_trashmail)){
						foreach($arr_trashmail as $key=>$val){
							$arr_trashmail[$key]['quarter'] = $val['year']."-".$val['quarter'];
						}
					}
					$arr_total = $this->changeKey('months',$arr_total);
					$arr_soft_failure = $this->changeKey('months',$arr_soft_failure);
					$arr_failure = $this->changeKey('months',$arr_failure);
					$arr_done = $this->changeKey('months',$arr_done);
					$arr_open = $this->changeKey('months',$arr_open);
					$arr_skip = $this->changeKey('months',$arr_skip);
					$arr_unsubscribe = $this->changeKey('months',$arr_unsubscribe);
					$arr_trashmail = $this->changeKey('months',$arr_trashmail);
					if(!empty($arr_total)){
						foreach($arr_total as $key=>$val){
							$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_soft_failure[$key],(array)$arr_failure[$key],(array)$arr_done[$key],(array)$arr_open[$key],(array)$arr_skip[$key],(array)$arr_unsubscribe[$key],(array)$arr_trashmail);
						}
					}
				}else{
					$arr_total = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,COUNT(id) COUNT FROM mr_smtp_task where 1=1 ".$where." GROUP BY years");
					$arr_soft_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,COUNT(id) soft_failure FROM mr_smtp_task where 1=1 ".$where." and status=5 GROUP BY years");
					$arr_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,COUNT(id) failure FROM mr_smtp_task where 1=1 ".$where." and status=3 GROUP BY years");
					$arr_done = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,COUNT(id) done FROM mr_smtp_task where 1=1 ".$where." and status=2 GROUP BY years");
					$arr_open = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,COUNT(id) open FROM mr_smtp_task where 1=1 ".$where." and hasread=1 GROUP BY years");
					$arr_skip = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,SUM(`readcount`) skip FROM mr_smtp_task where 1=1 ".$where." and hasread=1 GROUP BY years");
					$arr_unsubscribe = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,count(id) unsubscribe FROM mr_smtp_task where 1=1 and unsubscribe=1 ".$where." GROUP BY years");
					$arr_trashmail = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,count(id) trashmail FROM mr_smtp_task where 1=1 and trashmail=1 ".$where." GROUP BY years");
					$arr_total = $this->changeKey('years',$arr_total);
					$arr_soft_failure = $this->changeKey('years',$arr_soft_failure);
					$arr_failure = $this->changeKey('years',$arr_failure);
					$arr_done = $this->changeKey('years',$arr_done);
					$arr_open = $this->changeKey('years',$arr_open);
					$arr_skip = $this->changeKey('years',$arr_skip);
					$arr_unsubscribe = $this->changeKey('years',$arr_unsubscribe);
					$arr_trashmail = $this->changeKey('years',$arr_trashmail);
					if(!empty($arr_total)){
						foreach($arr_total as $key=>$val){
							$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_soft_failure[$key],(array)$arr_failure[$key],(array)$arr_done[$key],(array)$arr_open[$key],(array)$arr_skip[$key],(array)$arr_unsubscribe[$key],(array)$arr_trashmail[$key]);
						}
					}
				}
				
				$this->Smarty->assign ("taskdata", $taskdata);
				if(!empty($infos)){
					foreach($infos as $key=>$val){
						$year = substr($val['weeks'],0,4);
						$weekth = substr($val['weeks'],4,2);
						$week = self::getWeekStartAndEnd($year,$weekth);
						$start = date("Y-m-d",$week['start']);
						$end = date("Y-m-d",$week['end']);
						if(date("Y-m-d",$week['start'])<$year."-01-01"){
							$start = $year."-01-01";
						}
						$infos[$key]['time'] = $start."至".$end;
					}
				}
				$this->Smarty->assign("infos",$infos);
			}
		}
		$this->Smarty->assign('statisticstime',$statisticstime);
		
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$where=' ';
		if($role == 'sadmin' || $role == 'admin') {
			$where=" 1=1 and (role = 'stasker' or role = 'tasker') ";
		}elseif($role == 'stasker'){
			$userinfo = $this->account->getSTaskerUsers($userid);
			 if(!empty($userinfo)) {
                foreach ($userinfo as $val) {
                    $id[] = $val['id'];
                }
            }
            $str = implode(",", $id);
            $where = "  1=1 and id in(" . $str . ")";
		}else{
			$where="  1=1 and id = ".$userid." ";
		}
		$uesrSql = "select * from mr_accounts where ".$where;
		$userdata=$this->statistics->getAllUsers($uesrSql);
		if(!empty($userdata)){
			foreach($userdata as $data){
				if($data['id'] == $uid){
					$description = "该用户查询了任务发布员为:".$data['username']."下的所有任务统计情况";
					$description_en = "This user search task released the name for ".$data['username']." under all task statistics";
					BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询任务发布员统计', $description, 'search task released', $description_en, $_SERVER["REMOTE_ADDR"]);
				}
			}
		}
		
		$this->Smarty->assign ("userdata", $userdata);
		$this->Smarty->assign ("li_menu", "releaseperson");
		$this->Smarty->display ( 'releaseperson.php');
	}
	
	public function changeKey($str,$arr){
		if(!empty($arr)){
			foreach($arr as $key=>$val){
				$brr[$val[$str]] = $arr[$key];
			}
		}
		return $brr;
	}
	
	public function alltaskstatisticsAction(){
		if($_GET['count'] == "count"){
			$where = "" ;
			$userid = $this->getCurrentUserID();
			$role = $this->getCurrentUserRole();
			$uname = $this->getCurrentUser();
			$wh=self::checkLoginAudit($role);
			$wh .= " and ";
			$sql_task="select id from `mr_task` where ".$wh." draft<>1 and (status = 3 or status = 4 or status = 5 or status = 7 or status= 8) order by createtime desc";
			$result=$this->statistics->selectAllTask($sql_task);
			if($result){
				$taskids='';
				 foreach($result as $val){
					if($val['id']){
						$taskids .= $val['id'].',';
						
					}
				}
				
				$where =" and taskid in(".rtrim($taskids,',').")";
			}
			if($this->_request->get('start_time')){
				$start_time = $this->_request->get('start_time');
				$this->Smarty->assign("start_time",$start_time);
				$where .= " and runtime>='".$start_time."'";
			}
			if($where){
				if($this->_request->get('last_time')){
					$last_time = $this->_request->get('last_time');
					$this->Smarty->assign("last_time",$last_time);
					$where .= " and runtime<='".$last_time."'";
				}
			}

			$statisticstime = $this->_request->get('statisticstime');
			if($statisticstime == 1){
				$arr_total = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,COUNT(id) COUNT FROM mr_smtp_task where 1=1 and taskid>0 ".$where." GROUP BY days");
				$arr_soft_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,COUNT(id) soft_failure FROM mr_smtp_task where 1=1 and taskid>0 and status=5 ".$where." GROUP BY days");
				$arr_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,COUNT(id) failure FROM mr_smtp_task where 1=1 and taskid>0 and status=3 ".$where." GROUP BY days");
				$arr_done = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,COUNT(id) done FROM mr_smtp_task where 1=1 and taskid>0 and status=2 ".$where." GROUP BY days");
				$arr_open = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,COUNT(id) open FROM mr_smtp_task where 1=1 and taskid>0 and hasread=1 ".$where." GROUP BY days");
				$arr_skip = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,SUM(`readcount`) skip FROM mr_smtp_task where 1=1 and taskid>0 and hasread=1 ".$where." GROUP BY days");
				$arr_unsubscribe = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,count(id) unsubscribe FROM mr_smtp_task where 1=1 and taskid>0 and unsubscribe=1 ".$where." GROUP BY days");
				$arr_trashmail = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,count(id) trashmail FROM mr_smtp_task where 1=1 and taskid>0 and trashmail=1 ".$where." GROUP BY days");
				$arr_total = $this->changeKey('days',$arr_total);
				$arr_soft_failure = $this->changeKey('days',$arr_soft_failure);
				$arr_failure = $this->changeKey('days',$arr_failure);
				$arr_done = $this->changeKey('days',$arr_done);
				$arr_open = $this->changeKey('days',$arr_open);
				$arr_skip = $this->changeKey('days',$arr_skip);
				$arr_unsubscribe = $this->changeKey('days',$arr_unsubscribe);
				$arr_trashmail = $this->changeKey('days',$arr_trashmail);
				if(!empty($arr_total)){
					foreach($arr_total as $key=>$val){
						$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_soft_failure[$key],(array)$arr_failure[$key],(array)$arr_done[$key],(array)$arr_open[$key],(array)$arr_skip[$key],(array)$arr_unsubscribe[$key],(array)$arr_trashmail[$key]);
					}
				}
			}else if($statisticstime == 2){
				$arr_total = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,COUNT(id) COUNT FROM mr_smtp_task where 1=1 and taskid>0 ".$where." GROUP BY weeks");
				$arr_soft_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,COUNT(id) soft_failure FROM mr_smtp_task where 1=1 and taskid>0 and status=5 ".$where." GROUP BY weeks");
				$arr_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,COUNT(id) failure FROM mr_smtp_task where 1=1 and taskid>0 and status=3 ".$where." GROUP BY weeks");
				$arr_done = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,COUNT(id) done FROM mr_smtp_task where 1=1 and taskid>0 and status=2 ".$where." GROUP BY weeks");
				$arr_open = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,COUNT(id) open FROM mr_smtp_task where 1=1 and taskid>0 and hasread=1 ".$where." GROUP BY weeks");
				$arr_skip = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,SUM(`readcount`) skip FROM mr_smtp_task where 1=1 and taskid>0 and hasread=1 ".$where." GROUP BY weeks");
				$arr_unsubscribe = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,count(id) unsubscribe FROM mr_smtp_task where 1=1 and taskid>0 and unsubscribe=1 ".$where." GROUP BY weeks");
				$arr_trashmail = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,count(id) trashmail FROM mr_smtp_task where 1=1 and taskid>0 and trashmail=1 ".$where." GROUP BY weeks");
				$arr_total = $this->changeKey('weeks',$arr_total);
				$arr_soft_failure = $this->changeKey('weeks',$arr_soft_failure);
				$arr_failure = $this->changeKey('weeks',$arr_failure);
				$arr_done = $this->changeKey('weeks',$arr_done);
				$arr_open = $this->changeKey('weeks',$arr_open);
				$arr_skip = $this->changeKey('weeks',$arr_skip);
				$arr_unsubscribe = $this->changeKey('weeks',$arr_unsubscribe);
				$arr_trashmail = $this->changeKey('weeks',$arr_trashmail);
				if(!empty($arr_total)){
					foreach($arr_total as $key=>$val){
						$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_soft_failure[$key],(array)$arr_failure[$key],(array)$arr_done[$key],(array)$arr_open[$key],(array)$arr_skip[$key],(array)$arr_unsubscribe[$key],(array)$arr_trashmail[$key]);
					}
				}
			}else if($statisticstime == 3){
				$arr_total = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,COUNT(id) COUNT FROM mr_smtp_task where 1=1 and taskid>0 ".$where." GROUP BY months");
				$arr_soft_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,COUNT(id) soft_failure FROM mr_smtp_task where 1=1 and taskid>0 and status=5 ".$where." GROUP BY months");
				$arr_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,COUNT(id) failure FROM mr_smtp_task where 1=1 and taskid>0 and status=3 ".$where." GROUP BY months");
				$arr_done = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,COUNT(id) done FROM mr_smtp_task where 1=1 and taskid>0 and status=2 ".$where." GROUP BY months");
				$arr_open = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,COUNT(id) open FROM mr_smtp_task where 1=1 and taskid>0 and hasread=1 ".$where." GROUP BY months");
				$arr_skip = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,SUM(`readcount`) skip FROM mr_smtp_task where 1=1 and taskid>0 and hasread=1 ".$where." GROUP BY months");
				$arr_unsubscribe = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,count(id) unsubscribe FROM mr_smtp_task where 1=1 and taskid>0 and unsubscribe=1 ".$where." GROUP BY months");
				$arr_trashmail = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,count(id) trashmail FROM mr_smtp_task where 1=1 and taskid>0 and trashmail=1 ".$where." GROUP BY months");
				$arr_total = $this->changeKey('months',$arr_total);
				$arr_soft_failure = $this->changeKey('months',$arr_soft_failure);
				$arr_failure = $this->changeKey('months',$arr_failure);
				$arr_done = $this->changeKey('months',$arr_done);
				$arr_open = $this->changeKey('months',$arr_open);
				$arr_skip = $this->changeKey('months',$arr_skip);
				$arr_unsubscribe = $this->changeKey('months',$arr_unsubscribe);
				$arr_trashmail = $this->changeKey('months',$arr_trashmail);
				if(!empty($arr_total)){
					foreach($arr_total as $key=>$val){
						$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_soft_failure[$key],(array)$arr_failure[$key],(array)$arr_done[$key],(array)$arr_open[$key],(array)$arr_skip[$key],(array)$arr_unsubscribe[$key],(array)$arr_trashmail[$key]);
					}
				}
			}else if($statisticstime == 4){
				$arr_total = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,COUNT(id) COUNT FROM mr_smtp_task where 1=1 and taskid>0 ".$where." GROUP BY QUARTER(runtime)");
				$arr_soft_failure = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,COUNT(id) soft_failure FROM mr_smtp_task where 1=1 and taskid>0 and status=5 ".$where." GROUP BY QUARTER(runtime)");
				$arr_failure = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,COUNT(id) failure FROM mr_smtp_task where 1=1 and taskid>0 and status=3 ".$where." GROUP BY QUARTER(runtime)");
				$arr_done = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,COUNT(id) done FROM mr_smtp_task where 1=1 and taskid>0 and status=2 ".$where." GROUP BY QUARTER(runtime)");
				$arr_open = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,COUNT(id) open FROM mr_smtp_task where 1=1 and taskid>0 and hasread=1 ".$where." GROUP BY QUARTER(runtime)");
				$arr_skip = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,SUM(`readcount`) skip FROM mr_smtp_task where 1=1 and taskid>0 and hasread=1 ".$where." GROUP BY QUARTER(runtime)");
				$arr_unsubscribe = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,count(id) unsubscribe FROM mr_smtp_task where 1=1 and taskid>0 and unsubscribe=1 ".$where." GROUP BY QUARTER(runtime)");
				$arr_trashmail = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,count(id) trashmail FROM mr_smtp_task where 1=1 and taskid>0 and trashmail=1 ".$where." GROUP BY QUARTER(runtime)");
				if(!empty($arr_total)){
					foreach($arr_total as $key=>$val){
						$arr_total[$key]['quarter'] = $val['year']."-".$val['quarter'];
					}
				}
				if(!empty($arr_soft_failure)){
					foreach($arr_soft_failure as $key=>$val){
						$arr_soft_failure[$key]['quarter'] = $val['year']."-".$val['quarter'];
					}
				}
				if(!empty($arr_failure)){
					foreach($arr_failure as $key=>$val){
						$arr_failure[$key]['quarter'] = $val['year']."-".$val['quarter'];
					}
				}
				if(!empty($arr_done)){
					foreach($arr_done as $key=>$val){
						$arr_done[$key]['quarter'] = $val['year']."-".$val['quarter'];
					}
				}
				if(!empty($arr_open)){
					foreach($arr_open as $key=>$val){
						$arr_open[$key]['quarter'] = $val['year']."-".$val['quarter'];
					}
				}
				if(!empty($arr_skip)){
					foreach($arr_skip as $key=>$val){
						$arr_skip[$key]['quarter'] = $val['year']."-".$val['quarter'];
					}
				}
				if(!empty($arr_unsubscribe)){
					foreach($arr_unsubscribe as $key=>$val){
						$arr_unsubscribe[$key]['quarter'] = $val['year']."-".$val['quarter'];
					}
				}
				if(!empty($arr_trashmail)){
					foreach($arr_trashmail as $key=>$val){
						$arr_trashmail[$key]['quarter'] = $val['year']."-".$val['quarter'];
					}
				}
				$arr_total = $this->changeKey('months',$arr_total);
				$arr_soft_failure = $this->changeKey('months',$arr_soft_failure);
				$arr_failure = $this->changeKey('months',$arr_failure);
				$arr_done = $this->changeKey('months',$arr_done);
				$arr_open = $this->changeKey('months',$arr_open);
				$arr_skip = $this->changeKey('months',$arr_skip);
				$arr_unsubscribe = $this->changeKey('months',$arr_unsubscribe);
				$arr_trashmail = $this->changeKey('months',$arr_trashmail);
				if(!empty($arr_total)){
					foreach($arr_total as $key=>$val){
						$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_soft_failure[$key],(array)$arr_failure[$key],(array)$arr_done[$key],(array)$arr_open[$key],(array)$arr_skip[$key],(array)$arr_unsubscribe[$key],(array)$arr_trashmail[$key]);
					}
				}
			}else{
				$arr_total = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,COUNT(id) COUNT FROM mr_smtp_task where 1=1 and taskid>0 ".$where." GROUP BY years");
				$arr_soft_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,COUNT(id) soft_failure FROM mr_smtp_task where status=5 and taskid>0 ".$where." GROUP BY years");
				$arr_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,COUNT(id) failure FROM mr_smtp_task where status=3 and taskid>0 ".$where." GROUP BY years");
				$arr_done = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,COUNT(id) done FROM mr_smtp_task where status=2 and taskid>0 ".$where." GROUP BY years");
				$arr_open = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,COUNT(id) open FROM mr_smtp_task where hasread=1 and taskid>0 ".$where." GROUP BY years");
				$arr_skip = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,SUM(`readcount`) skip FROM mr_smtp_task where hasread=1 and taskid>0 ".$where." GROUP BY years");
				$arr_unsubscribe = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,count(id) unsubscribe FROM mr_smtp_task where 1=1 and taskid>0 and unsubscribe=1 ".$where." GROUP BY years");
				$arr_trashmail = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,count(id) trashmail FROM mr_smtp_task where 1=1 and taskid>0 and trashmail=1 ".$where." GROUP BY years");
				$arr_total = $this->changeKey('years',$arr_total);
				$arr_soft_failure = $this->changeKey('years',$arr_soft_failure);
				$arr_failure = $this->changeKey('years',$arr_failure);
				$arr_done = $this->changeKey('years',$arr_done);
				$arr_open = $this->changeKey('years',$arr_open);
				$arr_skip = $this->changeKey('years',$arr_skip);
				$arr_unsubscribe = $this->changeKey('years',$arr_unsubscribe);
				$arr_trashmail = $this->changeKey('years',$arr_trashmail);
				if(!empty($arr_total)){
					foreach($arr_total as $key=>$val){
						$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_soft_failure[$key],(array)$arr_failure[$key],(array)$arr_done[$key],(array)$arr_open[$key],(array)$arr_skip[$key],(array)$arr_unsubscribe[$key],(array)$arr_trashmail[$key]);
					}
				}
			}
			$this->Smarty->assign('statisticstime',$statisticstime);
			if($statisticstime == 1){
				$condition="按日统计";
			}elseif($statisticstime == 2){
				$condition="按周统计";
			}elseif($statisticstime == 3){
				$condition="按月统计";
			}elseif($statisticstime == 5){
				$condition="按年统计";
			}
			$description = "该用户查询了按全部任务统计下的".$condition."统计情况";
			$description_en = "This user search all task statistics ".$condition." all tasks under the statistics";
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询全部任务统计', $description, 'search all task statistics', $description_en, $_SERVER["REMOTE_ADDR"]);
		}
		if(!empty($infos)){
			foreach($infos as $key=>$val){
				$year = substr($val['weeks'],0,4);
				$weekth = substr($val['weeks'],4,2);
				$week = self::getWeekStartAndEnd($year,$weekth);
				$start = date("Y-m-d",$week['start']);
				$end = date("Y-m-d",$week['end']);
				if(date("Y-m-d",$week['start'])<$year."-01-01"){
					$start = $year."-01-01";
				}
				$infos[$key]['time'] = $start."至".$end;
			}
		}
		$this->Smarty->assign("infos",$infos);
		$this->Smarty->assign ("li_menu", "alltaskstatistics");
		$this->Smarty->display ( 'alltaskstatistics.php');	
	}
	
	public function allforwardstatisticsAction(){
		if($_GET['count'] == "count"){
			$where = "" ;
			if($this->_request->get('start_time')){
				$start_time = $this->_request->get('start_time');
				$this->Smarty->assign("start_time",$start_time);
				$where = " and runtime>='".$start_time."'";
			}
			if($where){
				if($this->_request->get('last_time')){
					$last_time = $this->_request->get('last_time');
					$this->Smarty->assign("last_time",$last_time);
					$where .= " and runtime<'".$last_time."'";
				}
			}
			
			$statisticstime = $this->_request->get('statisticstime');
			if($statisticstime == 1){
				$arr_total = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,COUNT(id) COUNT FROM mr_smtp_task where 1=1 and taskid<=0 ".$where." GROUP BY days");
				$arr_success = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,COUNT(id) success FROM mr_smtp_task where 1=1 and taskid<=0 and status=2 ".$where." GROUP BY days");
				$arr_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m%d') days,COUNT(id) failure FROM mr_smtp_task where 1=1 and taskid<=0 and status in (3,5,6) ".$where." GROUP BY days");
			
				$arr_total = $this->changeKey('days',$arr_total);
				$arr_success = $this->changeKey('days',$arr_success);
				$arr_failure = $this->changeKey('days',$arr_failure);
			
				if(!empty($arr_total)){
					foreach($arr_total as $key=>$val){
						
						$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_success[$key],(array)$arr_failure[$key]);
						if($arr_total[$key]['COUNT'] != 0){
							$infos[$key]['success_percent'] = round($arr_success[$key]['success']/$arr_total[$key]['COUNT']*100,2)."%";
							$infos[$key]['failure_percent'] = round($arr_failure[$key]['failure']/$arr_total[$key]['COUNT']*100,2)."%";
						}
					}
				}
			}else if($statisticstime == 2){
				$arr_total = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,COUNT(id) COUNT FROM mr_smtp_task where 1=1 and taskid<=0 ".$where." GROUP BY weeks");
				$arr_success = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,COUNT(id) success FROM mr_smtp_task where 1=1 and taskid<=0 and status=2 ".$where." GROUP BY weeks");
				$arr_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%u') weeks,COUNT(id) failure FROM mr_smtp_task where 1=1 and taskid<=0 and status in (3,5,6) ".$where." GROUP BY weeks");
			
				$arr_total = $this->changeKey('weeks',$arr_total);
				$arr_success = $this->changeKey('weeks',$arr_success);
				$arr_failure = $this->changeKey('weeks',$arr_failure);
				
				if(!empty($arr_total)){
					foreach($arr_total as $key=>$val){
						$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_success[$key],(array)$arr_failure[$key]);
						if($arr_total[$key]['COUNT'] != 0){
							$infos[$key]['success_percent'] = round($arr_success[$key]['success']/$arr_total[$key]['COUNT']*100,2)."%";
							$infos[$key]['failure_percent'] = round($arr_failure[$key]['failure']/$arr_total[$key]['COUNT']*100,2)."%";
						}
					}
				}
			}else if($statisticstime == 3){
				$arr_total = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,COUNT(id) COUNT FROM mr_smtp_task where 1=1 and taskid<=0 ".$where." GROUP BY months");
				$arr_success = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,COUNT(id) success FROM mr_smtp_task where 1=1 and taskid<=0 and status=2 ".$where." GROUP BY months");
				$arr_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y%m') months,COUNT(id) failure FROM mr_smtp_task where 1=1 and taskid<=0 and status in (3,5,6) ".$where." GROUP BY months");
				
				$arr_total = $this->changeKey('months',$arr_total);
				$arr_success = $this->changeKey('months',$arr_success);
				$arr_failure = $this->changeKey('months',$arr_failure);
				
				if(!empty($arr_total)){
					foreach($arr_total as $key=>$val){
						$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_success[$key],(array)$arr_failure[$key]);
						if($arr_total[$key]['COUNT'] != 0){
							$infos[$key]['success_percent'] = round($arr_success[$key]['success']/$arr_total[$key]['COUNT']*100,2)."%";
							$infos[$key]['failure_percent'] = round($arr_failure[$key]['failure']/$arr_total[$key]['COUNT']*100,2)."%";
						}
					}
				}
			}else if($statisticstime == 4){
				$arr_total = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,COUNT(id) COUNT FROM mr_smtp_task where 1=1 and taskid<=0 ".$where." GROUP BY QUARTER(runtime)");
				$arr_success = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,COUNT(id) success FROM mr_smtp_task where 1=1 and taskid<=0 and status=2 ".$where." GROUP BY QUARTER(runtime)");
				$arr_failure = $this->dbAdapter->fetchAll("SELECT QUARTER(runtime) quarter ,DATE_FORMAT(runtime,'%Y') year,COUNT(id) failure FROM mr_smtp_task where 1=1 and taskid<=0 and status in (3,5,6) ".$where." GROUP BY QUARTER(runtime)");
				
				if(!empty($arr_total)){
					foreach($arr_total as $key=>$val){
						$arr_total[$key]['quarter'] = $val['year']."-".$val['quarter'];
					}
				}
				if(!empty($arr_success)){
					foreach($arr_success as $key=>$val){
						$arr_success[$key]['quarter'] = $val['year']."-".$val['quarter'];
					}
				}
				if(!empty($arr_failure)){
					foreach($arr_failure as $key=>$val){
						$arr_failure[$key]['quarter'] = $val['year']."-".$val['quarter'];
					}
				}
			
				$arr_total = $this->changeKey('months',$arr_total);
				$arr_success = $this->changeKey('months',$arr_success);
				$arr_failure = $this->changeKey('months',$arr_failure);
				
				if(!empty($arr_total)){
					foreach($arr_total as $key=>$val){
						$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_success[$key],(array)$arr_failure[$key]);
					}
				}
			}else{
				$arr_total = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,COUNT(id) COUNT FROM mr_smtp_task where taskid<=0 GROUP BY years");
				$arr_success = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,COUNT(id) success FROM mr_smtp_task  where taskid<=0 and status=2 GROUP BY years");
				$arr_failure = $this->dbAdapter->fetchAll("SELECT DATE_FORMAT(runtime,'%Y') years,COUNT(id) failure FROM mr_smtp_task   where taskid<=0 and status in (3,5,6) GROUP BY years");
	
				$arr_total = $this->changeKey('years',$arr_total);
				$arr_success = $this->changeKey('years',$arr_success);
				$arr_failure = $this->changeKey('years',$arr_failure);
				
				if(!empty($arr_total)){
					foreach($arr_total as $key=>$val){
						$infos[$key] = array_merge((array)$arr_total[$key],(array)$arr_success[$key],(array)$arr_failure[$key]);
						if($arr_total[$key]['COUNT'] != 0){
							$infos[$key]['success_percent'] = round($arr_success[$key]['success']/$arr_total[$key]['COUNT']*100,2)."%";
							$infos[$key]['failure_percent'] = round($arr_failure[$key]['failure']/$arr_total[$key]['COUNT']*100,2)."%";
						}
					}
				}
			}
			$this->Smarty->assign('statisticstime',$statisticstime);
			$userid = $this->getCurrentUserID();
			$role = $this->getCurrentUserRole();
			$uname = $this->getCurrentUser();
			if($statisticstime == 1){
				$condition="按日统计";
			}elseif($statisticstime == 2){
				$condition="按周统计";
			}elseif($statisticstime == 3){
				$condition="按月统计";
			}elseif($statisticstime == 5){
				$condition="按年统计";
			}
			$description = "该用户查询了按全部转发统计下的".$condition."统计情况";
			$description_en = "This user search all forwarding statistics ".$condition." all tasks under the statistics";
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询全部转发统计', $description, 'search all forwarding statistics', $description_en, $_SERVER["REMOTE_ADDR"]);
		}
		if(!empty($infos)){
			foreach($infos as $key=>$val){
				$year = substr($val['weeks'],0,4);
				$weekth = substr($val['weeks'],4,2);
				$week = self::getWeekStartAndEnd($year,$weekth);
				$start = date("Y-m-d",$week['start']);
				$end = date("Y-m-d",$week['end']);
				if(date("Y-m-d",$week['start'])<$year."-01-01"){
					$start = $year."-01-01";
				}
				$infos[$key]['time'] = $start."至".$end;
			}
		}
		$this->Smarty->assign("infos",$infos);
		
		$this->Smarty->assign ("li_menu", "allforwardstatistics");
		$this->Smarty->display ( 'allforwardstatistics.php');	
	}	
	
	public function selectTaskInfo($where){
		$sql="select id,task_name,status,subject,sender,replyemail,sendtime,total,createtime from `mr_task` where ".$where." and draft<>1 and (status = 3 or status = 4 or status = 5 or status = 7 or status= 8) order by createtime desc limit 1";
		$result=$this->statistics->selectAllTask($sql);
		return $result;
	}
	
	public function sendMailSuccess($taskid,$tlid){
		$where = $tlid == 'all' ? '' : ' AND tlid = '.$tlid;
		$result['total']=$this->statistics->getSendNum("SELECT count(*) as num FROM `mr_smtp_task` WHERE taskid = ".$taskid." ".$where);
		$result['success']=$this->statistics->getSendNum("SELECT count(id) as num FROM `mr_smtp_task` WHERE taskid = ".$taskid." ".$where." and `status` = 2 ");
		$success=number_format($result['success']/$result['total']*100,1);
		$result['successpercent']=$success.'%';
		$result['hardFail']=$this->statistics->getSendNum("SELECT count(id) as num FROM `mr_smtp_task` WHERE taskid = ".$taskid." ".$where." and `status` = 3 ");
		$hardFail=number_format($result['hardFail']/$result['total']*100,1);
		$result['hardFailpercent']=$hardFail.'%';
		$result['softFail']=$this->statistics->getSendNum("SELECT count(id) as num FROM `mr_smtp_task` WHERE taskid = ".$taskid." ".$where." and `status` = 5 ");
		$softFail=number_format($result['softFail']/$result['total']*100,1);
		$result['softFailpercent']=$softFail.'%';
		$succ="['成功到达数量比例',".$success."]";
		$hard="['硬退回数量比例',".$hardFail."]";
		$soft="['软退回数量比例',".$softFail."]";
		
		$result['data']="[".$succ.",".$hard.",".$soft."]";
		return $result;
	}
	
	public function sendMailRuntime($taskid,$where){
		$sql="SELECT * FROM `mr_task_log`  WHERE ".$where." tid = ".$taskid." order by runtime desc";
		$result = $this->statistics->selectAllTask($sql);
		return $result;
	}
	
	public function mailFeedback($taskid,$tlid){
		$where = $tlid == 'all' ? '' : ' AND tlid = '.$tlid;
		$result['total']           = $this->statistics->getSendNum("SELECT count(id) as num FROM `mr_smtp_task` WHERE taskid = ".$taskid." ".$where);
		$result['numberAt']        = $this->statistics->getSendNum("SELECT count(id) as num FROM `mr_smtp_task` WHERE taskid = ".$taskid." ".$where." and `status` = 2 ");
		$result['numberAtpercent'] = number_format($result['numberAt'] / $result['total']* 100, 1).'%';
		$result['openNum']         = $this->statistics->getSendNum("SELECT sum(hasread) as num FROM `mr_smtp_task` WHERE taskid = ".$taskid." ".$where);
		$result['openNumpercent']  = number_format($result['openNum'] / $result['total']* 100, 1).'%';
		$result['clickNum']        = $this->statistics->getSendNum("SELECT sum(readcount) as num FROM `mr_smtp_task` WHERE taskid = ".$taskid." ".$where);
		$result['clickNumpercent'] = number_format($result['clickNum'] / $result['total']* 100, 1).'%';
		$tnum                      = "['发送数量',".$result['total']."]";
		$arrive                    = "['到达数量',".$result['numberAt']."]";
		$open                      = "['打开数量',".$result['openNum']."]";
		$click                     = "['点击数量',".$result['clickNum']."]";
		$result['data']            = "[".$tnum.",".$arrive.",".$open.",".$click."]";
		return $result;
	}

	public function checkLoginAudit($role) {
		$uid = $this->getCurrentUserID();
		$where=' ';
		if($role == 'sadmin' || $role == 'admin') {
			$where=' 1=1 ';
		}elseif($role == 'stasker'){
			$userinfo = $this->account->getSTaskerUsers($uid);
			 if(!empty($userinfo)) {
                foreach ($userinfo as $val) {
                    $id[] = $val['id'];
                }
            }
            $str = implode(",", $id);
            $where = "  1=1 and uid in(" . $str . ")";
		}else{
			$where="  1=1 and uid = ".$uid." ";
		}
		return $where;
	}
	
	public function checkstatus($status) {
		switch($status){
			case 1:
				$status='准备投递';
				break;
			case 2:
				$status='投递成功';
				break;
			case 3:
				$status='投递失败（硬退）';
				break;
			case 4:
				$status='投递失败重试';
				break;
			case 5:
				$status='投递失败（软退）';
				break;
			case 6:
				$status='投递取消';
				break;
			case 7:
				$status='停止';
				break;
			default:
				$status='待投递';
				break;
			}
		return $status;
	}
	
	function wphp_urlencode($data) {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $k => $v) {
                if (is_scalar($v)) {
                    if (is_array($data)) {
                        $data[$k] = urlencode($v);
                    } else if (is_object($data)) {
                        $data->$k = urlencode($v);
                    }
                } else if (is_array($data)) {
                    $data[$k] = $this->wphp_urlencode($v); //递归调用该函数
                } else if (is_object($data)) {
                    $data->$k = $this->wphp_urlencode($v);
                }
            }
        }
        return $data;
	}

	public function setSearchtime($arr){
		switch($arr['timescope']){
			case 'today':
				$result['begintime']=mktime(0,0,0,date('m'),date('d'),date('Y'));
				$result['endtime']=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
				break;
			case 'month':
				$result['begintime']=mktime(0,0,0,date('m'),1,date('Y'));
				$result['endtime']=mktime(23,59,59,date('m'),date('d'),date('Y'));
				break;
			case 'year':
				$result['begintime']=mktime(0,0,0,1,1,date('Y'));
				$result['endtime']=mktime(23,59,59,date('m'),date('d'),date('Y'));
				break;
			case 'setval':
				$last_begin = date("Y-m-d 00:00:00",strtotime($arr['stattime'])); 
				$last_end = date("Y-m-d 23:59:59",strtotime($arr['lasttime']));  
				$result['begintime']=strtotime($last_begin);
				$result['endtime']=strtotime($last_end);
				break;
			default:
				$result['begintime']=mktime(0,0,0,date('m'),date('d')-date('w')+1,date('Y'));
				$result['endtime']=mktime(23,59,59,date('m'),date('d'),date('Y'));
				break;
		}
		return $result;
	}


		function reportimgAction(){
				$uid = $this->_request->get('uid');
				$where = "uid = {$uid}";
				// $where = " uid = 3";
				$taskdata=self::selectTaskInfo($where);
		
				if(!empty($taskdata)){
					$sql="SELECT * FROM `mr_task_log`  WHERE ".$where." and tid = ".$taskdata[0]['id']." order by runtime desc";
					$taskresult = $this->statistics->selectAllTask($sql);
					
					$task_log_id = $task_lid ? $task_lid : $taskresult[0]['id'];
					if(!empty($taskresult)){
						//发送情况
						$sendresult=self::sendMailSuccess($taskdata[0]['id'],$task_log_id);
						$sendresult['totalpercent']=(number_format($sendresult['total']/$taskresult[0]['total'],1)*100).'%';
						//反馈情况
						$backresult=self::mailFeedback($taskdata[0]['id'],$task_log_id);
						$backresult['totalpercent']=(number_format($backresult['total']/$taskresult[0]['total'],1)*100).'%';
						$fpage=array();
						$fpage['pageSize']=10; 
						$fpage['pageMax']=0; 
						$fpage['currentPage']=1;
						$w='';
						if($task_log_id == 'all'){
							$w= '';
						}else{
							$w=" and tlid = ".$task_log_id;
						}
						$sql='SELECT count(id) FROM `mr_smtp_task` WHERE taskid = '.$taskdata[0]['id'].' '.$w.' order by runtime desc';
						$fpage['total']=$this->statistics->getSendNum($sql);
						$fpage['pageMax']=ceil($fpage['total']/$fpage['pageSize']); 	
						
						if($_GET['page'] != ''){
							$fpage['currentPage']=intval($_GET['page']);
						}
						if($fpage['currentPage']<=$fpage['pageMax']){ 
							$sql2="SELECT * FROM `mr_smtp_task`  WHERE taskid = ".$taskdata[0]['id']." ".$w." order by runtime desc limit ".($fpage['currentPage']-1)*$fpage['pageSize'].",".$fpage['pageSize']."";
							$statusresult=$this->statistics->selectAllTask($sql2);
							foreach($statusresult as &$val){
								$val['status']=self::checkstatus($val['status']);
							}
							unset($val);
						}
						if($_GET['page'] != ''){
							exit(json_encode($statusresult));
						}	
					
					}
				}
				$this->Smarty->assign ("sendresult", $sendresult);
				$this->Smarty->assign ("backresult", $backresult);
				$this->Smarty->display('reportimg.php');
		}

		//根据任务名称匹配任务
		public function matchtaskAction(){
			$role = $this->getCurrentUserRole();
			$where=self::checkLoginAudit($role);
			$task_name=$_POST['task_name'];
			if (isset($task_name) && !empty($task_name)) {
				if (empty($where)) {
					$where = " task_name like '%" . $task_name . "%' ";
				} else {
					$where .= " and task_name like '%" . $task_name . "%' ";
				}
			}
			$sql="select id,task_name from `mr_task` where ".$where." and draft<>1 and (status = 3 or status = 4 or status = 5 or status = 7 or status= 8) order by createtime desc";
			$result=$this->statistics->selectAllTask($sql);
			exit(json_encode($result));
		}
}
?>