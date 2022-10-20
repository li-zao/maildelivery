<?php
require("CommonController.php");
require('page.class.php');
require('simple_html_dom.php');

class TempletController extends CommonController
{
	function init()
	{
		header('Content-type:text/html;charset=utf-8');
		parent::init();
	}

	function  indexAction()
	{

		$num = 10; //default

		$sql = "select id,tpl_name,tpl_img from mr_template where tpl_style=-1 ";

		$fet_rows = $this->dbAdapter->fetchAll($sql);
		$total = count($fet_rows);
		$page = new page($total, $num, '');

		$limit_sql = "order by id {$page->limit}";
		$sql = $sql . $limit_sql;
		$fet_row = $this->dbAdapter->fetchAll($sql);


		/*download*/
		$down_id = $this->_request->get('doid');
		if ($down_id) {
			$this->template = new Template();
			$rows = $this->template->getOneTpl($down_id);
			if ($rows) {
				$p = $this->Smarty->template_dir;
				$path = $p[0] . "/downloads.html";
				$file = fopen($path, "w+");
				if (fwrite($file, stripslashes($rows->tpl_body))) {

					if (is_file($path) && file_exists($path)) {
						@ob_end_clean();
						// file size in bytes
						$fsize = filesize($path);
						$zipname = $rows->tpl_name . ".html";
						Header("Pragma: public");
						Header("Expires: 0"); // set expiration time
						Header("Cache-Component: must-revalidate, post-check=0, pre-check=0");
						Header("Content-Length: " . $fsize);
						Header("Content-Disposition: attachment; filename=\"$zipname\"");
						Header('Content-Transfer-Encoding: binary');

						$userid = $this->getCurrentUserID();
						$role = $this->getCurrentUserRole();
						$uname = $this->getCurrentUser();
						$description = "该用户进行下载模板操作,模板名为:" . $zipname;
						$description_en = "The user to download the template, the template named: " . $zipname;
						BehaviorTrack::addBehaviorLog($uname, $role, $userid, '下载操作', $description, 'Download operation', $description_en, $_SERVER["REMOTE_ADDR"]);

						$tempfile = @fopen($path, "rb");
						if ($tempfile) {
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
		}


		$this->Smarty->assign("page", $page->fpage());
		$this->Smarty->assign("tpls", $fet_row);
		$this->Smarty->assign("li_menu", "index");
		$this->Smarty->display('tpl.php');
	}

	function presetAction()
	{

		$num = 10;
		$sql = "select id,tpl_name,tpl_img from mr_template where tpl_style=-1 ";

		$fet_rows = $this->dbAdapter->fetchAll($sql);
		$total = count($fet_rows);
		$page = new page($total, $num, '');

		$limit_sql = "order by id desc {$page->limit}";
		$sql = $sql . $limit_sql;
		$fet_row = $this->dbAdapter->fetchAll($sql);
		$down_id = $this->_request->get('doid');
		if ($down_id) {
			$this->template = new Template();
			$rows = $this->template->getOneTpl($down_id);
			if ($rows) {
				$p = $this->Smarty->template_dir;
				$path = $p[0] . "/downloads.html";
				$file = fopen($path, "w+");
				if (fwrite($file, stripslashes($rows->tpl_body))) {
					if (is_file($path) && file_exists($path)) {
						@ob_end_clean();
						// file size in bytes
						$fsize = filesize($path);
						$zipname = $rows->tpl_name . ".html";
						Header("Pragma: public");
						Header("Expires: 0"); // set expiration time
						Header("Cache-Component: must-revalidate, post-check=0, pre-check=0");
						Header("Content-Length: " . $fsize);
						Header("Content-Disposition: attachment; filename=\"$zipname\"");
						Header('Content-Transfer-Encoding: binary');

						$userid = $this->getCurrentUserID();
						$uname = $this->getCurrentUser();
						$role = $this->getCurrentUserRole();
						$description = "该用户进行下载模板操作,模板名为: " . $zipname;
						$description_en = "The user to download the template, the template named: " . $zipname;
						BehaviorTrack::addBehaviorLog($uname, $role, $userid, '下载操作', $description, 'Download operation', $description_en, $_SERVER["REMOTE_ADDR"]);

						$tempfile = @fopen($path, "rb");
						if ($tempfile) {
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
		}

		$this->Smarty->assign("page", $page->fpage());
		$this->Smarty->assign("preset", $fet_row);
		$this->Smarty->assign("li_menu", "preset");
		$this->Smarty->display('preset.php');
	}

	function mytemplAction()
	{
		$user_id = $_SESSION['Zend_Auth']['storage']->id;
		$Jurisdiction = $this->getCurrentUserRole();
		if ($Jurisdiction == "stasker") {
			$stasker_sql = "SELECT id FROM mr_accounts WHERE parentid = " . $user_id;
			$stasker_resoult = $this->dbAdapter->fetchAll($stasker_sql);
			$stasker_array = array();
			if ($stasker_resoult) {
				foreach ($stasker_resoult as $v2) {
					$stasker_array[] = $v2['id'];
				}
			}
			array_push($stasker_array, $user_id);
			$final_id = join(',', $stasker_array);
		}

		$num = 10; //default
		if ($Jurisdiction == 'stasker') {
			$sql = "SELECT id,uid,tpl_name,tpl_img FROM mr_template WHERE uid in ($final_id)";
		} else {
			$sql = "SELECT id,uid,tpl_name,tpl_img FROM mr_template WHERE uid=" . $user_id;
		}

		$fet_rows = $this->dbAdapter->fetchAll($sql);

		$total = count($fet_rows);
		$page = new page($total, $num, '');

		$limit_sql = " order by id desc {$page->limit}";
		$sql = $sql . $limit_sql;
		$fet_row = $this->dbAdapter->fetchAll($sql);

		$down_id = $this->_request->get('doid');
		if ($down_id) {
			$this->template = new Template();
			$rows = $this->template->getOneTpl($down_id);
			if ($rows) {
				$p = $this->Smarty->template_dir;
				$path = $p[0] . "/downloads.html";
				$file = fopen($path, "w+");
				if (fwrite($file, stripslashes($rows->tpl_body))) {

					if (is_file($path) && file_exists($path)) {
						@ob_end_clean();
						// file size in bytes
						$fsize = filesize($path);
						$zipname = $rows->tpl_name . ".html";
						Header("Pragma: public");
						Header("Expires: 0"); // set expiration time
						Header("Cache-Component: must-revalidate, post-check=0, pre-check=0");
						Header("Content-Length: " . $fsize);
						Header("Content-Disposition: attachment; filename=\"$zipname\"");
						Header('Content-Transfer-Encoding: binary');

						$userid = $this->getCurrentUserID();
						$uname = $this->getCurrentUser();
						$role = $this->getCurrentUserRole();
						$description = "该用户进行下载模板操作,模板名为: " . $zipname;
						$description_en = "The user to download the template, the template named: " . $zipname;
						BehaviorTrack::addBehaviorLog($uname, $role, $userid, '下载操作', $description, 'Download operation', $description_en, $_SERVER["REMOTE_ADDR"]);

						$tempfile = @fopen($path, "rb");
						if ($tempfile) {
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
		}


		//获取行业分类
		$this->voc = new Vocation();
		if ($Jurisdiction == 'stasker') {
			$vals = $this->voc->getAllTpls($final_id);
		} else {
			$vals = $this->voc->getAllTpls($user_id);
		}
		foreach ($fet_row as $k => $v) {
			if ($fet_row[$k]['uid'] == $user_id) {
				$fet_row[$k]['mine'] = 1;
			} else {
				$fet_row[$k]['mine'] = 0;
			}
		}
		$this->Smarty->assign("page", $page->fpage());
		$this->Smarty->assign("li_menu", "mytempl");
		$this->Smarty->assign("tpls", $fet_row);
		$this->Smarty->assign("vals", $vals);
		$this->Smarty->display('mytempl.php');
	}

	function createtemplAction()
	{
		$host = $_SERVER['SERVER_ADDR'];
		// $username=$_SESSION['mailcenter_console']['admin_name'];
		// $sql='select id from mr_accounts where username="'.$username.'"';
		$user_id = $this->getCurrentUserID();

		$Jurisdiction = $this->getCurrentUserRole();
		/* if($Jurisdiction == "stasker"){
				$stasker_sql = "SELECT id FROM mr_accounts WHERE parentid = ".$user_id;
				$stasker_resoult = $this->dbAdapter->fetchAll($stasker_sql);
				$stasker_array=array();	
				if($stasker_resoult){
					foreach($stasker_resoult as $v2){
						$stasker_array[]=$v2['id'];
					}
				}
				
				array_push($stasker_array,$user_id);
				$final_id =join(',',$stasker_array);
				$sql2="select id,vocation_name from mr_vocation where uid in ({$final_id})";

			}else{
				$sql2="select id,vocation_name from mr_vocation where uid=".$user_id;
			} */
		$sql2 = "select id,vocation_name from mr_vocation where uid=" . $user_id;

		$fetch_resoult = $this->dbAdapter->fetchAll($sql2);
		//选择页面传值模板id
		$getid = $this->_request->get('tid');
		// echo $getid;
		$this->tpl = new Template();
		$row = $this->tpl->getOneTpl($getid);
		$vid = $row['tpl_style'];
		$this->voc = new Vocation();
		$rows = $this->voc->getvoOne($vid);
		$row['tpl_body'] = stripslashes($row['tpl_body']);
		// }

		$this->Smarty->assign("li_menu", "createtempl");
		$this->Smarty->assign("host", $host);
		$this->Smarty->assign("row", $row);
		$this->Smarty->assign("rows", $rows);
		$this->Smarty->assign("vocation", $fetch_resoult);
		$this->Smarty->display('createtempl.php');
	}

	function docreatesAction()
	{
		set_time_limit(10);
		header('Content-type:text/html;charset=utf-8');
		if ($this->_request->isPost()) {
			$per_content = $this->_request->getPost('factcontent');
			$per_names = $this->_request->getPost('tplname');
			$per_style = $this->_request->getPost('tpltype');
		}
		$this->template = new Template();
		$this->t_vocation = new Templatevocation();
		$userid = $this->getCurrentUserID();
		$uname = $this->getCurrentUser();
		$role = $this->getCurrentUserRole();

		/*生成缩略*/
		$thumb_image = $this->htmlimage($per_content);
		$path = "/var/www/maildelivery/dist/thumb_images/";  //Linux
		$array = scandir($path);
		if (!in_array($thumb_image, $array)) {
			$thumb_image = "121.jpg";
		}

		/*更新*/
		if ($this->_request->getPost('tid')) {
			$per_tid = $this->_request->getPost('tid');
			$per_contents = addslashes($per_content);
			$per_uid = $this->_request->getPost('uid');
			if ($per_uid == 0) {
				$description = "该用户进行添加预设模板操作,模板名为:" . $per_names;
				$description_en = "The user to add the template, the template name is: " . $per_names;
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '添加预设模板操作', $description, 'Add operation', $description_en, $_SERVER["REMOTE_ADDR"]);

				$per_uid = $_SESSION['Zend_Auth']['storage']->id;
				$arrs = array('uid' => $per_uid, 'tpl_body' => $per_contents, 'tpl_name' => $per_names, "tpl_style" => $per_style, 'tpl_img' => $thumb_image);
				$last_id = $this->template->insertOne($arrs);
				if ($last_id) {
					$arrs2 = array('tid' => $last_id, "vid" => $per_style);
					$last_two = $this->t_vocation->sendInsertOne($arrs2);
					if ($last_two) {
						$this->_redirect('/templet/mytempl');
					}
				}
			} else {
				$description = "该用户进行更新模板操作,模板名为: " . $per_names;
				$description_en = "The user to update the template, the template name is: " . $per_names;
				BehaviorTrack::addBehaviorLog($uname, $role, $userid, '更新模板操作', $description, 'Update operation', $description_en, $_SERVER["REMOTE_ADDR"]);

				/*获取原数据的缩略图*/
				$numbers = $this->template->getOneTpl($per_tid);
				exec("sudo chmod +x /var/www/maildelivery/dist/thumb_images/" . $numbers->tpl_img);
				exec("sudo rm -rf /var/www/maildelivery/dist/thumb_images/" . $numbers->tpl_img);
				$row = $this->template->updateTpl($per_tid, $per_contents, $per_names, $per_style, $thumb_image);
				if ($row == 1 || $row == 0) {
					//更新与行业的关系
					$tid = $per_tid;
					$vid = $per_style;
					$resoult = $this->t_vocation->updateTV($tid, $vid);
					if ($resoult == 1 || $resoult == 0) {
						$this->_redirect('/templet/mytempl');
					}
				}
			}
		} else {
			$description = "该用户进行添加我的模板操作,模板名为: " . $per_names;
			$description_en = "The user to add the template, the template name is: " . $per_names;
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '添加我的模板操作', $description, 'Add operation', $description_en, $_SERVER["REMOTE_ADDR"]);
			/*插入数据*/
			$username = $_SESSION['mailcenter_console']['admin_name'];
			$sql = 'select id from mr_accounts where username="' . $username . '"';
			$user_id = $this->dbAdapter->fetchOne($sql);
			//  /*生成缩略*/
			$per_content = addslashes($per_content);
			// exit;
			$arrs = array(
				'uid' => $user_id,
				"tpl_name" => $per_names,
				"tpl_body" => $per_content,
				"tpl_img" => $thumb_image,
				"tpl_style" => $per_style
			);
			$last_insert_id = $this->template->insertOne($arrs);
			$resoult_two = array(
				"tid" => $last_insert_id,
				"vid" => $per_style
			);
			$last_insert_double = $this->t_vocation->sendInsertOne($resoult_two);
			if ($last_insert_double) {
				$this->_redirect('/templet/mytempl');
			}
		}
	}

	public function  htmlimage($html_content)
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

	function addvocationAction()
	{
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();

		if ($this->_request->isPost()) {
			$tem_name = $this->_request->getPost('inputname');
			$tem_content = $this->_request->getPost('inputcontent');
			$tem_user_id = $_SESSION['Zend_Auth']['storage']->id;
			$this->vocation = new Vocation();
			$resoult = $this->vocation->getOneselect($tem_name);

			$description = "该用户进行添加行业操作,行业名为" . $tem_name;
			$description_en = "The user to add the vocation, the vocation named " . $tem_name;
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '添加行业', $description, 'Add vocation', $description_en, $_SERVER["REMOTE_ADDR"]);

			if ($resoult) {
				echo 0;
			} else {
				$rows = array(
					"vocation_name" => $tem_name,
					"vocation_body" => $tem_content,
					"uid" => $tem_user_id
				);
				if ($insert_id = $this->vocation->addInsert($rows)) {
					echo $insert_id;
				}
			}
		} else {
			$tem_name = $this->_request->get('names');
			$tem_content = $this->_request->get('content');
			$tem_user_id = $_SESSION['Zend_Auth']['storage']->id;
			$description = "该用户进行添加任务分类操作,任务分类名为" . $tem_name;
			$description_en = "The user to add the task classification, the task classification named " . $tem_name;
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '添加操作', $description, 'Add operation', $description_en, $_SERVER["REMOTE_ADDR"]);
			//if($tem_name && $tem_user_id){
			if ($tem_name) {
				$this->vocation = new Vocation();
				//$resoult=$this->vocation->getOneselect($tem_name,$tem_user_id);
				$resoult = $this->vocation->getOneselect($tem_name);
				if ($resoult) {
					echo "error";
					// exit;
				} else {
					$rows = array(
						"vocation_name" => $tem_name,
						"vocation_body" => $tem_content,
						"uid" => $tem_user_id
					);
					if ($insert_id = $this->vocation->addInsert($rows)) {
						echo $insert_id;
					}
				}
			}
		}
	}

	/*获取编辑模板*/
	public function editvocationAction()
	{
		$get_id = $this->_request->get('id');
		if ($get_id) {
			$this->vocation = new Vocation();
			$resoult = $this->vocation->getvoOne($get_id);
			$name = $resoult->vocation_name;
			$content = $resoult->vocation_body;
			$id = $resoult->id;
			$this->Smarty->assign('name', $name);
			$this->Smarty->assign('body', $content);
			$this->Smarty->assign('void', $id);
			$this->Smarty->assign("li_menu", "mytempl");
			$this->Smarty->display('editvo.php');
		}
	}

	public function doupdatevoAction()
	{
		header('Content-type:text/html;charset=utf-8');
		$names = $this->_request->getPost('names');
		$contents = $this->_request->getPost('contents');
		$id = $this->_request->getPost('void');
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$description = "该用户进行编辑任务分类操作,任务分类名为" . $names;
		$description_en = "The user to edit the task classification, the task classification named " . $names;
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '编辑操作', $description, 'Edit operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		if ($names != "") {
			$arr = array('vocation_name' => $names, 'vocation_body' => $contents, 'id' => $id);
			$this->vocation = new Vocation();
			if ($this->_request->getPost('tag') == 'task') {
				//$resoult=$this->vocation->getOneselect($names,$userid,$id);fetchAll()
				//$sql="select id,vocation_name,vocation_body from mr_vocation where vocation_name = '".$names."' and uid = ".$userid." and id <> ".$id."";
				$sql = "select id,vocation_name,vocation_body from mr_vocation where vocation_name = '" . $names . "' and id <> " . $id . "";
				//echo $sql;exit;
				$resoult = $this->dbAdapter->fetchOne($sql);
				if ($resoult) {
					echo 'error';
					exit;
				} else {
					$rows = $this->vocation->updateOne($names, $contents, $id);
					echo 'success';
					exit;
				}
			} else {
				$rows = $this->vocation->updateOne($names, $contents, $id);
				if ($rows == 1 || $rows == 0) {
					$this->_redirect('/templet/mytempl');
				}
			}
		}
	}

	public function  checkvoAction()
	{
		$postInputname = $this->_request->getPost('inputname');
		$postVocaid = $this->_request->getPost('vocaid');
		echo $postInputname . $postVocaid;
	}

	public function deletevoAction()
	{
		$user_id = $_SESSION['Zend_Auth']['storage']->id;
		$this->voc = new Vocation();
		$id = $this->_request->get('void');
		$rows = $this->voc->deleteOne($id, $user_id);
		$names = $this->voc->getNameById($id);
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$description = "该用户进行删除任务分类操作,任务分类名为" . $names;
		$description_en = "The user to delete the task classification operations, the task classification named " . $names;
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '删除操作', $description, 'Delete operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		if ($rows) {
			$this->_redirect('templet/mytempl');
		}
	}

	public function searchvoAction()
	{
		$ids = $this->_request->getPost('ids');
		// echo $ids;
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$description = "该用户进行行业搜索操作";
		$description_en = "The user to select the vocation";
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '行业搜索操作', $description, 'Delete operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		if ($ids != "") {
			$uid = $_SESSION['Zend_Auth']['storage']->id;
			$ids = trim($ids, ",");
			$this->tpl = new Template();

			$Jurisdiction = $this->getCurrentUserRole();
			if ($Jurisdiction == "stasker") {
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
				$rows = $this->tpl->searchAll($ids, $final_id);
			} else {
				$rows = $this->tpl->searchAll($ids, $uid);
			}

			if ($this->_request->getPost('currentPage')) {
				$page = $this->_request->getPost('currentPage');
			} else {
				$page = 1;
			}
			$totalNum = count($rows);
			$pageNum = 10;
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
			if ($Jurisdiction == 'stasker') {
				$sql = "select id,uid,tpl_name,tpl_img from mr_template where uid in(" . $final_id . ") and tpl_style in({$ids}) order by id desc limit {$offset},{$pageNum} ";
			} else {
				$sql = "select id,uid,tpl_name,tpl_img from mr_template where uid=" . $uid . " and tpl_style in({$ids}) order by id desc limit {$offset},{$pageNum} ";
			}
			$row = $this->dbAdapter->fetchAll($sql);
			$str = '<div class="row-fluid"  style="width:980px;margin-left:90px;">';
			foreach ($row as $k => $val) {
				$str .= "<div class='choseone' id='" . $val['id'] . "'><dl class='inline' onmouseover='changeOver(this)' onmouseout='changeOut(this)'>";
				$str .= '<dt class="text-center">' . $val['tpl_name'] . '</dt>';
				$str .= '<dd style="margin-right: 15px;" class="actions">
					<sapn href="/templet/createtempl/tid/' . $val['id'] . '">';
				$str .= '<img src="/dist/thumb_images/' . $val['tpl_img'] . '" style="width: 104px;height: 133px;" alt="" class="img-rounded">';
				$str .= '</span></dd></dl><div class="chose">
					<a href="/templet/tplview/relid/' . $val['id'] . '" target="_blank" title="预览" style="margin-left:10px"><i class="icon-eye-open icon-large"></i></a>';
				if ($row[$k]['uid'] == $uid) {
					$str .= '<a href="/templet/createtempl/tid/' . $val['id'] . '" style="margin-left:5px"  title="编辑"><i class="icon-file icon-large"></i></a>';
				};
				$str .= '<a href="/templet/mytempl/doid/' . $val['id'] . '" style="margin-left:5px" title="下载"><i class="icon-download-alt icon-large"></i></a>
					<a href="javascript:void(0)" onclick="deltpls(this)"  class="deltpls" value="{%$tpls[loop].id%}" title="删除"><i class="icon-remove icon-large"></i></a>
					</div></div>';
			}
			$str .= "</div>";
			$str .= '
					<div class="row-fluid">
						<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
								<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;">
							共有 &nbsp;<b>' . $totalNum . '</b>&nbsp;条记录 &nbsp;<b>' . $page . '</b>/<b>' . $total_page . '</b>&nbsp;
							<a onclick="goPages(1)" id="DataTables_Table_0_first" class="first ui-corner-tl ui-corner-bl fg-button ui-button ui-state-default" tabindex="0">
					            首页
					        </a>
					        <a onclick="goPages(' . $prePage . ')" id="DataTables_Table_0_previous" class="previous fg-button ui-button ui-state-default" tabindex="0">
					            上一页
					        </a>';
			$listNum = 8;
			$inum = floor($listNum / 2);
			for ($i = $inum - 1; $i >= 1; $i--) {
				$pages = $page - $i;
				if ($pages <= 0) {
					continue;
				}
				$str .= "&nbsp;<a onclick='goPages({$pages})' class='ui-corner-tr ui-corner-br fg-button ui-button ui-state-default rush' page='{$page}'>{$pages}</a>&nbsp;";
			}
			$str .= "&nbsp;<a><span style='width:20px;background-color:#26B779' class='ui-corner-tr ui-corner-br fg-button ui-button ui-state-default rush'>" . ($pages + 1) . "</span></a>&nbsp;";
			for ($i = 1; $i < $inum; $i++) {
				$pages = $page + $i;
				if ($pages <= $total_page) {
					$str .= "&nbsp;<a  onclick='goPages({$pages})' class='ui-corner-tr ui-corner-br fg-button ui-button ui-state-default rush' page='{$pages}'>{$pages}</a>&nbsp;";
				} else {
					break;
				}
			}
			$str .= '
					        <a onclick="goPages(' . $nextPage . ')" id="DataTables_Table_0_next" class="next fg-button ui-button ui-state-default" tabindex="0">
					            下一页
					        </a>
					        <a onclick="goPages(' . $total_page . ')" id="DataTables_Table_0_last" class="last ui-corner-tr ui-corner-br fg-button ui-button ui-state-default" tabindex="0">
					            末页
					        </a>
								</div>
						</div>
    				</div>
    				';
			echo $str;
		}
	}

	/** 读取url地址内容做模板 */
	public function ajaxwebAction()
	{
		$postUrl = $this->_request->getPost('files_contents');
		//one
		$nurl = parse_url($postUrl);
		$finalBaseUrl = $nurl['scheme'] . "://" . $nurl['host'];
		if (!empty($nurl['port'])) {
			$finalBaseUrl .= ':' . $nurl['port'];
		}

		/*img*/

		$html = file_get_html($postUrl);
		if (!empty($html)) {
			foreach ($html->find('img') as $element) {
				if (stripos($element->src, 'http://') !== 0 && stripos($element->src, 'https://') !== 0 && stripos($element->src, '//') !== 0) {
					$element->src = $finalBaseUrl . $element->src;
				}
			}
			/*link*/
			foreach ($html->find('link') as $element) {
				if (stripos($element->href, 'http://') !== 0 && stripos($element->href, 'https://') !== 0 && stripos($element->href, '//') !== 0) {
					$element->href = $finalBaseUrl . $element->href;
				}
			}
			/*flash*/
			$embed = $html->find('embed');
			foreach ($embed as $element) {
				if (stripos($element->src, 'http://') !== 0 && stripos($element->src, 'https://') !== 0 && stripos($element->src, '//') !== 0) {
					$element->src = $finalBaseUrl . $element->src;
				}
			}
			/*td background*/
			$tds = $html->find('table td[background]');
			foreach ($tds as $element) {
				if (stripos($element->background, 'http://') !== 0 && stripos($element->background, 'https://') !== 0 && stripos($element->background, '//') !== 0) {
					$element->background = $finalBaseUrl . $element->background;
				}
			}
			/*IFRAME*/
			$iframes = $html->find('iframe');
			foreach ($iframes as $element) {
				if (stripos($element->src, 'http://') !== 0 && stripos($element->src, 'https://') !== 0 && stripos($element->src, '//') !== 0) {
					$element->src = $finalBaseUrl . $element->src;
				}
			}

			echo $html;
		} else {
			echo "ERROR -- get contents error";
		}
	}

	public function uploadtemAction()
	{
		if ($this->_request->isPost()) {
			$upload = new Zend_File_Transfer_Adapter_Http();
			$upload_dir = "/var/www/maildelivery/uploads"; //linux
			// $upload_dir = "D:\AppServ\www\V1.1\web\uploads";//windows
			$upload->setDestination($upload_dir);
			$upload->addValidator('Extension', false, 'html');
			$upload->addValidator('Count', false, array('min' => 1, 'max' => 5));
			$upload->addValidator('FilesSize', false, array('min' => '0KB', 'max' => '1024KB'));
			$fileInfo = $upload->getFileInfo();
			$setname = mt_rand(1, 9999) . time() . $fileInfo['files']['name'];
			$newname = $upload_dir . '/' . $setname;
			// addFilter 这个改名字的,你自己评估下用不用吧.
			$upload->addFilter('Rename', array('target' => $newname, 'overwrite' => false));
			$userid = $this->getCurrentUserID();
			$role = $this->getCurrentUserRole();
			$uname = $this->getCurrentUser();
			$description = "该用户进行模板操作.上传模板名字为：" . $fileInfo['files']['name'];
			$description_en = "The user template upload operation. The template is: " . $fileInfo['files']['name'];
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, '上传操作', $description, 'UPload operation', $description_en, $_SERVER["REMOTE_ADDR"]);
			$receive = $upload->receive();
			if ($receive === false) {
				$messages = $upload->getMessages();
				echo implode("\n", $messages);
			} else {
				$file = fopen($newname, "r+b");
				while (!feof($file)) {
					echo fgets($file);
				}
				@fclose($file);
				unlink($newname);
			}
		}
	}

	public function previewAction()
	{
		$content = $this->_request->getPost('templets');
		$p = $this->Smarty->template_dir;
		// $path = $p[0] . "views.php";

		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();

		$path = $p[0] . $uname . "views.php";
		if (!is_file($path)) {
			@exec("sudo touch $path");
		}
		@exec("sudo chmod 777 $path");

		$description = "该用户进行模板预览操作";
		$description_en = "The user template preview operation. ";
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '预览操作', $description, 'Preview operation', $description_en, $_SERVER["REMOTE_ADDR"]);

		$file = fopen($path, "w+");
		fwrite($file, $content);
		@fclose($file);
		echo 111;
	}
	public function previewoneAction()
	{
		$uname = $this->getCurrentUser();
		$this->Smarty->display($uname . "views.php");
	}

	public function tplviewAction()
	{
		$rel_id = $this->_request->get('relid');
		$this->template = new Template();
		$rows = $this->template->getOneTpl($rel_id);
		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$description = "该用户进行模板预览操作";
		$description_en = "The user template preview operation. ";
		BehaviorTrack::addBehaviorLog($uname, $role, $userid, '预览操作', $description, 'Preview operation', $description_en, $_SERVER["REMOTE_ADDR"]);
		if ($rows) {
			$p = $this->Smarty->template_dir;
			$path = $p[0] . $uname . "views.php";
			$file = fopen($path, "wb+");
			if (fwrite($file, stripslashes($rows->tpl_body))) {
				$this->_redirect('/templet/previewone');
			}

			@fclose($file);
		}
	}

	/*删除模板*/
	public function deltplsAction()
	{
		if ($this->_request->isPost()) {
			$post_id = $this->_request->getPost('delid');
			$sql = "SELECT tpl_name FROM mr_template WHERE id = " . $post_id;
			$per_names = $this->dbAdapter->fetchOne($sql);
			$this->template = new Template();
			$this->templatevocation = new Templatevocation();
			$resoult = $this->template->getOneTpl($post_id);
			$tplimg = $resoult->tpl_img;
			if ($tplimg != "121.jpg") {
				@exec('sudo rm -rf /var/www/maildelivery/dist/thumb_images/' . $tplimg);
			}
			$userid = $this->getCurrentUserID();
			$role = $this->getCurrentUserRole();
			$uname = $this->getCurrentUser();
			$description = "该用户进行模板删除操作，模板名为：" . $per_names;
			$description_en = "The user template delete operation, the template named: " . $per_names;
			BehaviorTrack::addBehaviorLog($uname, $role, $userid, ' 删除操作', $description, 'Delete operation', $description_en, $_SERVER["REMOTE_ADDR"]);
			$rows = $this->template->delOne($post_id);
			if ($rows) {

				$row = $this->templatevocation->deltvOne($post_id);
				if ($row) {
					echo 1;
				}
			}
		}
	}

	/*CheckTplname*/

	public function checktplAction()
	{
		if ($this->_request->isPost()) {
			$tplname = trim($this->_request->getPost('tplnames'));
			$tpls = trim($this->_request->getPost('tpls'));
			if ($tplname) {
				$uid = $_SESSION['Zend_Auth']['storage']->id;
				$this->check = new Template();
				$resoult = $this->check->checkTplOne($tplname);
			}
			if ($tpls) {
				$sql = 'SELECT id FROM mr_template WHERE tpl_style=-1 AND tpl_name="' . $tpls . '"';
				$resoult = $this->dbAdapter->fetchOne($sql);
			}
			if ($resoult) {
				echo "nopass";
			} else {
				echo "pass";
			}
		}
	}

	public function edituploadAction()
	{
		$extensions_images = array("jpg", "bmp", "gif", "png");
		$extensions_flash = array("fla", "flv", "as", "asc", "swf");
		$uploadFilename = $_FILES['upload']['name'];
		$extension = strtolower(pathInfo($uploadFilename, PATHINFO_EXTENSION));

		$dir_root = $_SERVER['DOCUMENT_ROOT'];

		$uploadType = $this->_request->get('type');
		$url = $this->dbAdapter->fetchRow("select domainname,https,dport from mr_console");

		$userid = $this->getCurrentUserID();
		$role = $this->getCurrentUserRole();
		$uname = $this->getCurrentUser();
		$datetime = date('Y-m-d H:i:s', time());
		
		if ($uploadType == "Images") {
			if (in_array($extension, $extensions_images)) {
				$uploadPath = str_replace("\\",'/',realpath($dir_root))."/uploads/images/";  //Windows
				// $uploadPath = "/home/maildelivery/images/";  //Linux
				$uuid = str_replace('.', '', uniqid("", TRUE)) . "." . $extension;
				$desname = $uploadPath . $uuid;
				if ($url['https'] == 1) {
					$previewname = 'https://' . $url['domainname'] . ':' . $url['dport'] . '/uploads/images/' . $uuid;
				} else {
					$previewname = 'http://' . $url['domainname'] . ':' . $url['dport'] . '/uploads/images/' . $uuid;
				}
				$tag = move_uploaded_file($_FILES['upload']['tmp_name'], $desname);
				$filesize = filesize($desname);
				if ($tag) {
					$insert_sql = 'INSERT INTO mr_images(uid,role,author,aliasname,truename,createtime,filesize) VALUES(' . $userid . ',"' . $role . '","' . $uname . '","' . $uuid . '","' . $uploadFilename . '","' . $datetime . '",' . $filesize . ')';
					$resoults = $this->dbAdapter->query($insert_sql);
				}
				$callback = $_REQUEST["CKEditorFuncNum"];
				echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($callback,'" . $previewname . "','');</script>";
			} else {
				echo "<font color=\"red\"size=\"2\">*文件格式不正确（必须为.jpg/.gif/.bmp/.png文件）</font>";
			}
		} else {
			if (in_array($extension, $extensions_flash)) {
				$uploadPath = str_replace("\\",'/',realpath($dir_root))."/uploads/images/";  
				// $uploadPath = "/home/maildelivery/images/";  //Linux
				$uuid = str_replace('.', '', uniqid("", TRUE)) . "." . $extension;
				$desname = $uploadPath . $uuid;
				if ($url['https'] == 1) {
					$previewname = 'https://' . $url['domainname'] . ':' . $url['dport'] . '/uploads/images/' . $uuid;
				} else {
					$previewname = 'http://' . $url['domainname'] . ':' . $url['dport'] . '/uploads/images/' . $uuid;
				}
				// $previewname = 'http://'.$url['domainname'].'/uploads/images/'.$uuid;  
				$tag = move_uploaded_file($_FILES['upload']['tmp_name'], $desname);
				$callback = $_REQUEST["CKEditorFuncNum"];
				echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($callback,'" . $previewname . "','');</script>";
			} else {
				echo "<font color=\"red\"size=\"2\">*文件格式不正确（必须为.swf/.fla/.flv/.ac/.asc文件）</font>";
			}
		}
	}
}
