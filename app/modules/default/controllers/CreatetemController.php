<?php
require("CommonController.php");
require('page.class.php');
class CreatetemController extends CommonController
{
	public $smtptask;
	function init()
	{
		header('Content-type:text/html;charset=utf-8');
		parent::init();
		$this->smtptask = new SmtpTask();
	}

	public function addtplsAction()
	{
		$getRel = $this->_request->get('rel');
		if ($getRel == "nection") {
			$this->Smarty->assign('rel', $getRel);
		}
		$get_rela_id = $this->_request->get('rela');
		if ($get_rela_id) {
			$this->template = new Template();
			$rows = $this->template->getOneTpl($get_rela_id);
			if ($rows) {
				$this->Smarty->assign('tplname', $rows->tpl_name);
				$this->Smarty->assign('tplbody', stripslashes($rows->tpl_body));
				$this->Smarty->assign('tplid', $rows->id);
			}
		}

		$this->Smarty->assign("li_menu", "preset");
		$this->Smarty->display('addtpls.php');
	}

	public function doaddtplsAction()
	{
		set_time_limit(10);
		if ($this->_request->isPost()) {
			$name = $this->_request->getPost('tplname');
			$content = $this->_request->getPost('factcontent');
			$contents = addslashes($content);
			$rel = $this->_request->getPost('rel');
			$realid = $this->_request->getPost('realid');
			$this->templet = new Template();

			$userid = $this->getCurrentUserID();
			$role = $this->getCurrentUserRole();
			$uname = $this->getCurrentUser();

			$path = "/var/www/maildelivery/dist/thumb_images/";  //Linux

			$newname = $this->htmlimage($content);
			if ($realid) {
				//有传值说明是更新数据
				if ($rel == "nection") {
					$style = "-1";
				} else {
					$style = "-1";
				}
				$resoults = $this->templet->getOneTpl($realid);
				$tpl_img = $resoults->tpl_img;
				unlink($path . $tpl_img);
				$rows = $this->templet->updateTpl($realid, $contents, $name, $style, $newname);
				if ($rows || $rows == 0) {
					$description = "该用户进行更新模板操作,模板名称为：" . $name;
					$description_en = "The user template update operation,the template name is: " . $name;
					BehaviorTrack::addBehaviorLog($uname, $role, $userid, ' 预设模板操作', $description, 'Default template operation', $description_en, $_SERVER["REMOTE_ADDR"]);

					if ($rel == 'nection') {
						$this->_redirect('/templet/preset');
					} else {
						$this->_redirect('/templet/preset');
					}
				}
			} else {

				if ($rel == "nection") {
					$arrs = array('tpl_name' => $name, 'tpl_body' => $contents, 'tpl_style' => "-1", "tpl_img" => $newname);
				} else {
					$arrs = array('tpl_name' => $name, 'tpl_body' => $contents, 'tpl_style' => "-1", "tpl_img" => $newname);
				}
				$insert_id = $this->templet->insertOne($arrs);
				if ($insert_id) {
					$description = "该用户进行添加预设模板操作，模板名称为：" . $arrs['tpl_name'];
					$description_en = "The user to add the default template operation,the template name is: " . $arrs['tpl_name'];
					BehaviorTrack::addBehaviorLog($uname, $role, $userid, ' 预设模板操作', $description, 'Default template operation', $description_en, $_SERVER["REMOTE_ADDR"]);
					if ($rel == 'nection') {
						$this->_redirect('/templet/preset');
					} else {
						$this->_redirect('/templet/preset');
					}
				}
			}
		}
	}

	public function htmlimage($html_content)
	{
		$html_content = stripslashes($html_content);
		$path = "/var/www/maildelivery/dist/thumb_images/";  //Linux
		$file = @fopen($path . "thumb.html", "w+b");
		$nowfile = fwrite($file, $html_content);
		@fclose($file);
		$url = $path . 'thumb.html';
		//输出图片的位置与名称
		$newname = mt_rand(1, 9999) . time() . ".png";
		$out = $path . $newname;
		$cmd = "sudo /usr/bin/xvfb-run --server-args=\"-screen 0, 800x600x24\" /opt/bin/CutyCapt --url=file:" . $url . " --out=" . $out . " --max-wait=8000";
		//Linux
		@exec($cmd);
		$linuxs = 'sudo convert -resize 13%x18% ' . $out . " " . $out;
		@exec($linuxs);
		return $newname;
	}

	public function gettotalmailAction()
	{
		$stats = $this->smtptask->getPast24HourStats();
		$curhour = $this->smtptask->getCurrentHourStats();
		$array1 = array(); //os 1
		$i = 1;
		$n = 0;
		while ($i <= 24) {
			$mkhour  = mktime(date("H") - 24 + $i, 0, 0, date("m"), date("d"), date("Y"));
			$hour = date('H', $mkhour);
			$i++;
			$n++;
			$array1[$n]['smtp'] = 0;
			$array1[$n]['task'] = 0;
			// $array1[$n]['success'] = 0;
			// $array1[$n]['failure'] = 0;
			// $array1[$n]['softfailure'] = 0;
			// $array1[$n]['total'] = 0;
			$array1[$n]['hour'] = $hour;
		}
		// 目前，实际用到的只有total值，其他预留的，没实际用途
		foreach ($stats['smtp'] as $hourstat) {
			if (!empty($hourstat)) {
				$hour = date('H', strtotime($hourstat['runtime']));
				$k = $this->ReturnJsonKey($array1, $hour);
				$array1[$k]['smtp'] = $array1[$k]['smtp'] + $hourstat['total'];
				// $array1[$k]['total'] = $array1[$k]['total'] + $hourstat['total'];
				// $array1[$k]['success'] = $array1[$k]['success'] + $hourstat['success'];
				// $array1[$k]['failure'] = $array1[$k]['failure'] + $hourstat['failure'];
				// $array1[$k]['softfailure'] = $array1[$k]['softfailure'] + $hourstat['softfailure'];
				$array1[$k]['hour'] = $hour;
			}
		}
		foreach ($stats['task'] as $hourstat) {
			if (!empty($hourstat)) {
				$hour = date('H', strtotime($hourstat['runtime']));
				$k = $this->ReturnJsonKey($array1, $hour);
				$array1[$k]['task'] = $array1[$k]['task'] + $hourstat['total'];
				// $array1[$k]['total'] = $array1[$k]['total'] + $hourstat['total'];
				// $array1[$k]['success'] = $array1[$k]['success'] + $hourstat['success'];
				// $array1[$k]['failure'] = $array1[$k]['failure'] + $hourstat['failure'];
				// $array1[$k]['softfailure'] = $array1[$k]['softfailure'] + $hourstat['softfailure'];
				$array1[$k]['hour'] = $hour;
			}
		}
		$nh = date('H', time());
		$nk = $this->ReturnJsonKey($array1, $nh);
		$array1[$nk]['smtp'] =  intval($curhour['smtp']['total']);
		$array1[$nk]['task'] = intval($curhour['task']['total']);
		$array1[$nk]['hour'] = $nh;
		echo json_encode($array1);
	}

	public function ReturnJsonKey($arr, $hour)
	{
		foreach ($arr as $k => $v) {
			if ($v['hour'] == $hour) {
				return $k;
			}
		}
	}

	public function firstpageAction()
	{
		$role = $this->getCurrentUserRole();
		$uid = $_SESSION['Zend_Auth']['storage']->id;
		$userdata = $this->dbAdapter->fetchAll("select id,operator,ip,accesstime from `mr_accesslog` where userid = " . $uid . " order by accesstime desc limit 1");

		$strtime = strtotime($userdata[0]['accesstime']);
		$logintime = date('Y-m-d H:i', $strtime);
		//登录用户信息统计
		if ($role == "admin" || $role == "sadmin" || $role == "stasker") {
			$taskdata_all = $this->dbAdapter->fetchAll("select count(id) as num,min(runtime) as runtime from mr_smtp_task where status='2'");
			$runtime = strtotime($taskdata_all[0]['runtime']);
		} else {
			$taskdata = $this->dbAdapter->fetchAll("select sum(success) as tnum,min(runtime) as runtime from `mr_task_log` where uid = '" . $uid . "'");
			$runtime = strtotime($taskdata[0]['runtime']);
		}
		$tnum = $taskdata[0]['tnum'] + $taskdata_all[0]['num'];
		$days = (time() - $runtime) / 3600 / 24;
		if ($days <= 1) {
			$time2 = explode('-', date('y-m-d', time()));
			$runtime2 = explode('-', date('y-m-d', $runtime));
			if ($time2[2] == $runtime2[2]) {
				$days = ceil($days);
			} else {
				$days = ceil($days) + 1;
			}
		} else {
			$days = ceil($days);
		}
		$avg = ceil($tnum / $days);
		//联系人统计
		$group_num = 0;
		if ($role == "admin" || $role == "sadmin" || $role == "stasker") {
			$group_num = $this->dbAdapter->fetchOne("select count(id) as num from `mr_group`");
		} else {
			$group_num = $this->dbAdapter->fetchOne("select count(id) as num from `mr_group` where uid = " . $uid . "");
		}
		$person_num = 0;
		$person_num = $this->dbAdapter->fetchOne("select  count(id) as num from `mr_subscriber`");

		//任务数量
		if ($role == "admin" || $role == "sadmin" || $role == "stasker") {
			$tasknum = $this->dbAdapter->fetchOne("select count(id) as num from mr_task where draft<>1");
		} else {
			//$subscribe = $this->dbAdapter->fetchAll("select count(id) as subscribe_num from `mr_subscriber` where subscribe <> '' and uid = '".$uid."'");
			$tasknum = $this->dbAdapter->fetchOne("select count(id) as num from mr_task where draft<>1 and uid = '" . $uid . "'");
		}

		//最新任务执行情况
		if ($role == "admin" || $role == "sadmin" || $role == "stasker") {
			$taskstats = $this->dbAdapter->fetchAll("select id,tid,runtime,total,success,failure from `mr_task_log` order by runtime desc limit 4");
		} else {
			$taskstats = $this->dbAdapter->fetchAll("select id,tid,runtime,total,success,failure from `mr_task_log` where uid = '" . $uid . "' order by runtime desc limit 4");
		}
		foreach ($taskstats as &$val) {
			$tasknames = $this->dbAdapter->fetchAll("select task_name from `mr_task` where id = " . $val['tid'] . "");
			$val['task_name'] = $tasknames[0]['task_name'];
			$val['progess'] = round(($val['success'] + $val['failure']) / $val['total'] * 100) . "%";
			$val['completion_rate'] = round($val['success'] / $val['total'] * 100) . '%';
		}
		//var_dump($taskstats);exit;
		//模版数量
		if ($role == "admin" || $role == "sadmin" || $role == "stasker") {
			$tplnum = $this->dbAdapter->fetchOne("select count(id) as num from mr_template where uid <> 0");
		} else {
			$tplnum = $this->dbAdapter->fetchOne("select count(id) as num from mr_template where uid = " . $uid . "");
		}

		//账户发送总情况
		if ($role == "admin" || $role == "sadmin" || $role == "stasker") {
			$tasksituation = $this->dbAdapter->fetchAll("select id,status from `mr_task`");
		} else {
			$tasksituation = $this->dbAdapter->fetchAll("select id,status from `mr_task` where uid = " . $uid . "");
		}

		$this->Smarty->assign('total_max', $total_max);
		$this->Smarty->assign("avg", $avg);
		$this->Smarty->assign("tnum", $tnum);
		$this->Smarty->assign("uname", $userdata[0]['operator']);
		$this->Smarty->assign("ip", $userdata[0]['ip']);
		$this->Smarty->assign("logintime", $logintime);
		$this->Smarty->assign("group_num", $group_num);
		$this->Smarty->assign("person_num", $person_num);
		$this->Smarty->assign("tasknum", $tasknum);
		$this->Smarty->assign("task1", $taskstats[0]);
		$this->Smarty->assign("task2", $taskstats[1]);
		$this->Smarty->assign("task3", $taskstats[2]);
		$this->Smarty->assign("task4", $taskstats[3]);
		//$this->Smarty->assign ("arr", $arrtask); 
		// $this->Smarty->assign ("success", $success); 
		$this->Smarty->assign("tplnum", $tplnum);
		// $this->Smarty->assign ("failure", $failure); 
		$this->Smarty->assign("li_menu", "charts");
		$this->Smarty->display('charts.php');
	}

	function calc($size, $digits = 2)
	{
		$unit = array('', 'K', 'M', 'G', 'T', 'P');
		$base = 1024;
		$i   = floor(log($size, $base));
		$n   = count($unit);
		if ($i >= $n) {
			$i = $n - 1;
		}
		return round($size / pow($base, $i), $digits) . ' ' . $unit[$i] . 'B';
	}

	public function uploadattchsAction()
	{
		if ($this->_request->isPost()) {
			$upload = new Zend_File_Transfer_Adapter_Http();
			$upload_dir = $this->dbAdapter->fetchone("SELECT spath FROM `mr_storage` WHERE sname = 'attach' ORDER BY id DESC");
			$upload->setDestination($upload_dir);
			$upload->addValidator('Count', false, array('min' => 1, 'max' => 5));
			$upload->addValidator('FilesSize', false, array('min' => '0KB', 'max' => '20480KB'));
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
				$uid = $_SESSION['Zend_Auth']['storage']->id;
				$uname = $this->getCurrentUser();
				// echo $uname;
				$date = date('Y-m-d H:i:s', time());
				$str = json_encode($arr);
				//insert sqlserver
				$insert_sql = 'insert into mr_attachment (uid,path,truename,aliasname,createtime) values("' . $uid . '","' . $path . '","' . $arr['filename'] . '","' . $path . '","' . $date . '")';
				$last_insert_id = $this->dbAdapter->query($insert_sql);
				if ($last_insert_id) {
					echo $str;
				}
				$description = '该用户管理附件时上传附件,用户名为' . $uname;
				$description_en = 'The user upload attachments, The user is' . $uname;
				$mess = '上传附件操作';
				$userid = $this->getCurrentUserID();
				$role = $this->getCurrentUserRole();
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, $mess, $description, 'Download operation', $description_en, $_SERVER["REMOTE_ADDR"]);
			}
		}
	}

	/*附件管理*/
	public function mgattachAction()
	{
		$uid = $_SESSION['Zend_Auth']['storage']->id;
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$Jurisdiction = $this->getCurrentUserRole();
		if ($Jurisdiction == "admin" || $Jurisdiction == "sadmin") {
			$admins_sql = 'SELECT id FROM mr_accounts WHERE role IN ("stasker","tasker")';
			$admins_resoult = $this->dbAdapter->fetchAll($admins_sql);
			$admins_array = array();
			if ($admins_resoult) {
				foreach ($admins_resoult as $v1) {
					$admins_array[] = $v1['id'];
				}
			}
			array_push($admins_array, $uid);
			$final_id = join(',', $admins_array);
		} elseif ($Jurisdiction == "stasker") {
			$stasker_sql = "SELECT id FROM mr_accounts WHERE parentid = " . $uid;
			$stasker_resoult = $this->dbAdapter->fetchAll($stasker_sql);
			$stasker_array = array();
			if ($stasker_resoult) {
				foreach ($stasker_resoult as $v2) {
					$stasker_array[] = $v2['id'];
				}
			}
			array_push($stasker_array, $uid);
			$final_id = join(',', $stasker_array);
		}
		//downfile
		$down_id = $this->_request->get('doid');
		// echo $down_id;
		if ($down_id) {
			$down_sql = "SELECT path,truename FROM mr_attachment WHERE id=" . $down_id;
			$down_resoult = $this->dbAdapter->fetchAll($down_sql);
			if ($down_resoult) {

				$zipfilepath = $down_resoult[0]['path'];
				// $zipname = substr($zipfilepath,strrpos($zipfilepath,'/'));
				$zipname = $down_resoult[0]['truename'];
				if (is_file($zipfilepath) && file_exists($zipfilepath)) {
					@ob_end_clean();
					// file size in bytes
					$fsize = filesize($zipfilepath);
					header("Content-type: application/force-download");
					Header("Pragma: public");
					Header("Expires: 0"); // set expiration time
					Header("Cache-Component: must-revalidate, post-check=0, pre-check=0");
					Header("Content-Length: " . $fsize);
					Header("Content-Disposition: attachment; filename=\"$zipname\"");
					Header('Content-Transfer-Encoding: binary');
					$tempfile = @fopen($zipfilepath, "rb");
					if ($tempfile) {
						$description = "该用户进行下载操作,下载附件:" . $down_resoult[0]['truename'];
						$description_en = "The user to download, download attachment :" . $down_resoult[0]['truename'];
						BehaviorTrack::addBehaviorLog($uname, $role, $userid, ' 下载操作', $description, 'Download operation', $description_en, $_SERVER["REMOTE_ADDR"]);

						while (!feof($tempfile)) {
							print(fread($tempfile, 20480));
							flush();
							if (connection_status() != 0) {
								@fclose($tempfile);
							}
						}
						@fclose($tempfile);
					}
				}
			}
		}
		$num = $this->_request->get('num');
		if (empty($num)) {
			$num = 10;
		}
		$parameter = "";
		$parameter = "&num=" . $num;
		$this->Smarty->assign('setnum', $num);

		$names = $this->_request->get('attachname');
		$names = str_replace(array('*', '(', "'", '\\', '|', '?', '+', '['), array('', '', '', '', '', '', '', ''), $names);
		$post_taskname = $this->_request->get('taskname');
		$post_taskname = str_replace(array('*', '(', "'", '\\', '|', '?', '+', '['), array('', '', '', '', '', '', '', ''), $post_taskname);
		$slide = 'down';
		if ($names || $post_taskname) {
			$this->Smarty->assign('slide', $slide);
		}
		$this->Smarty->assign('attachname', $names);
		$this->Smarty->assign('taskname', $post_taskname);
		if ($Jurisdiction == "sadmin") {
			if ($names != "" && $post_taskname != '') {
				$sql_where = "WHERE truename REGEXP '" . $names . "' AND task_name REGEXP'" . $post_taskname . "' ";
			} elseif ($names != "") {
				$sql_where = "WHERE  truename REGEXP '" . $names . "'";
				$parameter .= "&truename=" . $names;
			} elseif ($post_taskname != "") {
				$sql_where = "WHERE task_name REGEXP '" . $post_taskname . "' ";
				$parameter .= "&task_name=" . $post_taskname;
			}
		} else {
			if ($names != "" && $post_taskname != '') {
				$sql_where = "AND truename REGEXP '" . $names . "' AND task_name REGEXP'" . $post_taskname . "' ";
			} elseif ($names != "") {
				$sql_where = "AND truename REGEXP '" . $names . "'";
				$parameter .= "&truename=" . $names;
			} elseif ($post_taskname != "") {
				$sql_where = "AND task_name REGEXP '" . $post_taskname . "' ";
				$parameter .= "&task_name=" . $post_taskname;
			}
		}
		if ($Jurisdiction == "sadmin") {
			$sql = "SELECT mr_attachment.id AS id,mr_attachment.uid AS username,tid,task_name,truename,mr_attachment.createtime,path FROM  mr_attachment LEFT JOIN mr_task ON mr_attachment.tid=mr_task.id  " . $sql_where;
		} elseif ($Jurisdiction == 'admin' || $Jurisdiction == 'stasker') {
			$sql = "SELECT mr_attachment.id AS id,mr_attachment.uid AS username,tid,task_name,truename,mr_attachment.createtime,path FROM  mr_attachment LEFT JOIN mr_task ON mr_attachment.tid=mr_task.id  WHERE  mr_attachment.uid in ({$final_id}) " . $sql_where;
		} else {
			$sql = "SELECT mr_attachment.id AS id,mr_attachment.uid AS username,tid,task_name,truename,mr_attachment.createtime,path FROM  mr_attachment LEFT JOIN mr_task ON mr_attachment.tid=mr_task.id  WHERE  mr_attachment.uid={$uid} " . $sql_where;
		}

		$resoult = $this->dbAdapter->fetchAll($sql);
		if ($resoult && ($names || $post_taskname)) {
			$style = $this->_request->get('style');
			if (empty($style)) {
				$description = '该用户进行查询附件的操作';
				$description_en = 'The user performs the operation of querying attachment';
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询操作', $description, 'Query operation', $description_en, $_SERVER["REMOTE_ADDR"]);
			}
		}
		$total = count($resoult);
		$page = new page($total, $num, $parameter);
		$limit_sql = " order by mr_attachment.createtime DESC {$page->limit};";
		$sql = $sql . $limit_sql;
		$fet_row = $this->dbAdapter->fetchAll($sql);
		$sql_other = 'SELECT username,role,id FROM mr_accounts'; // WHERE id='.$uid
		$resoult_other = $this->dbAdapter->fetchAll($sql_other);
		$id_uname_arr = array();
		foreach ($resoult_other as $item) {
			$id_uname_arr[$item['id']] = $item['username'];
		}
		foreach ($fet_row as &$vals) {
			$vals['username'] = $id_uname_arr[$vals['username']];
			$file = filesize($vals['path']);
			$vals['filesize'] = $this->calc($file);
		}

		$this->Smarty->assign('rows', $fet_row);
		$this->Smarty->assign('page', $page->fpage());
		$this->Smarty->assign("li_menu", "mgattach");
		$this->Smarty->display('attachments.php');
	}


	public function delattachAction()
	{
		if ($this->_request->isPost()) {
			$del_id = $this->_request->getPost('id');
			$del_all = $this->_request->getPost('del_str_id');
			$userid = $this->getCurrentUserID();
			$role = $this->getCurrentUserRole();
			$uname = $this->getCurrentUser();

			if ($del_id) {
				$this->delattach = new Attachment();
				$info = $this->delattach->selOne($del_id);
				$aliasname = $info->aliasname;
				$truename = $this->dbAdapter->fetchOne("SELECT truename FROM mr_attachment WHERE id=" . $del_id);
				$sql = "SELECT COUNT(aliasname) FROM mr_attachment WHERE aliasname IS NOT NULL AND aliasname='" . $aliasname . "'";
				$resoult = $this->dbAdapter->fetchOne($sql);
				if ($resoult == 1) {
					if (unlink($info->path)) {
						$resoults = $this->delattach->delOne($del_id);
						if ($resoults) {
							$description = "该用户进行删除附件操作，附件名为: " . $truename;
							$description_en = "The user delete attachment, the attachment name is: " . $truename;
							BehaviorTrack::addBehaviorLog($uname, $role, $userid, ' 删除操作', $description, 'Delete operation', $description_en, $_SERVER["REMOTE_ADDR"]);
							echo 1;
						}
					}
				} else {
					$resoults = $this->delattach->delOne($del_id);
					if ($resoults) {
						echo 1;
					}
				}
			}
			if ($del_all) {
				$del_all = trim($del_all, ',');
				$aliasnames = "";
				$sql = "SELECT truename FROM mr_attachment WHERE id in(" . $del_all . ")";
				$resoult = $this->dbAdapter->fetchAll($sql);
				foreach ($resoult as $val) {
					$aliasnames .= $val['truename'] . ",";
				}
				$aliasnames = trim($aliasnames, ",");
				$description = "该用户进行批量删除附件操作，附件名为" . $aliasnames;
				$description_en = "The user batch delete attachment, the attachment name is " . $aliasnames;
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, ' 删除操作', $description, 'Delete operation', $description_en, $_SERVER["REMOTE_ADDR"]);
				$arr = explode(',', $del_all);
				foreach ($arr as $vals) {
					$this->delattach = new Attachment();
					$info = $this->delattach->selOne($vals);
					$aliasname = $info->aliasname;
					$sql = "SELECT COUNT(aliasname) FROM mr_attachment WHERE aliasname IS NOT NULL AND aliasname='" . $aliasname . "'";
					$resoult = $this->dbAdapter->fetchOne($sql);
					if ($resoult == 1) {
						unlink($info->path);
						$resoults = $this->delattach->delOne($vals);
						if ($resoults) {
							echo 1;
						}
					} else {
						$resoults = $this->delattach->delOne($vals);
						if ($resoults) {
							echo 1;
						}
					}
				}
			}
		}
	}

	public function delimagesAction()
	{
		if ($this->_request->isPost()) {
			$del_id = $this->_request->getPost('id');
			$del_all = $this->_request->getPost('del_str_id');
			$userid = $this->getCurrentUserID();
			$role = $this->getCurrentUserRole();
			$uname = $this->getCurrentUser();
			$path = '/home/maildelivery/images/';
			if ($del_id) {
				$sql = "SELECT aliasname FROM mr_images WHERE id='" . $del_id . "'";
				$aliasname = $this->dbAdapter->fetchOne($sql);
				if (unlink($path . $aliasname)) {
					$sql = "DELETE FROM mr_images WHERE id=" . $del_id;
					$resoults = $this->dbAdapter->query($sql);
					if ($resoults) {
						$description = "该用户进行删除图片操作，图片名为：" . $aliasname;
						$description_en = "The user to delete pictures operation, the image name is: " . $aliasname;
						BehaviorTrack::addBehaviorLog($uname, $role, $userid, ' 删除操作', $description, 'Delete operation', $description_en, $_SERVER["REMOTE_ADDR"]);
						echo 1;
					}
				}
			}
			if ($del_all) {
				$aliasnames = "";
				$del_all = trim($del_all, ',');
				$sql = "SELECT aliasname FROM mr_images WHERE id in(" . $del_all . ")";
				$resoult = $this->dbAdapter->fetchAll($sql);
				if ($resoult) {
					$sql = "DELETE FROM mr_images WHERE id in(" . $del_all . ")";
					$resoults = $this->dbAdapter->query($sql);
					if ($resoults) {
						foreach ($resoult as $val) {
							$aliasnames .= $val['aliasname'] . ",";
							unlink($path . $val['aliasname']);
						}
						$aliasnames = trim($aliasnames, ",");
						$description = "该用户进行批量删除图片操作，图片名为: " . $aliasnames;
						$description_en = "The user to batch delete pictures operation, the image name is: " . $aliasnames;
						BehaviorTrack::addBehaviorLog($uname, $role, $userid, ' 删除操作', $description, 'Delete operation', $description_en, $_SERVER["REMOTE_ADDR"]);
						echo 1;
					}
				}
			}
		}
	}
	
	public function mdimagesAction()
	{
		$uid = $_SESSION['Zend_Auth']['storage']->id;
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();

		$Jurisdiction = $this->getCurrentUserRole();
		if ($Jurisdiction == "admin" || $Jurisdiction == "sadmin") {
			$admins_sql = 'SELECT id FROM mr_accounts WHERE role IN ("stasker","tasker")';
			$admins_resoult = $this->dbAdapter->fetchAll($admins_sql);
			$admins_array = array();
			if ($admins_resoult) {
				foreach ($admins_resoult as $v1) {
					$admins_array[] = $v1['id'];
				}
			}
			array_push($admins_array, $uid);
			$final_id = join(',', $admins_array);
		} elseif ($Jurisdiction == "stasker") {
			$stasker_sql = "SELECT id FROM mr_accounts WHERE parentid = " . $uid;
			$stasker_resoult = $this->dbAdapter->fetchAll($stasker_sql);
			$stasker_array = array();
			if ($stasker_resoult) {
				foreach ($stasker_resoult as $v2) {
					$stasker_array[] = $v2['id'];
				}
			}
			array_push($stasker_array, $uid);
			$final_id = join(',', $stasker_array);
		}

		//downfile
		$down_id = $this->_request->get('doid');
		// echo $down_id;
		if ($down_id) {
			$down_sql = "SELECT aliasname,truename FROM mr_images WHERE id=" . $down_id;
			$down_resoult = $this->dbAdapter->fetchAll($down_sql);
			if ($down_resoult) {

				$zipfilepath = "/home/maildelivery/images/" . $down_resoult[0]['aliasname'];
				// $zipname = substr($zipfilepath,strrpos($zipfilepath,'/'));
				$zipname = $down_resoult[0]['truename'];
				if (is_file($zipfilepath) && file_exists($zipfilepath)) {
					@ob_end_clean();
					// file size in bytes
					$fsize = filesize($zipfilepath);
					header("Content-type: application/force-download");
					Header("Pragma: public");
					Header("Expires: 0"); // set expiration time
					Header("Cache-Component: must-revalidate, post-check=0, pre-check=0");
					Header("Content-Length: " . $fsize);
					Header("Content-Disposition: attachment; filename=\"$zipname\"");
					Header('Content-Transfer-Encoding: binary');
					$tempfile = @fopen($zipfilepath, "rb");
					if ($tempfile) {
						$description = "该用户进行下载操作,下载图片:" . $down_resoult[0]['truename'];
						$description_en = "The user to download, download images :" . $down_resoult[0]['truename'];
						BehaviorTrack::addBehaviorLog($uname, $role, $userid, ' 下载操作', $description, 'Download operation', $description_en, $_SERVER["REMOTE_ADDR"]);

						while (!feof($tempfile)) {
							print(fread($tempfile, 20480));
							flush();
							if (connection_status() != 0) {
								@fclose($tempfile);
							}
						}
						@fclose($tempfile);
					}
				}
			}
		}
		$num = $this->_request->get('num');
		if (empty($num)) {
			$num = 10;
		}
		$parameter = "";
		$parameter = "&num=" . $num;

		$names = $this->_request->get('attachname');
		$names = str_replace(array('*', '(', "'", '\\', '|', '?', '+', '['), array('', '', '', '', '', '', '', ''), $names);
		$names = trim(mysql_escape_string($names));
		$slide = 'down';
		if ($names) {
			$this->Smarty->assign('slide', $slide);
			$this->Smarty->assign('attachname', stripslashes($names));
		}
		if ($Jurisdiction == "sadmin") {
			if ($names != "") {
				$sql_where = "WHERE truename REGEXP '" . $names . "'";
				$parameter .= "&truename=" . $names;
			}
		} else {
			if ($names != "") {
				$sql_where = "AND truename REGEXP '" . $names . "'";
				$parameter .= "&truename=" . $names;
			}
		}

		if ($Jurisdiction == "sadmin") {
			$sql = "SELECT  * FROM mr_images  " . $sql_where;
		} elseif ($Jurisdiction == 'admin' || $Jurisdiction == 'stasker') {
			$sql = "SELECT * FROM mr_images  WHERE  uid  in ({$final_id}) " . $sql_where;
		} else {
			$sql = " SELECT * from mr_images WHERE  uid={$uid} " . $sql_where;
		}

		$resoult = $this->dbAdapter->fetchAll($sql);
		if ($resoult && $names) {
			$style = $this->_request->get('style');
			if (empty($style)) {
				$description = '该用户进行查询图片的操作';
				$description_en = 'The user performs the operation of querying image';
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '查询操作', $description, 'Query operation', $description_en, $_SERVER["REMOTE_ADDR"]);
			}
		}
		$total = count($resoult);
		$page = new page($total, $num, $parameter);

		$limit_sql = " order by createtime DESC {$page->limit};";
		$sql = $sql . $limit_sql;
		$fet_row = $this->dbAdapter->fetchAll($sql);
		$true_path = '/home/maildelivery/images/';

		foreach ($fet_row as &$vals) {
			$vals['filesize'] = $this->calc($vals['filesize']);
		}


		$this->Smarty->assign('rows', $fet_row);
		$this->Smarty->assign('page', $page->fpage());
		$this->Smarty->assign('setnum', $num);
		$this->Smarty->assign("li_menu", "mdimages");
		$this->Smarty->display('images.php');
	}
}
