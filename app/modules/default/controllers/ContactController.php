<?php 
require ('CommonController.php');
require ('page.class.php');
require_once ('excel/reader.php');
require_once ('excel/PHPExcel.php');
require_once 'excel/PHPExcel/IOFactory.php';
require_once 'excel/PHPExcel/Reader/Excel5.php';
require_once 'excel/PHPExcel/Reader/Excel2007.php';
require_once ('excel/PHPExcel/Shared/String.php');
header("Content-Type:text/html;charset=utf-8");
class ContactController extends CommonController{
	public function init(){
		parent::init ();
	}
	//联系人组列表
	public function contactlistAction(){
		$id = $_SESSION['Zend_Auth']['storage']->id;
		$role = $_SESSION['Zend_Auth']['storage']->role;
		$num = $_GET['num']?$_GET['num']:10;
		if($_GET['search']){
			$this->Smarty->assign("search",$_GET['search']);
			$gname = mysql_escape_string($_GET['gname']);
			$remark = mysql_escape_string($_GET['remark']);
			$createtime = mysql_escape_string($_GET['createtime']);
			$createman = mysql_escape_string($_GET['createman']);
			$select = $this->dbAdapter->select();
			$select->from('mr_group',array('id','gname','remark','createtime','tablename'));
			if(isset($gname) && !empty($gname)){
				$where = " gname like '%".$gname."%' ";
				$this->Smarty->assign('gname',stripslashes($gname));
			}
			if(isset($remark) && !empty($remark)){
				if(empty($where)){
					$where = " remark like '%".$remark."%' ";
				}else{
					$where .= " and remark like '%".$remark."%' ";
				}
				$this->Smarty->assign('remark',stripslashes($remark));
			}
			if(isset($createtime) && !empty($createtime)){
				if(empty($where)){
					$where = " createtime >= '".$createtime." 00:00:00' and createtime <= '".$createtime." 23:59:59'";
				}else{
					$where .= " and createtime >= '".$createtime." 00:00:00' and createtime <= '".$createtime." 23:59:59'";
				}
				$this->Smarty->assign('createtime',stripslashes($createtime));
			}
			if(isset($createman) && !empty($createman)){
				$uid = $this->dbAdapter->fetchAll("select id from mr_accounts where username like '%".$createman."%'");
				if(!empty($uid)){
					foreach($uid as $key=>$val){
						$uids .= $val['id'].",";
					}
					$uids = rtrim($uids,",");
					if(empty($where)){
						$where = " uid in (".$uids.")";
					}else{
						$where .= " and uid in (".$uids.")";
					}
				}
				$this->Smarty->assign('createman',stripslashes($createman));
			}

			$condition = "where 1=1";
			if(isset($where) && !empty($where)){
				$res = $this->dbAdapter->fetchAll("select * from mr_group ".$condition." and ".$where);
				$total = count($res);
				$page = new Page($total,$num,"");
				$result = $this->dbAdapter->fetchAll("select * from mr_group ".$condition." and ".$where." {$page->limit} ");
			}else{
				$res = $this->dbAdapter->fetchAll("select * from mr_group ".$condition);
				$total = count($res);
				$page = new Page($total,$num,"");
				$result = $this->dbAdapter->fetchAll("select * from mr_group ".$condition." {$page->limit}");
			}
			if($result){
				foreach($result as $r_key=>$r_val){
					if(strlen($r_val['remark'])>20){
						$result[$r_key]['rk'] = mb_substr($r_val['remark'],0,20);
					}
					if(strlen($r_val['gname'])>10){
						$result[$r_key]['gm'] = mb_substr($r_val['gname'],0,10);
					}
					if($r_val['uid'] == $id){
						$result[$r_key]['mine'] = 1;
					}else{
						$result[$r_key]['mine'] = 0;
					}
					$select1 = $this->dbAdapter->select();
					$select1->from($r_val['tablename'],'id');

					$result[$r_key]['count'] = count($this->dbAdapter->fetchAll("select * from ".$r_val['tablename']));
					$result[$r_key]['createperson'] = $this->dbAdapter->fetchOne("select username from mr_accounts where id=".$r_val['uid']);
				}
			}
			$userid = $this->getCurrentUserID();
			$uname = $this->getcurrentuser();
			$role = $this->getCurrentUserRole();
			$description='该用户进行查询联系人分组的操作';
			$description_en='The user performs the operation of querying the grouping of contacts';
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询操作', $description, 'Query operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		
			$this->Smarty->assign ("page", $page->fpage());
		}else{
			$result = $this->dbAdapter->fetchAll("select * from mr_group ");
			$total = count($result);
			$page = new Page($total,$num,"");
			$select = $this->dbAdapter->select();
			$select->from('mr_group',array('id','gname','tablename','createtime'));

			$result = $this->dbAdapter->fetchAll("select * from mr_group order by id desc {$page->limit}");
			if($result){
				foreach($result as $r_key=>$r_val){
					if(strlen($r_val['remark'])>20){
						$result[$r_key]['rk'] = mb_substr($r_val['remark'],0,20);
					}
					if(strlen($r_val['gname'])>10){
						$result[$r_key]['gm'] = mb_substr($r_val['gname'],0,10);
					}
					if($r_val['uid'] == $id){
						$result[$r_key]['mine'] = 1;
					}else{
						$result[$r_key]['mine'] = 0;
					}
					$select1 = $this->dbAdapter->select();
					$select1->from($r_val['tablename'],'id');
					$result[$r_key]['count'] = count($this->dbAdapter->fetchAll("select * from ".$r_val['tablename']));
					$result[$r_key]['createperson'] = $this->dbAdapter->fetchOne("select username from mr_accounts where id=".$r_val['uid']);
				}
			}
			$this->Smarty->assign ("page", $page->fpage());
		}
		$this->Smarty->assign('num',$num);
		$this->Smarty->assign ("li_menu", "contactlist");
		if($result){
			foreach($result as $key=>$val){
				if(strlen($val['gname'])>10){
					$result[$key]['gm'] = mb_substr($val['gname'],0,10);
				}
			}
		}
		$this->Smarty->assign('data',$result);
		$this->Smarty->display ( 'contactlist.php' );	
	}
	
	//返回权限控制组成的用户id
	public function checkLoginAuditId($role,$id){
		if($role == "sadmin"){
				$admins_sql = 'SELECT id FROM mr_accounts';
				$admins_resoult = $this->dbAdapter->fetchAll($admins_sql);
				$admins_array=array();	
				if($admins_resoult){
					foreach($admins_resoult as $v1){
						$admins_array[]=$v1['id'];
					}
				}
				$string =join(',',$admins_array);
			}else if($role == "admin"){
				$stasker_id = $this->dbAdapter->fetchAll("select id from mr_accounts where role='stasker' and parentid=".$id);
				$stasker_ids = "";
				if(!empty($stasker_id)){
					foreach($stasker_id as $key=>$val){
						$stasker_ids .= $val['id'].",";
					}
				}
				$stasker_ids = rtrim($stasker_ids,",");
				if(!empty($stasker_ids)){
					$tasker_id = $this->dbAdapter->fetchAll("select id from mr_accounts where parentid in(".$stasker_ids.")");
					$tasker_ids = "";
					if(!empty($tasker_id)){
						foreach($tasker_id as $key=>$val){
							$tasker_ids .= $val['id'].",";
						}
					}
				}
				if(!empty($tasker_ids)){
					$string = $stasker_ids.",".$tasker_ids.$id;
				}else{
					$string = $id;
				}
			}else if($role == "stasker"){
				$arr_id = $this->dbAdapter->fetchAll("select id from mr_accounts where parentid=".$id);
				$string = "";
				if(!empty($arr_id)){
					foreach($arr_id as $key=>$val){
						$string .= $val['id'].",";
					}
				}
				$string = $string.$id; 
			}else{
				$string = $id;
			}
			return $string;
	}
	
	//返回权限控制条件
	public function checkLoginAudit($role,$id){
		if($role == "sadmin"){
				$condition = " where 1=1 ";
			}else if($role == "admin"){
				$stasker_id = $this->dbAdapter->fetchAll("select id from mr_accounts where role='stasker' and parentid=".$id);
				$stasker_ids = "";
				if(!empty($stasker_id)){
					foreach($stasker_id as $key=>$val){
						$stasker_ids .= $val['id'].",";
					}
				}
				$stasker_ids = rtrim($stasker_ids,",");
				if(!empty($stasker_ids)){
					$tasker_id = $this->dbAdapter->fetchAll("select id from mr_accounts where parentid in(".$stasker_ids.")");
					$tasker_ids = "";
					if(!empty($tasker_id)){
						foreach($tasker_id as $key=>$val){
							$tasker_ids .= $val['id'].",";
						}
					}
				}
				if(!empty($tasker_ids)){
					$string = $stasker_ids.",".$tasker_ids.$id;
				}else{
					$string = $id;
				}
				$condition = " where uid in(".$string.") ";
			}else if($role == "stasker"){
				$arr_id = $this->dbAdapter->fetchAll("select id from mr_accounts where parentid=".$id);
				$string = "";
				if(!empty($arr_id)){
					foreach($arr_id as $key=>$val){
						$string .= $val['id'].",";
					}
				}
				$string = $string.$id; 
				if(!empty($string)){
					$condition = " where uid in(".$string.") ";
				}
			}else{
				$condition = " where uid=".$id;
			}
			return $condition;
	}
	
	//增加联系人组
	public function addgroupAction(){
			$gname = str_replace("","",$_POST['gname']);
			$gname = str_replace(" ","",$gname);
			$tablename = 'mr_group_'.self::uuid();
			$uid = $_SESSION['Zend_Auth']['storage']->id ? $_SESSION['Zend_Auth']['storage']->id : 0;
			$arr = array('uid'=>$uid,
						 'createtime'=>date('Y-m-d H:i:s'),
						 'gname'=>$gname,
						 'remark'=>$_POST['remark'],
						 'tablename'=>$tablename);
			if(empty($uid)){
				exit;
			}
			$insert = $this->dbAdapter->insert('mr_group',$arr);
			if($insert){
				$description='该用户进行添加联系人组的操作,联系人组名为:'.$gname;
				$description_en='The user add contact group operation of the user, the contact group name is: '.$gname;
				self::taskOperationLog($description,$description_en);
			}
			
			$extension = $this->dbAdapter->fetchAll("select showname,name,type from mr_group_extension where hidden=0");
			if($extension){
				foreach($extension as $e_key=>$e_val){
					if($e_val['type']==1){
						$type = "char(128)";
					}elseif($e_val['type']==2){
						$type = "int(10)";
					}elseif($e_val['type']==3){
						$type = "datetime";
					}else{
						$type = "float";
					}
					$str .= "`".$e_val['name']."` ".$type." NOT NULL ,";
					$arr[$e_val['name']] = $_POST[$e_val['name']];
				}
			}
			if($_POST['newgname']!=""){
				$tablename = 'mr_group_'.self::uuid();
			}
			$str = rtrim($str,",");
			if($str != ""){
				$sql = "CREATE TABLE ".$tablename."(
										`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
										`uid` INT UNSIGNED NOT NULL DEFAULT 0,
										`username` varchar(256) NOT NULL,
										`mailbox` varchar(256) NOT NULL,
										`sex` tinyint(1) NOT NULL DEFAULT 1,
										`birth` int(10) NOT NULL DEFAULT 0,	
										`tel` varchar(20),".$str.",
										UNIQUE KEY `name`(`mailbox`)
									) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			}else{
				$sql = "CREATE TABLE ".$tablename."(
										`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
										`uid` INT UNSIGNED NOT NULL DEFAULT 0,
										`username` varchar(256) NOT NULL,
										`mailbox` varchar(256) NOT NULL,
										`sex` tinyint(1) NOT NULL DEFAULT 1,
										`birth` int(10) NOT NULL DEFAULT 0,
										`tel` varchar(20),
										UNIQUE KEY `name`(`mailbox`)
									) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			}
			$this->dbAdapter->query($sql);
			$this->_redirect('contact/contactlist');
	}

	//修改联系人组信息
	public function editAction(){
		$gname = str_replace("","",$_POST['gname']);
		$gname = str_replace(" ","",$gname);
		$remark = $_POST['remark'];
		$id = $_POST['id'];
		$arr = array('gname'=>$gname,'remark'=>$remark);
		$count = $this->dbAdapter->update('mr_group',$arr,'id='.$id);
		$description='该用户进行修改联系人组的操作,联系人组名为:'.$gname;
		$description_en='The user update the contact group operation, the contact group name:'.$gname;
		self::taskOperationLog($description,$description_en);
		$this->_redirect('contact/contactlist');
	}
	/*删除联系人组
	 * 1.删除的原则1：publisher 可以删除联系人组
	 * 2.删除的原则2：普通任务发布员 可以删除任何人创建的，但"仅"存在自己创建的联系人分组中的人
	 * 3.删除联系人组的时候要同时删除联系人组表中tablename字段对应的表中的数据
	 * 4.在mr_subscriber表中要修改groups字段，该字段是存放联系人组id的
	 */
	public function deleteAction(){
		$role = $_SESSION['Zend_Auth']['storage']->role;
		$id = $_POST['id'];//联系人分组的id 
        $usernames = $_SESSION['Zend_Auth']['storage']->username;
		$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$id);
		$gname = $this->dbAdapter->fetchOne("select gname from mr_group where id=".$id);
		$uid = $this->dbAdapter->fetchOne("select uid from mr_group where id =".$id);
		$tid = $this->dbAdapter->fetchOne("select id from mr_accounts where username ='".$usernames."'"); 
		if( $uid == $tid || $role == 'stasker'){//是本任务发布员删除自己创建的组
			$mailbox = $this->dbAdapter->fetchAll("select mailbox from ".$tablename);
			if($mailbox){
				foreach($mailbox as $key=>$val){
					$groups = $this->dbAdapter->fetchOne("select groups from mr_subscriber where mailbox ='".$val['mailbox']."'");
					$gcount = substr_count(trim($groups,","), ",");//判断组成员是否属于多个组
					if($gcount < 1){//组成员仅属于一个组	
						$this->dbAdapter->delete('mr_subscriber',"mailbox='".$val['mailbox']."'");
					}else{			//组成员属于多个组
						$groups_new = str_replace($id,"",$groups);
						$groups_new = trim( str_replace(",,",",",$groups_new),",");
						$count = $this->dbAdapter->update('mr_subscriber',array('groups'=>$groups_new),"mailbox='".$val['mailbox']."'");
					}
				}
			}
			$sql = "drop table ".$tablename;
			$this->dbAdapter->query($sql);
			$this->dbAdapter->delete('mr_group',"tablename='".$tablename."'");
			$usernames = $_SESSION['Zend_Auth']['storage']->username;
			$description='该用户进行删除联系人组的操作,联系人组名为:'.$gname;
			$description_en='The user delete contact group operation ,the group is: '.$gname;
			self::taskOperationLog($description,$description_en);
		}
	}
	
	//检查联系人邮箱是否合法
	public function checkmailAction(){
		$id = $_POST['id'];
		$mail = "/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/";
		$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$id);
		$arr = $this->dbAdapter->fetchAll("select id,mailbox from ".$tablename);
		$brr['total'] = count($arr);
		foreach($arr as $a_key=>$a_val){
			if(preg_match($mail,$a_val['mailbox'])){
				$brr['legal']++;
			}else{
				$brr['illegal']++;
				$brr['str'] = $a_val['id'].",".$brr['str'];
			}
		}
		echo json_encode($brr);
	}
	
	//显示不合法联系人信息
	public function illegallistAction(){
		if($_GET['str']){
			$str = $_GET['str'];
			$str = substr($str,0,-1);
			$gid = $_GET['gid'];
			$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$gid);
			$result = $this->dbAdapter->fetchAll("select * from ".$tablename." where id in(".$str.")");
			$this->Smarty->assign ("li_menu", "personlist");
			$this->Smarty->display('personlist.php');
		}
	}
	
	//用于判断是否可以合并组
	public function combinegroupAction(){
		$uid = $_SESSION['Zend_Auth']['storage']->id;
		$targetgroup = $_POST['targetgroup'];
		$gids = $_POST['gids'];
		$tid = $this->dbAdapter->fetchOne("select id from mr_group where gname='".$targetgroup."'");
		$ids = $gids . "" . $tid;
		$id_arr = explode(',',$ids);
		foreach($id_arr as $v){
			$uid_c = $this->dbAdapter->fetchOne("select uid from mr_group where id=".$v);
			if ( $uid != $uid_c ){
				echo 1;die;
			}
		}
	}
	
	//用于合并联系人列表
	public function combineAction(){
		if($_POST['groupname']){
			$gname = str_replace("","",$_POST['groupname']);
			$gname = str_replace(" ","",$gname);
			$tablename = 'mr_group_'.self::uuid();
			$uid = $_SESSION['Zend_Auth']['storage']->id ? $_SESSION['Zend_Auth']['storage']->id : 0;
			$arr = array('uid'=>$uid,
						 'createtime'=>date('Y-m-d H:i:s'),
						 'gname'=>$gname,
						 'remark'=>$_POST['remark'] ? $_POST['remark']:"",
						 'tablename'=>$tablename);
			if(empty($uid)){
				exit;
			}
			$insert = $this->dbAdapter->insert('mr_group',$arr);
			$gid = $this->dbAdapter->lastInsertId();
			
			$extension = $this->dbAdapter->fetchAll("select showname,name,type from mr_group_extension where hidden=0");
			if($extension){
				foreach($extension as $e_key=>$e_val){
					if($e_val['type']==1){
						$type = "char(128)";
					}elseif($e_val['type']==2){
						$type = "int(10)";
					}elseif($e_val['type']==3){
						$type = "datetime";
					}else{
						$type = "float";
					}
					$str .= "`".$e_val['name']."` ".$type." NOT NULL ,";
					$arr[$e_val['name']] = $_POST[$e_val['name']];
				}
			}
			if($_POST['newgname']!=""){
				$tablename = 'mr_group_'.self::uuid();
			}

			$str = rtrim($str,",");
			

			if($str != ""){
				$sql = "CREATE TABLE ".$tablename."(
										`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
										`uid` INT UNSIGNED NOT NULL DEFAULT 0,
										`username` varchar(256) NOT NULL,
										`mailbox` varchar(256) NOT NULL,
										`sex` tinyint(1) NOT NULL DEFAULT 1,
										`birth` int(10) NOT NULL DEFAULT 0,	
										`tel` varchar(20),".$str.",
										UNIQUE KEY `name`(`mailbox`)
									) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			}else{
				$sql = "CREATE TABLE ".$tablename."(
										`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
										`uid` INT UNSIGNED NOT NULL DEFAULT 0,
										`username` varchar(256) NOT NULL,
										`mailbox` varchar(256) NOT NULL,
										`sex` tinyint(1) NOT NULL DEFAULT 1,
										`birth` int(10) NOT NULL DEFAULT 0,
										`tel` varchar(20),
										UNIQUE KEY `name`(`mailbox`)
									) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			}
			$this->dbAdapter->query($sql);
			

			$groupids = $_POST['gname'];
			$gnames = "";

			if($groupids){
				foreach($groupids as $val){
					$name = $this->dbAdapter->fetchOne("select gname from mr_group where id=".$val);
					$gnames .= $name.",";
					$tablename1 = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$val);
					$arr = $this->dbAdapter->fetchAll("select * from ".$tablename1);
					if($arr){
						foreach($arr as $key=>$val){
							unset($arr[$key]['id']);
							$sql = "INSERT INTO {$tablename}(uid,username,mailbox,sex,birth,tel) SELECT uid,username,mailbox,sex,birth,tel FROM  {$tablename1} WHERE mailbox NOT IN (SELECT mailbox FROM {$tablename}) ";
							$this->dbAdapter->query($sql);
						}
					}
				}

				//将当前组的联系人合并到新组
				$targetgroup = $_POST['targetgroup'];
				$tablename2 = $this->dbAdapter->fetchOne("select tablename from mr_group where gname='".$targetgroup."'");
				$brr = $this->dbAdapter->fetchAll("select * from ".$tablename2);
				if(!empty($brr)){
					foreach($brr as $bk=>$bv){
						$num = $this->dbAdapter->fetchOne("select count(id) from ".$tablename." where mailbox='".$bv['mailbox']."'");
						if($num){
						
						}else{
							unset($brr[$bk]['id']);
							$this->dbAdapter->insert($tablename,$brr[$bk]);
						}
					}
				}
				$this->dbAdapter->query("delete from ".$tablename." where id not in(select * from(select max(id) from ".$tablename." group by mailbox) as tmp)");
				$result = $this->dbAdapter->fetchAll("select * from ".$tablename);
				if($result){
					foreach($result as $key=>$val){
						$groups = $this->dbAdapter->fetchOne("select groups from mr_subscriber where mailbox='".$val['mailbox']."'");
						$groups .= ",".$gid;
						$this->dbAdapter->update('mr_subscriber',array('groups'=>$groups),"mailbox='".$val['mailbox']."'");
					}
				}
			}
	        $usernames = $_SESSION['Zend_Auth']['storage']->username;
			$gnames = trim($gnames ,",");
			$description='该用户进行联系人组的合并操作,生成新组：'.$gname.'，参与合并组: '.$targetgroup.','.$gnames;
			$description_en='The user of the merge operation contact groups, generate a new group: '.$gname.', participation in the merger group: '.$targetgroup.','.$gnames;
			self::taskOperationLog($description,$description_en);
		}else{
			$groupids = $_POST['gname'];
			$targetgroup = $_POST['targetgroup'];
			$gnames = "";
			if($groupids){
				foreach($groupids as $val){
					$gname = $this->dbAdapter->fetchOne("select gname from mr_group where id=".$val);
					$gnames .= $gname.",";
					$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$val);
					$arr = $this->dbAdapter->fetchAll("select * from ".$tablename);
					$targettablename = $this->dbAdapter->fetchRow("select id,tablename from mr_group where gname='".$targetgroup."'");
					if($arr){
						foreach($arr as $key=>$val){
							$count = $this->dbAdapter->fetchOne("select count(*) from ".$targettablename['tablename']."  where mailbox='".$val['mailbox']."'");
							if($count>0){
								$this->dbAdapter->query("delete from ".$targettablename['tablename']." where mailbox='".$val['mailbox']."'");
							}
							unset($arr[$key]['id']);
							$this->dbAdapter->insert($targettablename['tablename'],$arr[$key]);
						}
					}
				}
				$this->dbAdapter->query("delete from ".$targettablename['tablename']." where id not in(select * from(select max(id) from ".$targettablename['tablename']." group by mailbox) as tmp)");
				$result = $this->dbAdapter->fetchAll("select * from ".$targettablename['tablename']);
				if($result){
					foreach($result as $key=>$val){
						$groups = $this->dbAdapter->fetchOne("select groups from mr_subscriber where mailbox='".$val['mailbox']."'");
						$ext = explode(",",$groups);
						if($ext){
							foreach($ext as $k=>$v){
								if($targettablename['id'] == $v){
									unset($ext[$k]);
								}
							}
						}
						$gr = implode(",",$ext);
						$groupsid = $gr.",".$targettablename['id'];
						$this->dbAdapter->update('mr_subscriber',array('groups'=>$groupsid),"mailbox='".$val['mailbox']."'");
					}
				}
			}
			$gnames = trim($gnames ,",");
	        $usernames = $_SESSION['Zend_Auth']['storage']->username;
			$description='该用户进行联系人组的合并操作,联系人组为：'.$targetgroup.'，参与合并组: '.$gnames;
			$description_en='The user of the merge operation contact groups, the contact group is: '.$targetgroup.',Combining group:'.$gnames;
			self::taskOperationLog($description,$description_en);
		}
		$this->_redirect('contact/contactlist');
	}

	//联系人列表
	public function personlistAction(){
		if($_GET['msg']){
			$this->Smarty->assign('msg',$_GET['msg']);
		}
		$num = $_GET['num']?$_GET['num']:10;
		$uid = $_SESSION['Zend_Auth']['storage']->id;
		$role = $_SESSION['Zend_Auth']['storage']->role;
		$sel_status = $_SESSION['sel_stat'];

		$this->Smarty->assign('sel_status',$sel_status); 
		$this->Smarty->assign('num',$num); 
		if($_GET['id']){
			$id = $_GET['id'];
			$group_info = $this->dbAdapter->fetchAll("select tablename,gname from mr_group where id=".$id);
			$tablename = $group_info[0]['tablename'];
			$groupname = $group_info[0]['gname'];

			$user_id = $this->dbAdapter->fetchOne("select uid from mr_group where id=".$id);
			$userid = self::checkLoginAuditId($role,$uid);

			if(!empty($userid)){
				$arr_id = explode(",",$userid);
			}
			$res = $this->dbAdapter->fetchAll("select * from ".$tablename);
			$total = count($res);
			$page = new Page($total,$num,"");
			$result = $this->dbAdapter->fetchAll("select username,mailbox,sex,birth,tel,uid from ".$tablename." order by id desc {$page->limit}");
			if($result){
				foreach($result as $key=>$val){
					$result[$key]['id'] = $this->dbAdapter->fetchOne("select id from mr_subscriber where mailbox='".$val['mailbox']."'" );
					$ids .= $result[$key]['id'].",";
					if(count($arr_id)>0){
							if($role == 'sadmin' || $role =='admin'){
								$result[$key]['tag'] = 1;
							}else{
								if(in_array($val['uid'],$arr_id)){
									$result[$key]['tag'] = 1;
								}else{
									$result[$key]['tag'] = 2;
								}
							}
						if($val['uid'] == $uid){
							$result[$key]['mine'] = 1;
						}else{
							$result[$key]['mine'] = 0;
						}
					}else{
						if($role == 'sadmin' || $role =='admin'){
								$result[$key]['tag'] = 1;
							}else{
								if(in_array($val['uid'],$arr_id)){
									$result[$key]['tag'] = 1;
								}else{
									$result[$key]['tag'] = 2;
								}
							}
					}
				}
			}

			$this->Smarty->assign('groupname',$groupname);
			$this->Smarty->assign('ids',$ids);
			$this->Smarty->assign('id',$id);
			$this->Smarty->assign('data',$result);
			$this->Smarty->assign ("page", $page->fpage());
		}else if($_GET['search'] == "ser"){
			$oprator = array(0=>"是",1=>"否",2=>"包含",3=>"不包含",4=>"开始字符",5=>"结束字符",100=>"等于",101=>"不等于",102=>"大于",
					 103=>"不大于",104=>"小于",105=>"不小于",200=>"之前",201=>"之后",202=>"介于",203=>"不介于",300=>"为空",301=>"不为空");

			$this->Smarty->assign("search",$_GET['search']);
			$filtername = $_POST['filtername'];
			$description = $_POST['description'];
			$numstr = explode(",",$_GET['numstr2']);
			array_pop($numstr);
			$this->Smarty->assign('numstr',$numstr);
			$jostr = explode(",",$_GET['jostr2']);
			array_pop($jostr);
			$this->Smarty->assign('jostr',$jostr);
			$sname = explode(",",$_GET['sname2']);
			array_pop($sname);
			$this->Smarty->assign('sname',$sname);
			$ti = explode(",",$_GET['ti2']);
			array_pop($ti);
			$this->Smarty->assign('ti',$ti);
			$opra = explode(",",$_GET['opra2']);
			array_pop($opra);
			$this->Smarty->assign('opra',$opra);
			$type = explode(",",$_GET['type2']);
			array_pop($type);
			$ext = $this->dbAdapter->fetchAll("select * from mr_group_extension");
			$this->Smarty->assign('ext',$ext);
			$this->Smarty->assign('type',$type);
			$this->Smarty->assign('number2',$_GET['number2']);
			foreach($numstr as $key=>$val){
				$value[$key]["numstr"] = $numstr[$key];
				$value[$key]["jostr"] = $jostr[$key];
				$value[$key]["sname"] = $sname[$key];
				$value[$key]["opra"] = $opra[$key];
				$value[$key]["type"] = $type[$key];
				$value[$key]["ti"] = $ti[$key];
				$ext = explode("至",$ti[$key]);
				$value[$key]["time1"] = $ext[0];
				$value[$key]["time2"] = $ext[1];
			}
			$this->Smarty->assign('value',$value);

			$arr['name'] = $filtername;
			$arr['description'] = $description;
			$string = implode($sname);
			foreach($ti as &$tval){
				if($tval == "男"){
					$tval = "1";
				}else if($tval == "女"){
					$tval = "2";
				}
			}
			$ti=str_replace(array('*', '(', "'", '\\', '|', '?', '+', '[', '"'), array('','','','','','','','',''), $ti);
			if($string != ""){
				$sql = $this->getsql($oprator,$jostr,$sname,$ti,$opra);
			}
			$num = $this->_request->get('num2');
			if ($num == "") {
				$num = 10;
			}
			$this->Smarty->assign('num',$num);
			if($_GET['groupid']){
				$str = " 1=1 ";
				$this->Smarty->assign('id',$_GET['groupid']);
				if($sql){
					$str .= " and ".$sql;
				}
				$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$_GET['groupid']);
				$res = $this->dbAdapter->fetchAll("select * from ".$tablename." where ".$str);
				if(!empty($res)){
					foreach($res as $rk=>$rv){
						$ids .= $rv['id'].",";
					}
				}
				$this->Smarty->assign('ids',$ids);
				$total = count($res);
				$page = new Page($total,$num,"");
				$result = $this->dbAdapter->fetchAll("select mailbox,username,tel from ".$tablename." where ".$str." order by id desc {$page->limit}");
				$userid = self::checkLoginAuditId($role,$uid);
				if(!empty($userid)){
					$arr_id = explode(",",$userid);
				}
				if(!empty($result)){
					foreach($result as $key=>$val){
						if(count($arr_id)>0){
							if(in_array($val['uid'],$arr_id)){
								$result[$key]['tag'] = 1;
							}else{
								$result[$key]['tag'] = 0;
							}
							if($val['uid'] == $uid){
								$result[$key]['mine'] = 1;
							}else{
								$result[$key]['mine'] = 0;
							}
						}else{
							$result[$key]['tag'] = 1;
						}
					}
				}
				foreach($result as $a_key=>$a_val){
					$result[$a_key]['id'] = $this->dbAdapter->fetchOne("select id from mr_subscriber where mailbox='".$a_val["mailbox"]."'");
				}
				$this->Smarty->assign('data',$result);
				$this->Smarty->assign ("page", $page->fpage());
			}else{
				$str = " 1=1 ";
				if($sql){
					$str .= " and ".$sql;
				}
				$res = $this->dbAdapter->fetchAll("select * from mr_subscriber where ".$str);
				if(!empty($res)){
					foreach($res as $rk=>$rv){
						$ids .= $rv['id'].",";
					}
				}
				$this->Smarty->assign('ids',$ids);
				$total = count($res);
				$page = new Page($total,$num,"");
				$result = $this->dbAdapter->fetchAll("select * from mr_subscriber where ".$str." order by id desc {$page->limit}");
				$userid = self::checkLoginAuditId($role,$uid);
				if(!empty($userid)){
					$arr_id = explode(",",$userid);
				}
				if(!empty($result)){
					foreach($result as $key=>$val){
						if(count($arr_id)>0){
							if(in_array($val['uid'],$arr_id)){
								$result[$key]['tag'] = 1;
							}else{
								$result[$key]['tag'] = 0;
							}
							if($val['uid'] == $uid){
								$result[$key]['mine'] = 1;
							}else{
								$result[$key]['mine'] = 0;
							}
						}else{
							$result[$key]['tag'] = 1;
						}
					}
				}
				$userid = $this->getCurrentUserID();
				$uname = $this->getcurrentuser();
				$role = $this->getCurrentUserRole();
				$description='该用户进行查询联系人的操作';
				$description_en='The user performs the operation of querying contacts';
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询操作', $description, 'Query operation', $description_en, $_SERVER["REMOTE_ADDR"]);
			
				$this->Smarty->assign('data',$result);
				$this->Smarty->assign ("page", $page->fpage());
			}
		}else if($_GET['fid']){
			$this->Smarty->assign('fid',$_GET['fid']);
			$userid = self::checkLoginAuditId($role,$uid);
			if(!empty($userid)){
				$arr_id = explode(",",$userid);
			}
			$res = $this->dbAdapter->fetchAll("select * from mr_subscriber ");
			if(!empty($res)){
				foreach($res as $rk=>$rv){
					$ids .= $rv['id'].",";
				}
			}
			$this->Smarty->assign('ids',$ids);
			$total = count($res);
			$page = new Page($total,$num,"");
			$arr = $this->dbAdapter->fetchAll("select * from mr_condition where fid=".$_GET['fid']);
			$brr = $this->dbAdapter->fetchAll("select * from mr_group_extension");

			if(!empty($arr)){
				foreach($arr as $ak=>$av){
					foreach($brr as $bk=>$bv){
						if($bv['id'] == $av['field']){
							$value[$ak]['type'] = $bv['type'];
							$value[$ak]['sname'] = $bv['id'];
						}
					}
					$value[$ak]['opra'] = $av['operator'];
					$value[$ak]['ti'] = $av['value'];
					$value[$ak]['jostr'] = $av['join'];
					$value[$ak]['numstr'] = $ak+1;
					$exp = explode("至",$av['value']);
					$value[$ak]["time1"] = $exp[0];
					$value[$ak]["time2"] = $exp[1];
				}
			}
			$this->Smarty->assign('value',$value);
			$this->Smarty->assign('ext',$brr);
			$condition = $this->dbAdapter->fetchRow("select * from mr_filter where id=".$_GET['fid']);
			if($condition['condition']){
				$where = " where ".$condition['condition'];
			}else{
				$where = " where 1=1";
			}
			$result = $this->dbAdapter->fetchAll("select * from mr_subscriber ".$where." order by id desc {$page->limit}");
			if(!empty($result)){
				foreach($result as $key=>$val){
					if(count($arr_id)>0){
						if(in_array($val['uid'],$arr_id)){
							$result[$key]['tag'] = 1;
						}else{
							$result[$key]['tag'] = 0;
						}
						if($val['uid'] == $uid){
							$result[$key]['mine'] = 1;
						}else{
							$result[$key]['mine'] = 0;
						}
					}else{
						$result[$key]['tag'] = 1;
					}
				}
			}
			$this->Smarty->assign('data',$result);
			$this->Smarty->assign ("page", $page->fpage());
			
		}else{
			$userid = self::checkLoginAuditId($role,$uid);
			if(!empty($userid)){
				$arr_id = explode(",",$userid);
			}
			$res = $this->dbAdapter->fetchAll("select * from mr_subscriber ");
			if(!empty($res)){
				foreach($res as $rk=>$rv){
					$ids .= $rv['id'].",";
				}
			}
			$this->Smarty->assign('ids',$ids);
			$total = count($res);
			$page = new Page($total,$num,"");
			$result = $this->dbAdapter->fetchAll("select * from mr_subscriber order by id desc {$page->limit}");
			if(!empty($result)){
				foreach($result as $key=>$val){
					if(count($arr_id)>0){
						if(in_array($val['uid'],$arr_id)){
							$result[$key]['tag'] = 1;
						}else{
							$result[$key]['tag'] = 0;
						}
						if($val['uid'] == $uid){
							$result[$key]['mine'] = 1;
						}else{
							$result[$key]['mine'] = 0;
						}
					}else{
						$result[$key]['tag'] = 1;
					}
				}
			}
			$this->Smarty->assign('data',$result);
			$this->Smarty->assign ("page", $page->fpage());
		}
		$extension = $this->dbAdapter->fetchAll("select showname,name,type from mr_group_extension where hidden=0 ");
		$this->Smarty->assign('extension',$extension);
		$arr = $this->dbAdapter->fetchAll("select * from mr_group_extension");
		$this->Smarty->assign('arr',$arr);

		$userid = $this->getCurrentUserID();
		$group = $this->dbAdapter->fetchAll("select id,gname from mr_group where uid =".$userid);
		if($group){
			foreach($group as $key=>$val){
				if(strlen($val['gname'])>10){
					$group[$key]['gm'] = mb_substr($val['gname'],0,10);
				}
			}
		}
		$this->Smarty->assign('group',$group);
		$this->Smarty->assign ("li_menu", "personlist");
		$this->Smarty->display('personlist.php');
	}

	//添加联系人
	public function addpersonAction(){
			$uid = $_SESSION['Zend_Auth']['storage']->id ? $_SESSION['Zend_Auth']['storage']->id : 0;
			$sex = $_POST['sex'] ? $_POST['sex'] : 1;
			$arr = array();
			$extension = $this->dbAdapter->fetchAll("select showname,name,type from mr_group_extension where hidden=0 ");

			if($extension){
				foreach($extension as $e_key=>$e_val){
					if($e_val['type']==1){
						$type = "char(128)";
					}elseif($e_val['type']==2){
						$type = "int(10)";
					}elseif($e_val['type']==3){
						$type = "datetime";
					}else{
						$type = "float";
					}
					$str .= "`".$e_val['name']."` ".$type." NOT NULL ,";
					$arr[$e_val['name']] = $_POST[$e_val['name']];
				}
			}
			$str = rtrim($str,",");
			
			if($_POST['newgname']!=""){
				$tablename1 = 'mr_group_'.self::uuid();
				$newgname = str_replace("","",$_POST['newgname']);
				$newgname = str_replace(" ","",$newgname);
				$brr = array('gname'=>$newgname,
						 	'tablename'=>$tablename1,
						 	'createtime'=>date('Y-m-d H:i:s'),
						 	'uid'=>$uid
							);
				if(empty($uid)){
					exit;
				}
				$this->dbAdapter->insert(mr_group,$brr);
				$lastId = $this->dbAdapter->lastInsertId();
				if($str != ""){
					$sql = "CREATE TABLE ".$tablename1."(
											`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
											`uid` INT UNSIGNED NOT NULL DEFAULT 0,
											`username` varchar(256) NOT NULL,
											`mailbox` varchar(256) NOT NULL,
											`sex` tinyint(1) NOT NULL DEFAULT 1,
											`birth` int(10) NOT NULL DEFAULT 0,	
											`tel` varchar(20),".$str.",
											UNIQUE KEY `name`(`mailbox`)
										) ENGINE=MyISAM DEFAULT CHARSET=utf8";
				}else{
					$sql = "CREATE TABLE ".$tablename1."(
											`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
											`uid` INT UNSIGNED NOT NULL DEFAULT 0,
											`username` varchar(256) NOT NULL,
											`mailbox` varchar(256) NOT NULL,
											`sex` tinyint(1) NOT NULL DEFAULT 1,
											`birth` int(10) NOT NULL DEFAULT 0,
											`tel` varchar(20),
											UNIQUE KEY `name`(`mailbox`)
										) ENGINE=MyISAM DEFAULT CHARSET=utf8";
				}
				$this->dbAdapter->query($sql);

			}
			if(count($_POST['gname'])>1){
				$groups = implode(",",$_POST['gname']);
			}else{
				$groups = $_POST['gname'][0];
			}

			$arr['mailbox'] = $_POST['mailbox'];
			$arr['username'] = $_POST['username'];
			$arr['sex'] = $sex;
			$arr['birth'] = strtotime($_POST['birth']);
			$arr['tel'] = $_POST['tel'];
			$arr['groups'] = $groups;
			$arr['uid'] = $uid;
			$crr = $arr;
			unset($crr['groups']);
			if($tablename1){
				$count2 = $this->dbAdapter->insert($tablename1,$crr);
				if($groups){
					$arr['groups'] = $groups.",".$lastId;
				}else{
					$arr['groups'] = $lastId;
				}
			}
			$gnames = $this->dbAdapter->fetchAll("select gname from mr_group where id in(".$arr['groups'].")");
			$str2 ="";
			foreach( $gnames as $gname){
				$str2 .= $gname['gname'].","; 
			}
			$str2 = trim($str2,",");
	        $usernames = $_SESSION['Zend_Auth']['storage']->username;
			$description='该用户进行添加联系人的操作，联系人邮箱为：'.$arr['mailbox'].' ,添加进组：'.$str2;
			$description_en='The user add contact operation, the contact email is:'.$arr['mailbox'].', added to the group: '.$str2;
			self::taskOperationLog($description,$description_en);
			$count1 = $this->dbAdapter->insert("mr_subscriber",$arr);
			
			if($_POST['gname'] != ""){
				foreach($_POST['gname'] as $val){
					$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$val);
					$this->dbAdapter->insert($tablename,$crr);
				}
			}
			
			if($count1){
				$this->_redirect('contact/personlist');
			}else{
				echo "联系人添加失败";
			}
			
	}
	
	//编辑联系人中获取除自己所属组外的所有组
	public function getgroupsAction(){
		$id = $_SESSION['Zend_Auth']['storage']->id;
		$role = $_SESSION['Zend_Auth']['storage']->role;
		if($_POST['status']=='combine'){
			$gname = $_POST['gname'];
			$gnames = $this->dbAdapter->fetchAll("select id,gname from mr_group where gname <>'".$gname."'");
			echo json_encode($gnames);
		}else{
			$id = $_POST['id'];
			$result = $this->dbAdapter->fetchRow("select * from mr_subscriber where id=".$id);
			$group = $this->dbAdapter->fetchAll("select id,gname from mr_group where id not in(".$result['groups'].")");
			echo json_encode($group);
		}
	}
	
	//编辑联系人
	public function updatepersonAction(){
		$arr['mailbox'] = $_POST['mailbox'];
		$arr['username'] = $_POST['username'];
		$arr['sex'] = $_POST['sex'] ? $_POST['sex'] : 1;
		$arr['birth'] = strtotime($_POST['birth']);
		$arr['tel'] = $_POST['tel'];
		$uid = $_SESSION['Zend_Auth']['storage']->id ? $_SESSION['Zend_Auth']['storage']->id : 0;
		$arr['uid'] = $uid;
		$id = $_POST['id'];
		$extension = $this->dbAdapter->fetchAll("select showname,name,type from mr_group_extension where hidden=0");
			if($extension){
				foreach($extension as $e_key=>$e_val){
					if($e_val['type']==1){
						$type = "char(128)";
					}elseif($e_val['type']==2){
						$type = "int(10)";
					}elseif($e_val['type']==3){
						$type = "datetime";
					}else{
						$type = "float";
					}
					$str .= "`".$e_val['name']."` ".$type." NOT NULL ,";
					$arr[$e_val['name']] = $_POST[$e_val['name']];
				}
			}
		$str = rtrim($str,",");
		$result = $this->dbAdapter->fetchRow("select mailbox,groups from mr_subscriber where id=".$id);
		$groups = explode(",",$result['groups']);
		if($_POST['gname']){
			foreach($_POST['gname'] as $val){
				if(in_array($val,$groups)){
					$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$val);
					$this->dbAdapter->update($tablename,$arr,"mailbox='".$result['mailbox']."'");
				}else{
					$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$val);
					$this->dbAdapter->insert($tablename,$arr);
				}
			}
		}
		if($groups){
			foreach($groups as $val){
				if($_POST['gname']){
					if($val){
						if(!in_array($val,$_POST['gname'])){
							$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$val);
							$this->dbAdapter->delete($tablename,"mailbox='".$result['mailbox']."'");
						}
					}
				}
			}
		}
		if($_POST['newgname2']){
			$tablename = 'mr_group_'.self::uuid();
			$newgname = $_POST['newgname2'];
			$uid = $_SESSION['Zend_Auth']['storage']->id ? $_SESSION['Zend_Auth']['storage']->id : 0;
			$brr = array('gname'=>$newgname,
						 'tablename'=>$tablename,
						 'createtime'=>date('Y-m-d H:i:s'),
						 'uid'=>$uid
						);
			$this->dbAdapter->insert(mr_group,$brr);
			$insertId = $this->dbAdapter->lastInsertId();
			if($str != ""){
					$sql = "CREATE TABLE ".$tablename."(
											`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
											`uid` INT UNSIGNED NOT NULL DEFAULT 0,
											`username` varchar(256) NOT NULL,
											`mailbox` varchar(256) NOT NULL,
											`sex` tinyint(1) NOT NULL DEFAULT 1,
											`birth` int(10) NOT NULL DEFAULT 0,
											`tel` varchar(20),".$str.",
											UNIQUE KEY `name`(`mailbox`)
										) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			}else{
					$sql = "CREATE TABLE ".$tablename."(
											`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
											`uid` INT UNSIGNED NOT NULL DEFAULT 0,
											`username` varchar(256) NOT NULL,
											`mailbox` varchar(256) NOT NULL,
											`sex` tinyint(1) NOT NULL DEFAULT 1,
											`birth` int(10) NOT NULL DEFAULT 0,
											`tel` varchar(20),
											UNIQUE KEY `name`(`mailbox`)
										) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			}
			$this->dbAdapter->query($sql);
			$this->dbAdapter->insert($tablename,$arr);
		}
		$arr['groups'] = implode(",",$_POST['gname']);
		if(insertId){
			$arr['groups'] .= ",".$insertId;
		}
		$arr['groups'] = rtrim($arr['groups'],",");
		$count = $this->dbAdapter->update("mr_subscriber",$arr,"id=".$id);
        $usernames = $_SESSION['Zend_Auth']['storage']->username;
		$description='该用户进行编辑联系人的操作，联系人邮箱为： '.$arr['mailbox'];
		$description_en='The user edit operation, the contact email is: '.$arr['mailbox'];
		self::taskOperationLog($description,$description_en);
		if($count){
			$this->_redirect('contact/personlist');
		}
		$this->_redirect('contact/personlist');
	}
	
	/*联系人删除
	 * 判断删除的是联系人库里面的的联系人还是某个联系人列表里面的数据
	 * 如果是联系人库里面的数据，此联系人可能属于多个组，必须全部删除
	 * 如果是某个联系人列表里面的数据，只需删除本列表里面的数据，同时要更新联系人库中的groups字段信息
	 */
	public function deletepersonAction(){
		$id = $_POST['id'];
		$result = $this->dbAdapter->fetchRow("select * from mr_subscriber where id=".$id);
		$ext = explode(",",$result['groups']);
		$role = $this->getCurrentUserRole(); 
		$userid = $this->getCurrentUserID();

		if($_POST['gid']){
			$gid = $_POST['gid'];
			foreach($ext as $key=>$val){
				if($val == $gid){
					unset($ext[$key]);
				}
			}
			if(!empty($ext)){
				$groups = implode(",",$ext);
				$arr = array('groups'=>$groups);
				$this->dbAdapter->update('mr_subscriber',$arr,"id=".$id);
			}
			$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$gid);
			$this->dbAdapter->delete($tablename,"mailbox='".$result['mailbox']."'");
			$description='该用户进行删除联系人的操作，联系人邮箱为:'.$result['mailbox'];
			$description_en='The user to delete contact operation, contact mail for:'.$result["mailbox"];
			self::taskOperationLog($description,$description_en);
		}else{
			foreach($ext as $e_key=>$e_val){
				if($e_val){
					$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$e_val);
					$this->dbAdapter->query("delete from ".$tablename." where mailbox='".$result['mailbox']."'");
					if($userid == $result['uid'] || $role == 'stasker'){
						$this->dbAdapter->query("delete from mr_subscriber where id=".$id);
					}
					$description='该用户进行删除联系人的操作，联系人邮箱为:'.$result['mailbox'];
					$description_en='The user to delete contact operation, contact mail for:'.$result["mailbox"];
					self::taskOperationLog($description,$description_en);
				}
			}
		}
	}
	
	/*批量删除联系人
	 * 判断删除的是联系人库里面的的联系人还是某个联系人列表里面的数据
	 * 如果是联系人库里面的数据，此联系人可能属于多个组，必须全部删除
	 * 如果是某个联系人列表里面的数据，只需删除本列表里面的数据，同时要更新联系人库中的groups字段信息
	 */
	public function ajaxdelallAction(){
		$ids = $_POST['ids'];
		$gid = $_POST['gid'];
		$del_usernames ="";
		$ext = explode(",",$ids);
		array_pop($ext);
		$uid=$_SESSION['Zend_Auth']['storage']->id;
		$Jurisdiction=$this->getCurrentUserRole();
		
		if( $Jurisdiction == "stasker"){
			$stasker_sql = "SELECT id FROM mr_accounts WHERE role = 'stasker' or role = 'tasker'";
			$stasker_resoult = $this->dbAdapter->fetchAll($stasker_sql);
			if(count($stasker_resoult)>1){
				foreach($stasker_resoult as $v2){
					$stasker_array[]=$v2['id'];
				}
				$final_id =join(',',$stasker_array);
			}else{
				$final_id = 2;
			}
		}else if( $Jurisdiction == "tasker"){
			$final_id = $uid;
		}
		$acl_sql = "SELECT id FROM mr_subscriber WHERE uid IN ({$final_id})";//stasker把所有联系人id找到，tasker把自己创建的所有联系人id找到
		$resoult_sql = $this->dbAdapter->fetchAll($acl_sql);
		if($resoult_sql){
			foreach($resoult_sql as $vals){
					$acl_array[]=$vals['id'];
			}
		}
		$mine = array();
		$exl_arr = explode(',',trim($ids,","));//将所有选择要删除的联系人 id 创建成一个数组
		foreach($exl_arr as $k=>$axp){
			if(in_array($axp,$acl_array)){//为stasker时，$acl_array指所有联系人；为tasker时，$acl_array指该发布员创建的所有联系人
				$mine[] = $axp;
				unset($exl_arr[$k]);	
			}
		}

			// exit;

		if($gid){
			if($ext){
				foreach($ext as $key=>$val){
					if($val){
						$result = $this->dbAdapter->fetchRow("select groups,mailbox,username from mr_subscriber where id=".$val);
						$del_usermails .= $result['mailbox'].",";
						$arr = explode(",",$result['groups']);
						if($arr){
							foreach($arr as $k=>$v){
								if($v){
									if($gid == $v){
										$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$v);
										$this->dbAdapter->delete($tablename,"mailbox='".$result['mailbox']."'");
										unset($arr[$k]);
									}
								}
							}
						}
						$groups = implode(",",$arr);
						if($groups){
							$count = $this->dbAdapter->update("mr_subscriber",array('groups'=>$groups),"id=".$val);
						}
					}
				}
			} 
			$del_usermails = trim($del_usermails,",");
	        $usernames = $_SESSION['Zend_Auth']['storage']->username;
			$description='该用户进行批量删除联系人的操作，联系人邮箱为:'.$del_usermails;
			$description_en='The user batch delete contact operation，the contact mail is:'.$del_usermails;
			self::taskOperationLog($description,$description_en);			
		}else{
			if(empty($exl_arr)){
				if($mine){
				foreach($mine as $key=>$val){
					if($val){
						$result = $this->dbAdapter->fetchRow("select groups,mailbox,username from mr_subscriber where id=".$val);
						$del_usermails .= $result['mailbox'].",";
						$arr = explode(",",$result['groups']);
						if($arr){
							foreach($arr as $k=>$v){
								if($v){
									$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$v);
									$this->dbAdapter->delete($tablename,"mailbox='".$result['mailbox']."'");
									}
								}
							}
							$count = $this->dbAdapter->delete("mr_subscriber","id=".$val);//echo $count;exit;
						}
					}
				}
			}else{//要删除的并不都是自己建的
				foreach($exl_arr as $v){
					$groups = $this->dbAdapter->fetchOne("select groups from mr_subscriber where id=".$v);
					$group = explode(",",$groups);
					if(count($group) == 1 ){
						foreach($group as $gval){
							$guid = $this->dbAdapter->fetchOne("select uid from mr_group where id=".$gval );
						}
						if($uid == $guid){
							array_push($mine,$v);
						}
					}else{
						echo "error";
					}
				}
				if($mine){
					foreach($mine as $key=>$val){
						if($val){
							$result = $this->dbAdapter->fetchRow("select groups,mailbox,username from mr_subscriber where id=".$val);
							$del_usermails .= $result['username'].",";
							$arr = explode(",",$result['groups']);
							if($arr){
								foreach($arr as $k=>$v){
									if($v){
										$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$v);
										$this->dbAdapter->delete($tablename,"mailbox='".$result['mailbox']."'");
									}
								}
							}
							$count = $this->dbAdapter->delete("mr_subscriber","id=".$val);//echo $count;exit;
						}
					}
				}
			}
			$del_usermails = trim($del_usermails,",");
	        $usernames = $_SESSION['Zend_Auth']['storage']->username;
			$description='该用户进行批量删除联系人的操作，联系人邮箱为:'.$del_usermails;
			$description_en='The user batch delete contact operation，the contact mail is:'.$del_usermails;
			self::taskOperationLog($description,$description_en);
		}
	
	}
	
	/*
	 * 批量删除联系人组
	 * 在批量删除联系人组的同时要删除随创建该组时生成的成员表，还要修改mr_subscriber表中的groups字段
	 */
	public function ajaxdelallgroupsAction(){
		$ids = $_POST['ids'];
		$ids = trim($ids , ",");
		$ext = explode(",",$ids);
		$uid=$_SESSION['Zend_Auth']['storage']->id;
		$role = $_SESSION['Zend_Auth']['storage']->role;
		$gnames = "";
		if($ext){
			foreach($ext as $key=>$val){
				if($val){
					$arr = $this->dbAdapter->fetchRow("select * from mr_group where id=".$val);
					$mailboxs = $this->dbAdapter->fetchAll("select mailbox from ".$arr['tablename']);
					if($arr['uid'] == $uid || $role =="stasker" ){
						if( count($mailboxs) > 0){
							foreach($mailboxs as $mk=>$mv){
								$groups = $this->dbAdapter->fetchOne("select groups from mr_subscriber where mailbox='".$mv['mailbox']."'");//每个成员的组
								$gids = explode(",",$groups);
								if(count($gids) <= 1){//该成员仅属于一个组，删除该成员
									$this->dbAdapter->query("delete from mr_subscriber where mailbox='".$mv['mailbox']."'");
								}else{//该成员属于多个组，修改该成员的groups值
									foreach($gids as $gk=>$gv){
										if($gv == $arr['id']){
											unset($gids[$gk]);
										}
									}
									$newgroups = implode(",",$gids);
									if($gids){
										$this->dbAdapter->query("update mr_subscriber set groups='".$newgroups."' where mailbox='".$mv['mailbox']."'");
									}
								}
							} 
						}
						$this->dbAdapter->query("drop table ".$arr['tablename']);
						$arr = $this->dbAdapter->fetchRow("select * from mr_group where id=".$val);
						$gnames .= $arr['gname'].",";
						$this->dbAdapter->query("delete from mr_group where id=".$val);
					} 
				}
			}
			$gnames = trim($gnames,",");
			if($gnames){
				$description='该用户进行批量删除联系人组的操作,联系人组名为:'.$gnames;
				$description_en='The user batch delete contact group operation ,the contact group is: '.$gnames;
				self::taskOperationLog($description,$description_en);
			}
			echo (count($ext)-1);
		}
	}
	
	//自定义字段功能模块
	public function expansionAction(){
		$num = $_GET['num']?$_GET['num']:10;
		$res = $this->dbAdapter->fetchAll("select * from mr_group_extension where hidden=0");
		$total = count($res);
		$page = new Page($total,$num);		
		$select = $this->dbAdapter->select();
		$select->from('mr_group',array('id','gname'));
		$result = $this->dbAdapter->fetchAll($select);
		$select2 = $this->dbAdapter->select();
		$select2->from('mr_group_extension',array('id','gid','showname','name','type'));
		$res = $this->dbAdapter->fetchAll("select * from mr_group_extension where hidden=0 order by id desc {$page->limit}");
		foreach($res as $r_key=>$r_val){
			if($r_val['type']==1){
				$res[$r_key]['typename'] = 'char';
			}elseif($r_val['type']==2){
				$res[$r_key]['typename'] = 'int';
			}elseif($r_val['type']==3){
				$res[$r_key]['typename'] = 'datetime';
			}else{
				$res[$r_key]['typename'] = 'float';
			}
		}
		$this->Smarty->assign('res',$res);
		$this->Smarty->assign('page',$page->fpage());
		$this->Smarty->assign('num',$num);
		$this->Smarty->assign('data',$result);
		$this->Smarty->assign('li_menu','expansion');
		$this->Smarty->display("expansion.php");
	}

	//添加自定义字段
	public function addexpansionAction(){
		$showname = $_POST['showname'] ? $_POST['showname'] : '';
		$name = $_POST['name'] ? $_POST['name'] : '';
		if( strpos($name," ") != false){
			$name = str_replace(" ","",$name);
		}

		$uid = $_SESSION['Zend_Auth']['storage']->id;
		$type = $_POST['type'] ? $_POST['type'] : 0;
		switch ($type)
		{
			case 1:
			  $typename = 'char(128)';
			  break;  
			case 2:
			  $typename = 'int(10)';
			  break;
			case 3:
			  $typename = 'date';;
			  break;
			case 4:
			  $typename = 'float';
			  break;
		}
		$arr = array('showname'=>$showname,'type'=>$type,'name'=>$name);

		$sql = "alter table mr_subscriber add column `".$name."` ".$typename." not null ";
		$this->dbAdapter->query($sql);
		$tablename = $this->dbAdapter->fetchAll("select tablename from mr_group");
		if($tablename){
			foreach($tablename as $key=>$val){
				$sql = "alter table ".$val['tablename']." add column `".$name."` ".$typename." not null ";
				$this->dbAdapter->query($sql);
			}
		}
		$uname = $this->getCurrentUser();
		$insert = $this->dbAdapter->insert('mr_group_extension',$arr);
		$description='该用户进行添加自定义字段的操作，字段为：'.$showname;
		$description_en='The user add custom field operation, the field is: '.$name;
		self::taskOperationLog($description,$description_en);
		$this->_redirect('contact/expansion');		
	}
	
	//编辑自定义字段
	public function editexpansionAction(){
		$showname = $_POST['showname'];
		$name = $_POST['name'];
		if( strpos($name," ") != false){
			$name = str_replace(" ","",$name);
		}
		$id = $_POST['id'];
		$type = $_POST['type'];
		switch ($type)
		{
			case 1:
			  $typename = 'char';
			  break;  
			case 2:
			  $typename = 'int';
			  break;
			case 3:
			  $typename = 'datetime';;
			  break;
			case 4:
			  $typename = 'float';
			  break;
		}
		$brr = $this->dbAdapter->fetchRow("select * from mr_group_extension where id=".$id);
		$arr = array("showname"=>$showname,"name"=>$name,"type"=>$type);
		$count = $this->dbAdapter->update('mr_group_extension',$arr,'id='.$id);
		$tablename = $this->dbAdapter->fetchAll("select tablename from mr_group ");
		$this->dbAdapter->query("alter table mr_subscriber change `".$brr['name']."` `".$name."` ".$typename);
		if($tablename){
			foreach($tablename as $key=>$val){
				$this->dbAdapter->query("alter table ".$val['tablename']." change `".$brr['name']."` `".$name."` ".$typename);
			}
		}
		$uname = $this->getCurrentUser();
		$description='该用户进行编辑自定义字段的操作,字段为：'.$showname;
		$description_en='The user edit custom field operation,the field is'.$name;
		self::taskOperationLog($description,$description_en);
		$this->_redirect("contact/expansion");
	}
	
	//删除自定义字段
	public function deletexpansionAction(){
		$id = $_POST['id'];
		$del_all = $this->_request->getPost('del_str_id');
		$extension ="";

		if ($id) {
			$arr = $this->dbAdapter->fetchRow("select * from mr_group_extension where id=".$id);
			$extension = $arr['showname'];
			$this->dbAdapter->query("alter table `mr_subscriber` drop column `" . $arr['name'] . "`");
			$tablename = $this->dbAdapter->fetchAll("select tablename from mr_group");
			if($tablename){
				foreach($tablename as $key=>$val){
					$this->dbAdapter->query("alter table ".$val['tablename']." drop column `" . $arr['name'] . "`");
				}
			}
			$count = $this->dbAdapter->delete('mr_group_extension','id='.$id);
			$description = '该用户进行删除自定义字段的操作,字段为：'.$extension;
			$description_en = 'The user delete custom field operation, the fields is: '.$extension;
			self::taskOperationLog($description,$description_en);
		}
		if ($del_all) {
			$del_all = trim($del_all,',');
			$names_arr = $this->dbAdapter->fetchAll("SELECT * FROM mr_group_extension WHERE id in(".$del_all.")");
			foreach($names_arr as $v){
				$extension .= $v['showname'].',';
				$this->dbAdapter->query("alter table `mr_subscriber` drop column `" . $v['name'] . "`");
				$tablename = $this->dbAdapter->fetchAll("select tablename from mr_group");
				if($tablename){
					foreach($tablename as $vv){
						$this->dbAdapter->query("alter table ".$vv['tablename']." drop column `" . $v['name'] . "`");
					}
				} 
				$count = $this->dbAdapter->query("delete from `mr_group_extension` where id in(".$del_all.")");
			}
			$extension = trim($extension ,',');
			$description = '该用户进行批量删除自定义字段的操作,字段为：'.$extension;
			$description_en = 'The user batch delete custom field operation, the fields is: '.$extension;
			self::taskOperationLog($description,$description_en);
		}
		$this->_redirect("contact/expansion");
	}
	
	//用于生成一个唯一的字符
	public static function uuid() {
  		$chars = md5(uniqid(mt_rand(), true));
  		$uuid  = substr($chars,0,8) . '_';
  		$uuid .= substr($chars,8,4) . '_';
  		$uuid .= substr($chars,12,4) . '_';
  		$uuid .= substr($chars,16,4) . '_';  
  		$uuid .= substr($chars,20,12);
  		return $uuid;
	}
	
	//批量添加联系人
	public function addallpersonAction(){
		$gpid = $_POST['gpid'];
		$ids = $_POST['ids'];
		$newgroup = str_replace("","",$_POST['newgroup']);
		$newgroup = str_replace(" ","",$newgroup);
		$gname = $_POST['gname'];
		$ids = rtrim($ids,",");
		$result = $this->dbAdapter->fetchAll("select * from mr_subscriber where id in(".$ids.")");
		if($newgroup){
			$tablename = 'mr_group_'.self::uuid();
			$uid = $_SESSION['Zend_Auth']['storage']->id ? $_SESSION['Zend_Auth']['storage']->id : 0;
			$arr = array('uid'=>$uid,
						 'createtime'=>date('Y-m-d H:i:s'),
						 'gname'=>$newgroup,
						 'remark'=>$_POST['remark'] ? $_POST['remark']:"",
						 'tablename'=>$tablename);
			$insert = $this->dbAdapter->insert('mr_group',$arr);
			$gid = $this->dbAdapter->lastInsertId();
			$extension = $this->dbAdapter->fetchAll("select showname,name,type from mr_group_extension where hidden=0");
			if($extension){
				foreach($extension as $e_key=>$e_val){
					if($e_val['type']==1){
						$type = "char(128)";
					}elseif($e_val['type']==2){
						$type = "int(10)";
					}elseif($e_val['type']==3){
						$type = "datetime";
					}else{
						$type = "float";
					}
					$str .= "`".$e_val['name']."` ".$type." NOT NULL ,";
					$arr[$e_val['name']] = $_POST[$e_val['name']];
				}
			}
			$str = rtrim($str,",");
			if($str != ""){
				$sql = "CREATE TABLE ".$tablename."(
										`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
										`uid` INT UNSIGNED NOT NULL DEFAULT 0,
										`username` varchar(256) NOT NULL,
										`mailbox` varchar(256) NOT NULL,
										`sex` tinyint(1) NOT NULL DEFAULT 1,
										`birth` int(10) NOT NULL DEFAULT 0,
										`tel` varchar(20),".$str.",
										UNIQUE KEY `name`(`mailbox`)
									) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			}else{
				$sql = "CREATE TABLE ".$tablename."(
										`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
										`uid` INT UNSIGNED NOT NULL DEFAULT 0,
										`username` varchar(256) NOT NULL,
										`mailbox` varchar(256) NOT NULL,
										`sex` tinyint(1) NOT NULL DEFAULT 1,
										`birth` int(10) NOT NULL DEFAULT 0,
										`tel` varchar(20),
										UNIQUE KEY `name`(`mailbox`)
									) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			}
			$this->dbAdapter->query($sql);
			if($result){
				foreach($result as $key=>$val){
					$result[$key]['groups'] = $result[$key]['groups'].",".$gid;
					$this->dbAdapter->update('mr_subscriber',array('groups'=>$result[$key]['groups']),'id='.$result[$key]['id']);
					unset($result[$key]['groups']);
					unset($result[$key]['id']);
					unset($result[$key]['subscribe']);
					unset($result[$key]['unsubscribenumber']);
					$this->dbAdapter->insert($tablename,$result[$key]);
				}
			}
		}
			
		$result = $this->dbAdapter->fetchAll("select * from mr_subscriber where id in(".$ids.")");
		if($gname){
			foreach($gname as $k_g=>$v_g){
				$tablename2 = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$v_g);
				$gname = $this->dbAdapter->fetchOne("select gname from mr_group where id=".$v_g);
				if($result){
					foreach($result as $k_r=>$v_r){
						$count = $this->dbAdapter->fetchRow("select * from ".$tablename2." where mailbox='".$v_r['mailbox']."'");
						if(empty($count)){
							$result[$k_r]['groups'] = $result[$k_r]['groups'].",".$v_g;

							$this->dbAdapter->query("update mr_subscriber set groups='".$result[$k_r]['groups']."' where id=".$result[$k_r]['id']);
							unset($result[$k_r]['groups']);
							unset($result[$k_r]['id']);
							unset($result[$k_r]['subscribe']);
							unset($result[$k_r]['unsubscribenumber']);
							$this->dbAdapter->insert($tablename2,$result[$k_r]);
						}
					}
				}
			}
		}
		$uname = $this->getCurrentUser();
		$description='该用户进行批量合并联系人的操作，添加进组：'.$gname;
		$description_en='The user batch merge contact operation, added to the group:'.$gname;
		self::taskOperationLog($description,$description_en);
		$this->_redirect("contact/personlist");
	}
	
	//ajax判断联系人组是否重复,邮箱是否重复
	public function ajaxgroupAction(){
		$uid = $_SESSION['Zend_Auth']['storage']->id;
		if($_POST['status']=='editperson'){
			$id = $_POST['id'];
			$gname = $_POST['gname'];
			$mailbox = $_POST['mailbox'];
			$arr = $this->dbAdapter->fetchRow("select mailbox from mr_subscriber where id<>".$id." and mailbox='".$mailbox."'");
			if($gname){
				$brr = $this->dbAdapter->fetchRow("select gname from mr_group where gname='".$gname."'");
			}
			$crr = array_merge((array)$arr,(array)$brr);
			if(!empty($crr)){
				echo json_encode($crr);
			}else{
				echo 0;
			}
		}else if($_POST['status']=='editgroup'){
			$id = $_POST['id'];
			$gname = $_POST['gname'];
			$select = $this->dbAdapter->select();
			$select->from('mr_group','gname');
			$select->where('id<>'.$id.' and gname="'.$gname.'"');
			$result = $this->dbAdapter->fetchAll($select);
			if(!empty($result)){
				echo 1;
			}else{
				echo 0;
			}
		}else if($_POST['status']=='update'){
				$id = $_POST['id'];
				$gname = $_POST['gname'];
				$mailbox = $_POST['mailbox'];
				$arr = $this->dbAdapter->fetchRow("select mailbox from mr_subscriber where id<> ".$id." and mailbox='".$mailbox."'");
				if($gname){
					$brr = $this->dbAdapter->fetchRow("select gname from mr_group where gname='".$gname."'");
				}
				$crr = array_merge((array)$arr,(array)$brr);
				if(!empty($crr)){
					echo json_encode($crr);
				}else{
					echo 0;
				}
		}else{
			if($_POST['mailbox']==''){
				$gname = $_POST['gname'];
				$select = $this->dbAdapter->select();
				$select->from('mr_group','gname');
				$select->where('gname="'.$gname.'"');
				$result = $this->dbAdapter->fetchAll($select);
				if(!empty($result)){
					echo 1;
				}else{
					echo 0;
				}
			}else{
				$gname = $_POST['gname'];
				$mailbox = $_POST['mailbox'];
				$arr = $this->dbAdapter->fetchRow("select mailbox from mr_subscriber where mailbox='".$mailbox."'");
				if($gname){
					$brr = $this->dbAdapter->fetchRow("select gname from mr_group where gname='".$gname."'");
				}
				$crr = array_merge((array)$arr,(array)$brr);
				if(!empty($crr)){
					echo json_encode($crr);
				}else{
					echo 0;
				}
			}
		}
	}
	//ajax判断添加字段是否重复
	public function ajaxextensionAction(){
		$uid = $_SESSION['Zend_Auth']['storage']->id;
		$showname = $_POST['showname'];
		$name = $_POST['name'];
		$id = $_POST['id']? $_POST['id']:0;
		if(!empty($showname)){
			$arr = $this->dbAdapter->fetchRow("select showname from mr_group_extension where  showname= '".$showname."' and id<>".$id);
		}
		if(!empty($name)){
			$brr = $this->dbAdapter->fetchRow("select name from mr_group_extension where name='".$name."' and id<>".$id);
		}
		if(!empty($arr) || !empty($brr)){
			$uname = $this->getCurrentUser();
			$description='该用户判断添加的字段是否重复的操作';
			$description_en='The user batch add contact operation';
			self::taskOperationLog($description,$description_en);
		}
		$res = array_merge((array)$arr,(array)$brr);
		if($res){
			echo json_encode($res);
		}else{
			echo 1;
		}
	}
	
	//ajax获取编辑联系人组信息
	public function getinfoAction(){
		if($_POST['status'] == 'group'){
			$id = $_POST['id'];
			$result = $this->dbAdapter->fetchRow("select * from mr_group where id=".$id);
			echo json_encode($result);
		}else if($_POST['status'] == 'person'){
			$id = $_POST['id'];
			if($_POST['gid']){
				$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$_POST['gid']);
				$result = $this->dbAdapter->fetchRow("select * from ".$tablename." where mailbox='".$_POST['mailbox']."'");
			}else{
				$result = $this->dbAdapter->fetchRow("select * from mr_subscriber where id=".$id);
			}
				if($result['birth']){
					$result['birth'] = date("Y-m-d",$result['birth']);
				}
			echo json_encode($result);
		}else if($_POST['status'] == 'expansion'){
			$id = $_POST['id'];
			$result = $this->dbAdapter->fetchRow("select * from mr_group_extension where id=".$id);
			echo json_encode($result);
		}
	}

	function array_unique_fb($array2D){
		$toArr =array();
		$keyArr = array();
		$newKey = array();
		foreach($array2D as $k=>$v){
				$toArr[$k]=$v[1];
				$keyArr[]=$k;
			}
			$toArr=array_unique($toArr);
			foreach($toArr as $key=>$toval){
					$newKey[]=$key;
			}
			$temp = array_diff($keyArr,$newKey);

			foreach($temp as $delval){
				unset($array2D[$delval]);
			}
			return $array2D;
		}	 
	
	//导入功能
	public function importAction(){
		$uid = $_SESSION['Zend_Auth']['storage']->id ? $_SESSION['Zend_Auth']['storage']->id : 0;
		$gid = $_POST['importgname'];
		$brr = array();
		$tmp = $_FILES['file']['tmp_name'];
		$filename = $_FILES['file']['name'];
		if (empty ($tmp)) { 
		    echo '请选择要导入的Excel文件！'; 
		    exit; 
		} 
		//取自定义字段
		$res = $this->dbAdapter->fetchAll("select * from mr_group_extension");
		$total_res = count($res);
		
		$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$gid);
		$gname = $this->dbAdapter->fetchOne("select gname from mr_group where id=".$gid);
		
		$save_path = "xls/";
		$extend=strrchr ($filename,'.');
		$file_name = $save_path.date('Ymdhis') . $extend; //上传后的文件保存路径和名称
		$result=move_uploaded_file($tmp,$file_name);
		
		if($result){
            if( $extend == '.xlsx' ) {
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            } else {
                $objReader = PHPExcel_IOFactory::createReader('Excel5');
            }
			$objPHPExcel = $objReader->load($file_name);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); //取得总行数
			$highestColumn = $objWorksheet->getHighestColumn(); //取得总列数
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
			$worksheet = $objPHPExcel->getActiveSheet();

			if($total_res!=$highestColumnIndex){
				$this->_redirect('/contact/personlist?msg=DRTBF');
				exit;
			}
			for($row = 2;$row <= $highestRow;$row++){
				for($col = 0;$col < $highestColumnIndex;$col++){
					// 参考 https://blog.csdn.net/weixin_42266757/article/details/81487480
					$cell = $objWorksheet->getCellByColumnAndRow($col, $row);
					$cellstyleformat = $worksheet->getStyle($cell->getCoordinate())->getNumberFormat();
					$formatcode = $cellstyleformat->getFormatCode();
					$f_value = $cell->getFormattedValue();

					$strs[$row][$col] = trim($f_value);
				}
			}
			
			$strs=$this->array_unique_fb($strs);

			//导入到联系人组中
			$value = "";
			if(!empty($strs)){
				foreach($strs as $sk=>$sv){
					if(!empty($sv)){
						$email = "/^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,4}$/i";
						$phone = "/^0{0,1}(13[0-9]|15[0-9]|16[0-9]|17[0-9]|18[0-9])[0-9]{8}$/";
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
						$number = $this->dbAdapter->fetchOne("select count(id) from ".$tablename." where mailbox='".$sv[1]."'");
						$exist_mail = $this->dbAdapter->fetchOne("select uid from mr_subscriber where mailbox='".$sv[1]."'");
						if($number>0){
							continue;
						}
						if( $sv[3] == "" || $sv[3] == NULL || $sv[3] == " "){
							$strs[$sk][3] = "";
						}else{
							$strs[$sk][3] = strtotime($sv[3]);
						}
						$value .= "(";
						
						foreach($strs[$sk] as $key=>$val){
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
			if($value){
				$sql1 = "insert ignore into ".$tablename." ".$type." values ".$value;
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

						if(!empty($sv[4])){
							if(!preg_match($phone,$sv[4])){
								$strs[$sk][4] = "";
								$sv[4] = "";
							}
						}
						$number = $this->dbAdapter->fetchOne("select count(id) from mr_subscriber where mailbox='".$sv[1]."'");
						if($number>0){
							continue;
						}
						$subscriber_value .= "(";

						foreach($sv as $key=>$val){
							$subscriber_value .= "'".$val."',";
						}
					}

					$subscriber_value .= "'".$uid."','$groups'),";
				}
			}
			$subscriber_value = rtrim($subscriber_value,",");
			$type = "(";
			foreach($res as $rk=>$rv){
				$type .= $rv['name'].",";
			}
			$type .= "uid,groups)";
			
			if($subscriber_value){
				$sql2 = "insert ignore into mr_subscriber ".$type." values ".$subscriber_value;
				
				$query2 = $this->dbAdapter->query($sql2);
			}
			if($brr){
		    	foreach($brr as $key=>$val){
		    		if($val){
		    			$groups = $this->dbAdapter->fetchOne("select groups from mr_subscriber where mailbox='".$val."' and uid=".$uid);
		    			$ext = explode(",",$groups);
		    			if($groups){
			    			if(!in_array($gid,$ext)){
			    				$newgroups = $groups.",".$gid;
			    				$this->dbAdapter->query("update mr_subscriber set groups='".$newgroups."' where mailbox='".$val."' and uid=".$uid);
			    			}
		    			}else{
		    				$this->dbAdapter->query("update mr_subscriber set groups='".$gid."' where mailbox='".$val."' and uid=".$uid);
		    			}
		    		}
		    	}
		    }
		}
		unlink("/var/www/maildelivery/".$file_name);
		$description='该用户进行导入联系人的操作，联系人组：'.$gname;
		$description_en='The user to import contacts operation, the contact group is: '.$gname;;
		self::taskOperationLog($description,$description_en);
		$this->_redirect('contact/personlist');
	}
	
	//导出功能
	public function exportAction(){
		$resultPHPExcel = new PHPExcel(); 
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', '姓名'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '邮箱'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '性别'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '出生日期'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '手机'); 
		
		$resultPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10); 
		$resultPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25); 
		$resultPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
		$resultPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25); 
		$resultPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20); 
		
		$gid = $_POST['exportgname'];
		$result = $this->dbAdapter->fetchAll("select showname,name from mr_group_extension where hidden=0");
		$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$gid);
		$gname = $this->dbAdapter->fetchOne("select gname from mr_group where id=".$gid);
		$result_person = $this->dbAdapter->fetchAll("select * from ".$tablename);		
		$j = 1;
		if(!empty($result)){
			foreach($result as $key=>$val){
				$resultPHPExcel->getActiveSheet()->setCellValue(chr(ord('E')+$j).'1', $val['showname']); 
				$resultPHPExcel->getActiveSheet()->getColumnDimension(chr(ord('E')+$j))->setWidth(20); 
		        $j++;
			}
		}
		$i = 2; 
		if(!empty($result_person)){
			foreach($result_person as $rpk=>$rpv){ 
				$m = 1;
				$resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, $rpv['username']); 
				$resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $rpv['mailbox']); 
				if($rpv['sex'] == 1){
					$resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, '男'); 
				}else{
					$resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, '女'); 
				}
				if($rpv['birth']!=0){
					$resultPHPExcel->getActiveSheet()->setCellValue('D' . $i, date("Y-m-d",$rpv['birth'])); 
				}else{
					$resultPHPExcel->getActiveSheet()->setCellValue('D' . $i, ""); 
				}
				$resultPHPExcel->getActiveSheet()->setCellValue('E' . $i, $rpv['tel']); 

				if(!empty($result)){
					foreach($result as $rk=>$rv){
						$resultPHPExcel->getActiveSheet()->setCellValue(chr(ord('E')+$m).$i, $rpv[$rv['name']]); 
						$m++;
					}
				}
				$i ++; 
			}
		}
		$outputFileName = date("YmdHis").".xls";

		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel); 

		//ob_start(); ob_flush(); 
		ob_end_clean();

		header("Content-Type: application/force-download"); 

		header("Content-Type: application/octet-stream"); 

		header("Content-Type: application/download"); 

		header('Content-Disposition:inline;filename="'.$outputFileName.'"'); 

		header("Content-Transfer-Encoding: binary"); 

		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 

		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 

		header("Pragma: no-cache"); 

		$xlsWriter->save( "php://output" );
		
		$description='该用户进行导出联系人的操作，联系人组：'.$gname;
		$description_en='The user to export contacts operation, the contact group is: '.$gname;;
		self::taskOperationLog($description,$description_en);
	}
	
	//导出模板
	public function exportviewAction(){
		$resultPHPExcel = new PHPExcel(); 
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', '姓名'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '邮箱(XXX@XXX.XXX)'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '性别(男或女)'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '出生日期(YYYY-MM-DD)'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '手机(11位数字组合)'); 
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A2', ' '); 
		$resultPHPExcel->getActiveSheet()->setCellValue('B2', ' '); 
		$resultPHPExcel->getActiveSheet()->setCellValue('C2', ' '); 
		$resultPHPExcel->getActiveSheet()->setCellValue('D2', ' '); 
		$resultPHPExcel->getActiveSheet()->setCellValue('E2', ' '); 
		
		$resultPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10); 
		$resultPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25); 
		$resultPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
		$resultPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25); 
		$resultPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20); 
		$result = $this->dbAdapter->fetchAll("select showname,name from mr_group_extension where hidden=0");		
		$j = 1;
		if(!empty($result)){
			foreach($result as $key=>$val){
				$resultPHPExcel->getActiveSheet()->setCellValue(chr(ord('E')+$j).'1', $val['showname']); 
				$resultPHPExcel->getActiveSheet()->getColumnDimension(chr(ord('E')+$j))->setWidth(20); 
				$j++;
			}
		}
		$outputFileName = date("YmdHis").".xls";

		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel); 

		header("Content-Type: application/force-download"); 

		header("Content-Type: application/octet-stream"); 

		header("Content-Type: application/download"); 

		header('Content-Disposition:inline;filename="'.$outputFileName.'"'); 

		header("Content-Transfer-Encoding: binary"); 

		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 

		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 

		header("Pragma: no-cache"); 

		$xlsWriter->save( "php://output" );
	}
	
	//订阅管理
	public function subscribeAction(){
		$uid = $_SESSION['Zend_Auth']['storage']->id;
		$result = $this->dbAdapter->fetchAll("select * from mr_usersubscription where uid =".$uid);
		if($result){
			$this->_redirect('contact/formlist');
		}else{
			$this->Smarty->assign ("li_menu", "subscribe");
			$this->Smarty->display("subscribe.php");
		}
	}
	
	//创建订阅表单页面
	public function createformAction(){
		$uid = $_SESSION['Zend_Auth']['storage']->id;
		$role = $_SESSION['Zend_Auth']['storage']->role;
		
		$groups = $this->dbAdapter->fetchAll("select * from mr_group where uid=".$uid);
		
		$basic = $this->dbAdapter->fetchAll("select id,showname,name from mr_group_extension where hidden=1");
		$extension = $this->dbAdapter->fetchAll("select id,showname,name from mr_group_extension where hidden=0");
		$this->Smarty->assign('basic',$basic);
		$this->Smarty->assign('extension',$extension);
		if(!empty($groups)){
			foreach($groups as $key=>$val){
				if(strlen($val['gname'])>10){
					$groups[$key]['gm'] = mb_substr($val['gname'],0,10);
				}
			}
		}
		$this->Smarty->assign('groups',$groups);
		$this->Smarty->assign ("li_menu", "subscribe");
		$this->Smarty->display("createform.php");
	}
	
	//生成表单并插入数据库
	public function docreateformAction(){
		$fname = $_POST['fname'];
		$thanks = "";
		$success = "";
		$validation = "";
		$welcome = "";
		$userinfo = "";
		//感谢订阅
		$thanks .= "<!DOCTYPE html><html lang='en'><head><title>激活提醒</title><meta http-equiv='content-type' content='text/html;charset=utf-8' />";
		$thanks .= "<script type='text/javascript' src='/dist/js/jquery.min.js'></script>";
		$thanks .= "</head><body>";
		$thanks .= "<html>";
		$thanks .= "<table align='center' width='100%'>";
		$thanks .= "<tr><td><h2>感谢您的订阅</h2></td></tr>";
		$thanks .= "<tr><td>感谢您的订阅，我们将竭诚为您服务。</td></tr>";
		$thanks .= "<tr><td>您将定期收到我们的邮件，如果您不想接收，请在接收到的邮件中进行退订操作。</td></tr>";
		$thanks .= "</table>";
		$thanks .= "</body>";
		$thanks .= "</html>";
		
		//订阅成功
		$success .= "<!DOCTYPE html><html lang='en'><head><title>感谢订阅</title><meta http-equiv='content-type' content='text/html;charset=utf-8' />";
		$success .= "<script type='text/javascript' src='/dist/js/jquery.min.js'></script>";
		$success .= "</head><body>";
		$success .= "<html>";
		$success .= "<table align='center' width='100%'>";
		$success .= "<tr><td><h2>感谢订阅</h2></td></tr>";
		$success .= "<tr><td>感谢您的订阅，我们将竭诚为您服务。</td></tr>";
		$success .= "<tr><td>您将定期收到我们的邮件，如果您不想接收，请在接收到的邮件中进行退订操作</td></tr>";
		$success .= "</table>";
		$success .= "</body>";
		$success .= "</html>";
		
		//点击链接通过订阅验证
		$validation .= "<meta content='text/html; charset=utf-8' http-equiv='Content-Type' />";
		$validation .= "<title></title>";
		$validation .= "<table align='center' border='0' cellpadding='0' cellspacing='0' width='600'>";
		$validation .= "<tbody><tr><td style='font-size:19px; font-family:Arial; color:#222; padding-top:20px; font-weight:bold;'>亲爱的[$email]：</td></tr>";
		$validation .= "<tr><td style='font-size:14px; font-family:Arial; color:#222; line-height:20px; padding:20px 0 10px 0;'>您好！感谢您的订阅，点击下面的链接即可完成订阅：<br />
						<a href='[$href]' style='color:#35639c; text-decoration:underline; line-height:18px;' target='_blank'>[$href]</a>。</td></tr>";
		$validation .= "<tr><td style='font-size:14px; font-family:Arial; color:#222; line-height:18px; padding:0 0 10px 0;'>(如果链接无法点击，请将它复制并粘贴到浏览器的地址栏中访问)</td></tr>";
		$validation .= "</tbody>";
		$validation .= "</table>";
		
		//欢迎订阅
		$welcome .= "<meta content='text/html; charset=utf-8' http-equiv='Content-Type' />";
		$welcome .= "<title></title>";
		$welcome .=  "<table align='center' border='0' cellpadding='0' cellspacing='0' width='600'>";
		$welcome .=  "<tbody><tr><td style='font-size:19px; font-family:Arial; color:#222; padding-top:20px; font-weight:bold;'>欢迎您！</td></tr>";
		$welcome .=  "<tr><td style='font-size:14px; font-family:Arial; color:#222; line-height:20px; padding:20px 0 10px 0;'>您将定期收到我们的邮件，如果你不想再接收我们的邮件，可以在邮件中进行退订操作。</td></tr>";
		$welcome .=  "<tr><td style='font-size:14px; font-family:Arial; color:#222; line-height:18px; padding:0 0 10px 0;'>谢谢！</td></tr>";
		$welcome .=  "</tbody>";
		$welcome .=  "</table>";
		
		$arr['uid']	= $_SESSION['Zend_Auth']['storage']->id;
		$arr['formname'] = $fname;
		$arr['description'] = $_POST['description'];
		$arr['formfield'] = $_POST['field'];
		$arr['groups'] = implode(",",$_POST['gname']);
		$arr['buttoname'] = $_POST['subname'];
		$arr['userinfo'] = $userinfo;
		$arr['thanks'] = $thanks;
		$arr['validation'] = $validation;
		$arr['success'] = $success;
		$arr['welcome'] = $welcome;
		$arr['tag'] = 1;
		$arr['rule'] = $_POST['rule'];
		$arr['number'] = $_POST['number'];
		$arr['necessary'] = $_POST['necessary'];

		$count = $this->dbAdapter->insert("mr_usersubscription",$arr);
		$id = $this->dbAdapter->lastInsertId();
		
		$url = $this->dbAdapter->fetchRow("select dport,https,domainname,serviceport from mr_console");
		$userinfo .= "<script>function changeimg(){var target=document.getElementById('iimg');if(target!=null){target.setAttribute('src','http://".$url['domainname'].":".$url['dport']."/index/captcha?'+Math.random());}}</script>";
		$userinfo .= "<form action='http://".$url['domainname'].":".$url['serviceport']."/adduser.php' method='get'>";
		$userinfo .= "<table align='center' cellpadding='0' cellspacing='0' border='1' width='50%'>";
		$userinfo .= "<thead><tr height='35'><td colspan='2' style='text-align:center'><b>".$fname."</b></td></tr></thead>";
		$userinfo .= "<tbody><tr height='35'><td style='text-align:right;width:40%'>邮箱：</td><td><input type='text' name='mailbox' value=''>&nbsp;<span style='color:red'>*</span></td></div></tr>";
		
        if($_POST['field']){
			$field = explode(",",$_POST['field']);
			$necessary = explode(",",$_POST['necessary']);
			$rule = explode(",",$_POST['rule']);
			foreach($field as $key=>$val){
				if($val){
					$brr = $this->dbAdapter->fetchRow("select showname,name from mr_group_extension where id=".$val);
					if($necessary[$key] == 1){
						$userinfo .= "<tr height='35'><td style='text-align:right;width:40%'>".$brr['showname']."：</td><td><input type='text' rule=".$rule[$key]." name='".$brr['name']."' value=''>&nbsp;<span style='color:red'>*</span></td></tr>";
					}else{
						$userinfo .= "<tr height='35'><td style='text-align:right;width:40%'>".$brr['showname']."：</td><td><input type='text' rule=".$rule[$key]." name='".$brr['name']."' value=''></td></tr>";
					}
				}
			}
		}
		$userinfo .= "<input type='hidden' name='usid' id='usid' value='".$id."' />";
		$userinfo .= "<input type='hidden' name='uid' id='uid' value='".$arr['uid']."' />";
		$userinfo .= "<tr height='35'><td style='text-align:right;width:40%'>验证码：</td><td><span style='margin-top: 0px;float:left'><input type='text' id='captcha' name='captcha' value='' /></span><span style='display:block;float:left'><img id='iimg' name='iimg' title='点击更换图片' onclick='changeimg();' src='http://".$url['domainname'].":".$url['dport']."/index/captcha'></span></td></tr>";
		$userinfo .= "<tr height='35'><td colspan='2' style='text-align:center'><input type='submit' value='".$_POST['subname']."' /></td></tr>";
		$userinfo .= "</tbody>";
		$userinfo .= "</table>";
		$userinfo .= "</form>";

		$this->dbAdapter->update("mr_usersubscription",array('userinfo'=>$userinfo),'id='.$id);
		$uname = $this->getCurrentUser();
		$description='该用户进行添加订阅的操作，订阅名称为：'.$arr['formname'];
		$description_en='The user add subscription operations,the subscription name is:'.$arr['formname'];
		self::taskOperationLog($description,$description_en);
		if($count){
			if($_POST['status'] == 'preview'){
				echo $userinfo;
			}else{
				$this->_redirect('contact/formlist');
			}
		}
	}
	
	//ajaxcode判断验证码是否正确
	public function ajaxcodeAction(){
		$code = $_POST['code'];
		if($_SESSION['randval'] != strtoupper($code)){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//订阅表单列表页面
	public function formlistAction(){
		$uid = $_SESSION['Zend_Auth']['storage']->id;
		$role = $_SESSION['Zend_Auth']['storage']->role;
			
		$condition = self::checkLoginAudit($role,$uid);
		$num = $_GET['num']?$_GET['num']:10;
		$res = $this->dbAdapter->fetchAll("select * from mr_usersubscription ".$condition);
		$total = count($res);
		$page = new Page($total,$num);
		$result = $this->dbAdapter->fetchAll("select * from mr_usersubscription ".$condition." order by id desc {$page->limit}");
		foreach($result as $k => $v){
			if( $v['uid'] == $uid){
				$result[$k]['mine'] = 1;
			}else{
				$result[$k]['mine'] = 0;
			}
		}
		$this->Smarty->assign('result',$result);
		$this->Smarty->assign('page',$page->fpage());
		$this->Smarty->assign('li_menu','subscribe');
		$this->Smarty->display("formlist.php");
	}
	
	//订阅中禁用表单功能
	public function updatestatusAction(){
		$id = $_GET['id'];
		$tag = $this->dbAdapter->fetchOne("select tag from mr_usersubscription where id={$id}");
		$formname = $this->dbAdapter->fetchOne("select formname from mr_usersubscription where id=".$id);
		if($tag == 1){
			$this->dbAdapter->update(mr_usersubscription,array('tag'=>0),'id='.$id);
			$description='该用户进行禁用订阅表单操作，订阅名称为：'.$formname;
			$description_en='The user disable the subscription form,the subscription name is: '.$formname;
			self::taskOperationLog($description,$description_en);
		}else{
			$this->dbAdapter->update(mr_usersubscription,array('tag'=>1),'id='.$id);
			$description='该用户进行启用订阅表单操作，订阅名称为：'.$formname;
			$description_en='The user enable the subscription form,the subscription name is: '.$formname;
			self::taskOperationLog($description,$description_en);
		}
		$this->_redirect('contact/formlist');
	}
	
	//订阅管理中预览功能
	public function previewAction(){
		$id = $_GET['id'];
		if($_GET['type'] == 'form'){
			$userinfo = $this->dbAdapter->fetchOne("select userinfo from mr_usersubscription where id={$id}");
			echo $userinfo;
		}elseif($_GET['type'] == 'thanks'){
			$thanks = $this->dbAdapter->fetchOne("select thanks from mr_usersubscription where id={$id}");
			echo $thanks;
		}elseif($_GET['type'] == 'success'){
			$success = $this->dbAdapter->fetchOne("select success from mr_usersubscription where id={$id}");
			echo $success;
		}elseif($_GET['type'] == 'welcome'){
			$welcome = $this->dbAdapter->fetchOne("select welcome from mr_usersubscription where id={$id}");
			echo $welcome;
		}elseif($_GET['type'] == 'validation'){
			$validation = $this->dbAdapter->fetchOne("select validation from mr_usersubscription where id={$id}");
			echo $validation;
		}
	}
	
	//判断订阅者邮箱是否重复
	public function ajaxmailboxAction(){
		$id = $_POST['id'];
		$mailbox = $_POST['mailbox'];
		$groups = $this->dbAdapter->fetchOne("select groups from mr_usersubscription where id={$id}");
		$arr = explode(",",$groups);
		$sum = 0;
		if($arr){
			foreach($arr as $val){
				$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$val);
				$count = $this->dbAdapter->fetchOne("select count(id) from ".$tablename." where mailbox='".$mailbox."'");
				if($count>0){
					$sum = $sum+1;
				}
			}
		}
		if($sum){
			$uname = $this->getCurrentUser();
			$description='该用户进行判断订阅者邮箱是否重复的操作';
			$description_en='The user to determine whether repeated operation subscriber mailbox';
			self::taskOperationLog($description,$description_en);
		}
		if($sum){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//添加订阅者到指定的联系人组中去
	public function adduserAction(){
		$id = $_POST['usid'];
		$crr['mailbox'] = $_POST['mailbox'];
		$arr = $this->dbAdapter->fetchAll("select * from mr_group_extension");
		if($arr){
			foreach($arr as $val){
				if($_POST["{$val['name']}"] == $val['showname']){
					$crr[$val['name']] = " ";
				}else{
					$crr[$val['name']] = $_POST["{$val['name']}"] ? $_POST["{$val['name']}"]:" ";
				}
			}
		}

		$result = $this->dbAdapter->fetchRow("select * from mr_usersubscription where id=".$id);
		$brr = explode(",",$result['groups']);
		if($brr){
			foreach($brr as $val){
				$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$val);

				if($tablename){
					$count = $this->dbAdapter->insert($tablename,$crr);
				}
			}
		}
		$crr['groups'] = $result['groups'];
		$crr['subscribe'] = $result['groups'];
		$count = $this->dbAdapter->insert('mr_subscriber',$crr);
		$uname = $this->getCurrentUser();
		$description='该用户进行添加用户到订阅组中的操作';
		$description_en='The user to add users to subscribe to the group operation this user';
		self::taskOperationLog($description,$description_en);
		if($count>0){
			echo $result['thanks'];
		}else{
			echo $result['userinfo'];
		}
		
	}
	
	//删除订阅表单
	public function delformAction(){
		$id = $_GET['id'];
		$formname = $this->dbAdapter->fetchOne("select formname from mr_usersubscription where id=".$id);
		$count = $this->dbAdapter->delete("mr_usersubscription",'id='.$id);
		$uname = $this->getCurrentUser();
		$description='该用户进行删除订阅表单的操作，订阅名称为：'.$formname;
		$description_en='The user to delete subscription form operation,the subscription name is:'.$formname;
		self::taskOperationLog($description,$description_en);
		if($count>0){
			$this->_redirect('contact/formlist');
		}
	}
	
	//编辑页面的html代码
	public function edittemplateAction(){
		$id = $_GET['id'];
		$result = $this->dbAdapter->fetchRow("select * from mr_usersubscription where id=".$id);
		if($_GET['type'] == 'form'){
			$this->Smarty->assign('usid',$id);
			$this->Smarty->assign('type','form');
			$this->Smarty->assign('text',$result['userinfo']);
		}elseif($_GET['type'] == 'thanks'){
			$this->Smarty->assign('usid',$id);
			$this->Smarty->assign('type','thanks');
			$this->Smarty->assign('text',$result['thanks']);
		}elseif($_GET['type'] == 'success'){
			$this->Smarty->assign('usid',$id);
			$this->Smarty->assign('type','success');
			$this->Smarty->assign('text',$result['success']);
		}elseif($_GET['type'] == 'validation'){
			$this->Smarty->assign('usid',$id);
			$this->Smarty->assign('type','validation');
			$this->Smarty->assign('text',$result['validation']);
		}else{
			$this->Smarty->assign('usid',$id);
			$this->Smarty->assign('type','welcome');
			$this->Smarty->assign('text',$result['welcome']);
		}
		$this->Smarty->assign('li_menu','subscribe');
		$this->Smarty->display('edittemplate.php');
	}
	
	//执行编辑页面的html动作
	public function doedittemplateAction(){
		$usid = $_POST['usid'];
		$type = $_POST['type'];
		$str = $_POST['factcontent'];

		if($type == 'form'){
			$count = $this->dbAdapter->update('mr_usersubscription',array('userinfo'=>$str),'id='.$usid);
		}elseif($type == 'thanks'){
			$count = $this->dbAdapter->update('mr_usersubscription',array('thanks'=>$str),'id='.$usid);
		}elseif($type == 'success'){
			$count = $this->dbAdapter->update('mr_usersubscription',array('success'=>$str),'id='.$usid);
		}elseif($type == 'validation'){
			$count = $this->dbAdapter->update('mr_usersubscription',array('validation'=>$str),'id='.$usid);
		}else{
			$count = $this->dbAdapter->update('mr_usersubscription',array('welcome'=>$str),'id='.$usid);
		}
		$uname = $this->getCurrentUser();
		$formname = $this->dbAdapter->fetchOne("select formname from mr_usersubscription where id=".$usid);
		$description='该用户进行编辑html页面的操作，订阅名称为：'.$formname;
		$description_en='The user to edit the HTML page operation,the subscription name is: '.$formname;
		self::taskOperationLog($description,$description_en);
		if($count>0){
			$this->_redirect('contact/formlist');
		}
	}
	
	//编辑订阅表单
	public function editformAction(){
        $uid = $_SESSION['Zend_Auth']['storage']->id ? $_SESSION['Zend_Auth']['storage']->id : 0;
        $role = $_SESSION['Zend_Auth']['storage']->role;
        $result = $this->dbAdapter->fetchRow("select * from mr_usersubscription where id=".$_GET['id']);
        $arr = explode(",",$result['formfield']);
        $drr = explode(",",$result['rule']);
        if($arr){
            foreach($arr as $key=>$val){
                if($val){
                    $brr[$key] = $this->dbAdapter->fetchRow("select * from mr_group_extension where id=".$val);
                }
            }
        }
        $crr = explode(",",$result['groups']);
        $err = explode(",",$result['necessary']);
        $frr = explode(",",$result['number']);
        $this->Smarty->assign('frr',$frr);
        $this->Smarty->assign('err',$err);
        $this->Smarty->assign('drr',$drr);
        $this->Smarty->assign('arr',$arr);
        $this->Smarty->assign('crr',$crr);

        $this->Smarty->assign('brr',$brr);
        $this->Smarty->assign('result',$result);
        if($role == "sadmin"){
            $condition = " where 1=1 ";
        }else if($role == "admin"){
            $arr_id = $this->dbAdapter->fetchAll("select id from mr_accounts where role='admin' or role='stasker' or role='tasker'");
            $string = "";
            if(!empty($arr_id)){
                foreach($arr_id as $key=>$val){
                    $string .= $val['id'].",";
                }
            }
            $string = rtrim($string,",");

            $condition = " where uid in(".$string.") ";
        }else if($role == "stasker"){
            $arr_id = $this->dbAdapter->fetchAll("select id from mr_accounts where role='stasker' or role='tasker'");
            $string = "";
            if(!empty($arr_id)){
                foreach($arr_id as $key=>$val){
                    $string .= $val['id'].",";
                }
            }
            $string = rtrim($string,",");

            $condition = " where uid in(".$string.") ";
        }else{

            $condition = " where uid=".$uid;
        }
		$groups = $this->dbAdapter->fetchAll("select * from mr_group where uid=".$uid);
		$basic = $this->dbAdapter->fetchAll("select id,showname,name from mr_group_extension where hidden=1");
		$extension = $this->dbAdapter->fetchAll("select id,showname,name from mr_group_extension where hidden=0");
		$this->Smarty->assign('basic',$basic);
		$this->Smarty->assign('extension',$extension);
		$this->Smarty->assign('groups',$groups);
		$this->Smarty->assign('li_menu','subscribe');

		$this->Smarty->display("editform.php");
	}
	
	//执行编辑订阅表单
	public function doeditformAction(){
		$id = $_POST['usid'];
		$arr['uid']	= $_SESSION['Zend_Auth']['storage']->id;
		$arr['formname'] = $_POST['fname'];
		$arr['description'] = $_POST['description'];
		$arr['formfield'] = $_POST['field'];
		$arr['groups'] = implode(",",$_POST['gname']);
		$arr['buttoname'] = $_POST['subname'];
		$arr['tag'] = 1;
		$arr['rule'] = $_POST['rule'];
		$arr['number'] = $_POST['number'];
		$arr['necessary'] = $_POST['necessary'];
		//订阅表单
		$url = $this->dbAdapter->fetchRow("select dport,https,domainname,serviceport from mr_console");
		$userinfo .= "<script>function changeimg(){var target=document.getElementById('iimg');if(target!=null){target.setAttribute('src','http://".$url['domainname'].":".$url['dport']."/index/captcha?'+Math.random());}}</script>";
		$userinfo .= "<form action='http://".$url['domainname'].":".$url['serviceport']."/adduser.php' method='get'>";
		$userinfo .= "<table align='center' cellpadding='0' cellspacing='0' border='1' width='50%'>";
		$userinfo .= "<thead><tr height='35'><td colspan='2' style='text-align:center'><b>".$arr['formname']."</b></td></tr></thead>";
		$userinfo .= "<tbody><tr height='35'><td style='text-align:right;width:40%'>邮箱：</td><td><input type='text' name='mailbox' value=''>&nbsp;<span style='color:red'>*</span></td></div></tr>";
		
        if($_POST['field']){
			$field = explode(",",$_POST['field']);
			$necessary = explode(",",$_POST['necessary']);
			$rule = explode(",",$_POST['rule']);
			foreach($field as $key=>$val){
				if($val){
					$brr = $this->dbAdapter->fetchRow("select showname,name from mr_group_extension where id=".$val);
					if($necessary[$key] == 1){
						$userinfo .= "<tr height='35'><td style='text-align:right;width:40%'>".$brr['showname']."：</td><td><input type='text' rule=".$rule[$key]." name='".$brr['name']."' value=''>&nbsp;<span style='color:red'>*</span></td></tr>";
					}else{
						$userinfo .= "<tr height='35'><td style='text-align:right;width:40%'>".$brr['showname']."：</td><td><input type='text' rule=".$rule[$key]." name='".$brr['name']."' value=''></td></tr>";
					}
				}
			}
		}
		$userinfo .= "<input type='hidden' name='usid' id='usid' value='".$id."' />";
		$userinfo .= "<input type='hidden' name='uid' id='uid' value='".$arr['uid']."' />";
		$userinfo .= "<tr height='35'><td style='text-align:right;width:40%'>验证码：</td><td><span style='margin-top: 0px;float:left'><input type='text' id='captcha' name='captcha' value='' /></span><span style='display:block;float:left'><img id='iimg' name='iimg' title='点击更换图片' onclick='changeimg();' src='http://".$url['domainname'].":".$url['dport']."/index/captcha'></span></td></tr>";
		$userinfo .= "<tr height='35'><td colspan='2' style='text-align:center'><input type='submit' value='".$_POST['subname']."' /></td></tr>";
		$userinfo .= "</tbody>";
		$userinfo .= "</table>";
		$userinfo .= "</form>";
		$arr['userinfo'] = $userinfo;
		
		$count = $this->dbAdapter->update("mr_usersubscription",$arr,'id='.$id);
		$uname = $this->getCurrentUser();
		$description='该用户进行编辑订阅表单的操作，订阅名称为：'.$arr['formname'];
		$description_en='The user edit subscription form operation,the subscription name is: '.$arr['formname'];
		self::taskOperationLog($description,$description_en);
		if($_POST['status'] == 'preview'){
			echo $userinfo;
		}else{
			$this->_redirect('contact/formlist');
		}
	}
	
	//判断表单名称是否重复
	public function ajaxformAction(){
		$uid = $_SESSION['Zend_Auth']['storage']->id ? $_SESSION['Zend_Auth']['storage']->id : 0;
		if($_POST['status'] == 'editform'){
			$fname = $_POST['fname'];
			$usid = $_POST['usid'];
			$result = $this->dbAdapter->fetchAll("select * from mr_usersubscription where uid=".$uid." and formname='".$fname."' and id<>".$usid);
			if(!empty($result)){
				$uname = $this->getCurrentUser();
				$description='该用户进行判断表单名称是否重复的操作';
				$description_en='The user to determine whether repeated operation form name';
				self::taskOperationLog($description,$description_en);
			}
			if(!empty($result)){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			$fname = $_POST['fname'];
			$result = $this->dbAdapter->fetchAll("select * from mr_usersubscription where uid=".$uid." and formname='".$fname."'");
			if(!empty($result)){
				$uname = $this->getCurrentUser();
				$description='该用户进行判断表单名称是否重复的操作';
				$description_en='The user to determine whether repeated operation form name';
				self::taskOperationLog($description,$description_en);
			}
			if(!empty($result)){
				echo 1;
			}else{
				echo 0;
			}
		}
	}
	
	//退订功能
	public function unsubscribeAction(){
		$groups = $_GET['groups'];
		$mailbox = $_GET['mailbox'];
		$arr = explode(",",$groups);
		if($arr){
			foreach($arr as $val){
				$tablename = $this->dbAdapter->fetchOne("select tablename from mr_group where id=".$val);
				$this->dbAdapter->delete($tablename,"mailbox='".$mailbox."'");
			}
		}
		$uname = $this->getCurrentUser();
		$description='该用户进行删除订阅组中用户的操作';
		$description_en='The user to delete users to subscribe to the group operation';
		self::taskOperationLog($description,$description_en);
		echo "您已经退订成功，感谢您的订阅！";
	}
	
	//筛选器功能
	public function filterAction(){
		$num = $_GET['num']?$_GET['num']:10;
		$uid = $_SESSION['Zend_Auth']['storage']->id ? $_SESSION['Zend_Auth']['storage']->id : 0;
		$role = $_SESSION['Zend_Auth']['storage']->role;
		$condition = self::checkLoginAudit($role,$uid);
		$res = $this->dbAdapter->fetchAll("select * from mr_filter ".$condition);
		$total = count($res);
		$page = new Page($total,$num);
		$result = $this->dbAdapter->fetchAll("select * from mr_filter ".$condition." order by id desc {$page->limit}");
		for($i = 0;$i <count($result); $i++){
			$usernams = $this->dbAdapter->fetchAll("select username from mr_accounts where id = ".$result[$i]['uid']);
			$result[$i]['uid'] = $usernams[0]['username'];
		}
		$this->Smarty->assign('num',$num);
		$this->Smarty->assign('data',$result);
		$this->Smarty->assign('page',$page->fpage());
		$this->Smarty->assign('li_menu','filter');
		$this->Smarty->display("filter.php");
	}
	
	//添加筛选器页面,编辑筛选器页面
	public function addfilterAction(){
		if($_GET['id']){
			$result = $this->dbAdapter->fetchRow("select * from mr_filter where id=".$_GET['id']);
			$this->Smarty->assign('result',$result);
			$arr = array("0"=>"是","1"=>"否","2"=>"包含","3"=>"不包含","4"=>"开始字符","5"=>"结束字符","100"=>"等于","101"=>"不等于","102"=>"大于",
						 "103"=>"不大于","104"=>"小于","105"=>"不小于","200"=>"之前","201"=>"之后","202"=>"介于","203"=>"不介于","300"=>"为空","301"=>"不为空");
			$arr2 =	array_flip($arr);
			$data = $this->dbAdapter->fetchAll("select * from mr_condition where fid=".$_GET['id']." order by id asc");
			if($data){
				foreach($data as $key=>$val){
					$data[$key]['fieldname'] = $this->dbAdapter->fetchOne("select showname from mr_group_extension where id=".$val['field']);
					$data[$key]['type'] = $this->dbAdapter->fetchOne("select type from mr_group_extension where id=".$val['field']);
					$data[$key]['num'] = $key+1;
					if(in_array($val['operator'],$arr2)){
						$data[$key]['opratorname'] = $arr[$val['operator']];
					}

					if($val['join'] == 0){
						$data[$key]['joiname'] = "";
					}elseif($val['join'] == 1){
						$data[$key]['joiname'] = "或(or)";
					}else{
						$data[$key]['joiname'] = "与(and)";
					}
					$ext = explode("至",$val['value']);
					$data[$key]['value1'] = $ext[0];
					$data[$key]['value2'] = $ext[1];
				}
			}

			$this->Smarty->assign('data',$data);
			$this->Smarty->assign('id',$_GET['id']);
		}else{
			
		}
		$arr = $this->dbAdapter->fetchAll("select * from mr_group_extension");
		$this->Smarty->assign('arr',$arr);

		$this->Smarty->assign('li_menu','filter');
		$this->Smarty->display("addfilter.php");
	}
	
	//执行添加筛选器
	public function doaddfilterAction(){
		$oprator = array(0=>"是",1=>"否",2=>"包含",3=>"不包含",4=>"开始字符",5=>"结束字符",100=>"等于",101=>"不等于",102=>"大于",
					 103=>"不大于",104=>"小于",105=>"不小于",200=>"之前",201=>"之后",202=>"介于",203=>"不介于",300=>"为空",301=>"不为空");

		$uid = $_SESSION['Zend_Auth']['storage']->id;
		$filtername = $_POST['filtername'];
		$description = $_POST['description'];

		$numstr = explode(",",$_POST['numstr']);
		array_pop($numstr);
		if(isset($_POST['number2']) && !empty($_POST['number2'])){
			array_push($numstr,$_POST['number2']);
		}
		$jostr = explode(",",$_POST['jostr']);
		array_pop($jostr);
		$sname = explode(",",$_POST['sname']);
		array_pop($sname);
		$ti = explode(",",$_POST['ti']);
		array_pop($ti);
		$opra = explode(",",$_POST['opra']);
		array_pop($opra);

		$arr['name'] = $filtername;
		$arr['description'] = $description;
		$arr['uid'] = $uid;
		$arr['public'] = $_POST['public'] ? $_POST['public']:0;
		$string = implode($sname);
		if($string != ""){
			$arr['condition'] = $this->getsql($oprator,$jostr,$sname,$ti,$opra);
		}

		if($_POST['id'] != ''){
			$this->dbAdapter->update('mr_filter',$arr,'id='.$_POST['id']);
			$this->dbAdapter->delete('mr_condition','fid='.$_POST['id']);

			foreach($numstr as $key=>$val){
				if($_POST['id']){
					$brr['fid'] = $_POST['id'];
					$brr['field'] = $sname[$key];
					$brr['operator'] = $opra[$key];
					$brr['value'] = $ti[$key];
					$brr['join'] = $jostr[$key];
					$this->dbAdapter->insert('mr_condition',$brr);	
				}		
			}
			$uname = $this->getCurrentUser();
			$description='该用户进行编辑筛选器的操作，筛选器：'.$filtername;
			$description_en='The user edit filter, the filter is: '.$filtername;
			self::taskOperationLog($description,$description_en);
		}else{
			$this->dbAdapter->insert('mr_filter',$arr);
			$id = $this->dbAdapter->lastInsertId();

			foreach($numstr as $key=>$val){
				if($id){
					$brr['fid'] = $id;
					$brr['field'] = $sname[$key];
					$brr['operator'] = $opra[$key];
					$brr['value'] = $ti[$key];
					$brr['join'] = $jostr[$key];
					$this->dbAdapter->insert('mr_condition',$brr);	
				}		
			}
			$uname = $this->getCurrentUser();
			$description='该用户进行添加筛选器的操作,筛选器：'.$filtername;
			$description_en='The user add operation of filter，the filter is: '.$filtername;
			self::taskOperationLog($description,$description_en);
		}
		$this->_redirect('/contact/filter');
	}
	
	//筛选器组装sql语句的方法
	private function getsql($arr,$jostr,$sname,$ti,$opra){
		for($i=0;$i<count($sname);$i++){
			$name = $this->dbAdapter->fetchOne("select name from mr_group_extension where id=".$sname[$i]);
			switch($opra[$i]){
				case 0:
					if($ti[$i] == "男" ){
						$ti[$i] = 1;
					}elseif($ti[$i] == "女"){
						$ti[$i] = 2;
					}
					if($i==0){
						$sql .= " ".$name." = '".$ti[$i]."' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." = '".$ti[$i]."' ";
						}elseif($jostr[$i] == 1){
							$sql .= " or ".$name." = '".$ti[$i]."' ";
						}
					}
					break;
				case 1:
					if($i==0){
						$sql .= " ".$name." <> '".$ti[$i]."' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." <> '".$ti[$i]."' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." <> '".$ti[$i]."' ";
						}
					}
					break;
				case 2:
					if($i==0){
						$sql .= " ".$name." like '%".$ti[$i]."%' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." like '%".$ti[$i]."%' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." like '%".$ti[$i]."%' ";
						}
					}
					break;
				case 3:
					if($i==0){
						$sql .= " ".$name." not like '%".$ti[$i]."%' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." not like '%".$ti[$i]."%' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." not like '%".$ti[$i]."%' ";
						}
					}
					break;
				case 4:
					if($i==0){
						$sql .= " ".$name." like '".$ti[$i]."%' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." like '".$ti[$i]."%' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." like '".$ti[$i]."%' ";
						}
					}
					break;
				case 5:
					if($i==0){
						$sql .= " ".$name." like '%".$ti[$i]."' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." like '%".$ti[$i]."' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." like '%".$ti[$i]."' ";
						}
					}
					break;
				case 100:
					if($i==0){
						$sql .= " ".$name." = '".$ti[$i]."' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." = '".$ti[$i]."' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." = '".$ti[$i]."' ";
						}
					}
					break;
				case 101:
					if($i==0){
						$sql .= " ".$name." <> '".$ti[$i]."' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." <> '".$ti[$i]."' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." <> '".$ti[$i]."' ";
						}
					}
					break;
				case 102:
					if($i==0){
						$sql .= " ".$name." > '".$ti[$i]."' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." > '".$ti[$i]."' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." > '".$ti[$i]."' ";
						}
					}
					break;
				case 103:
					if($i==0){
						$sql .= " ".$name." <= '".$ti[$i]."' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." <= '".$ti[$i]."' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." <= '".$ti[$i]."' ";
						}
					}
					break;
				case 104:
					if($i==0){
						$sql .= " ".$name." < '".$ti[$i]."' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." < '".$ti[$i]."' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." < '".$ti[$i]."' ";
						}
					}
					break;
				case 105:
					if($i==0){
						$sql .= " ".$name." >= '".$ti[$i]."' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." >= '".$ti[$i]."' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." >= '".$ti[$i]."' ";
						}
					}
					break;
				case 200:
					$time = strtotime($ti[$i]);
					if($i==0){
						$sql .= " ".$name." < '".$time."' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." < '".$time."' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." < '".$time."' ";
						}
					}
					break;
				case 201:
					$time = strtotime($ti[$i]);
					if($i==0){
						$sql .= " ".$name." > '".$time."' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." > '".$time."' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." > '".$time."' ";
						}
					}
					break;
				case 202:
					$time = explode("至",$ti[$i]);
					$time1 = strtotime($time[0]);
					$time2 = strtotime($time[1]);
					if($i==0){
						$sql .= " ".$name." > '".$time1."' and ".$name." < '".$time2."' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and (".$name." > '".$time1."' and ".$name." < '".$time2."') ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or (".$name." > '".$time1."' and ".$name." < '".$time2."') ";
						}
					}
					break;
				case 203:
					$time = explode("至",$ti[$i]);
					$time1 = strtotime($time[0]);
					$time2 = strtotime($time[1]);
					if($i==0){
						$sql .= " ".$name." < '".$time1."' or ".$name." > '".$time2."' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and (".$name." < '".$time1."' or ".$name." > '".$time2."') ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or (".$name." < '".$time1."' or ".$name." > '".$time2."') ";
						}
					}
					break;
				case 300:
					$ti[$i] = "";
					if($i==0){
						$sql .= " ".$name." = '' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." = '' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." = '' ";
						}
					}
					break;
				case 301:
					if($i==0){
						$sql .= " ".$name." <> '' ";
					}else{
						if($jostr[$i] == 2){
							$sql .=  " and ".$name." <> '' ";
						}elseif($jostr[$i] == 1){
							$sql .=  " or ".$name." <> '' ";
						}
					}
					break;
			}
		}
		return $sql;
	}
	
	public function delfilterAction(){
		$id = $_POST['id'];
		$name = $this->dbAdapter->fetchOne("select name from mr_filter where id=".$id);
		$count = $this->dbAdapter->delete('mr_filter','id='.$id);
		$this->dbAdapter->delete('mr_condition','fid='.$id);
		$description='该用户进行删除筛选器的操作,筛选器为：'.$name;
		$description_en='The user delete operation of filter, the filter is: '.$name;
		self::taskOperationLog($description,$description_en);
	}
	
	//判断筛选器名称是否存在
	public function ajaxfilternameAction(){
		$filtername = $_POST['fname'];
		$fid = $_POST['fid'];
		if($fid){
			$count = $this->dbAdapter->fetchOne("select count(*) from mr_filter where id<>".$fid." and name='".$filtername."'");
			if($count){
				$uname = $this->getCurrentUser();
				$description='该用户进行判断筛选器名称是否存在的操作';
				$description_en='The user is estimated filter name exists operation';
				self::taskOperationLog($description,$description_en);
			}
			if($count>0){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			$count = $this->dbAdapter->fetchOne("select count(*) from mr_filter where name='".$filtername."'");
			if($count){
				$uname = $this->getCurrentUser();
				$description='该用户进行判断筛选器名称是否存在的操作';
				$description_en='The user is estimated filter name exists operation';
				self::taskOperationLog($description,$description_en);
			}
			if($count>0){
				echo 1;
			}else{
				echo 0;
			}
		}
	}
	
	/*
	 * 批量删除筛选器
	 */
	public function ajaxdelallfilterAction(){
		$ids = $_POST['ids'];
		$ids = trim($ids);
		$names = "";
		$ext = explode(",",$ids);
		if($ext){
			foreach($ext as $key=>$val){
				if($val){
					$name = $this->dbAdapter->fetchOne("select name from mr_filter where id=".$val);
					$names .= $name .",";
					$this->dbAdapter->query("delete from mr_filter where id=".$val);
					$this->dbAdapter->query("delete from mr_condition where fid=".$val);
				}
			}
		}
		$names = trim($names, ",");
		$description='该用户进行批量删除筛选器的操作,筛选器为：'.$names;
		$description_en='The user to delete filter operation, the filter is: '.$names;
		self::taskOperationLog($description,$description_en);
		echo (count($ext)-1);
	}
	
	//ajax获取自定义字段表中数据
	public function getexpansionAction(){
		$result = $this->dbAdapter->fetchAll("select * from mr_group_extension");
		echo json_encode($result);
	}
	
	//统计每日发送邮件数量与每日授权可以发送邮件量百分比
	public function ajaxmailsendperAction(){
		$infos = SystemConsole::GetLicenseInfo();
		$time1 = date("Y-m-d 00:00:00");
		$time2 = date("Y-m-d 23:59:59");
		$success_send = $this->dbAdapter->fetchOne("select count(id) from mr_smtp_task where runtime>='".$time1."' and runtime<='".$time2."' and status=2");
		$daysend = 0;
		if (!empty($success_send)) {
			$daysend = $success_send;
		}
		$persend = round($daysend*100/$infos['capacity'],2);
		$crr['persend'] = $persend;
		$crr['capacity'] = $infos['capacity'];
		$crr['daysend'] = $daysend;
		echo json_encode($crr);
	}
	
	public function taskOperationLog($description,$description_en){
		$userid = $this->getCurrentUserID();
		$uname = $this->getcurrentuser();
		$role = $this->getCurrentUserRole();
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '联系人管理操作', $description, 'Download operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		return true;
	}

	//selectalled
	public function  selectalledAction(){
			$status = $this->_request->getPost('selectalls');	
			if($status == 'on'){
				$_SESSION['sel_stat'] = 'on';	
			}else{
				unset($_SESSION['sel_stat']);	
			}
			echo $_SESSION['sel_stat'];
	}
}





