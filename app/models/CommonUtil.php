<?php
class CommonUtil {

	public static function parseEmlFiles($dirpath, $with_echo, $maildb) {
		if(!is_dir($dirpath)) {
			return 0;
		}
		$handled = 0;
		$handle = opendir($dirpath);
		if($handle) {
			while(false !== ($file = readdir($handle))) {
				if ($file != '.' && $file != '..') {
					$filename = $dirpath . "/" . $file;
					if(is_file($filename)) {
						$status = CommonUtil::importEmlFile($filename, $maildb, $with_echo);
						if ($status) {
							if ($with_echo) {
								echo "\r\nSucced to import a mail file:".$filename.".\r\n";
							}
							$handled++;
						}
					}else {
						$handled = $handled + CommonUtil::parseEmlFiles($filename, $with_echo, $maildb);
					}
				}
				
			}
			closedir($handle);
		}
		return $handled;
	}
    
    public static function uuid() {
      $chars = md5(uniqid(mt_rand(), true));
      $uuid  = substr($chars,0,8) . '-';
      $uuid .= substr($chars,8,4) . '-';
      $uuid .= substr($chars,12,4) . '-';
      $uuid .= substr($chars,16,4) . '-';  
      $uuid .= substr($chars,20,12);
      return $uuid;
    }
    
	public function importEmlFile($emlfile, $maildb, $with_echo) {
		$storage_db = new Storage();
		$now_time = time();
		$mail_ym = date('Ym',$now_time);
		$mail_d = date('d',$now_time);
		$storagespath = $storage_db->getCurrentTempMailPath();
		$desc_path = $mail_ym."/".$mail_d;
		$desc_filename = CommonUtil::uuid();
		$dir_path = $storagespath."/".$desc_path;
		if (!file_exists($dir_path)) {
			system("sudo mkdir -p ".$dir_path);
			system("sudo chmod -R 777 ".$storagespath);
		}
		$trappedmail = $dir_path."/".$desc_filename;
		$desc = $desc_path."/".$desc_filename;
		
		system("sudo mv -f '".$emlfile."' ".$storagespath."/".$desc_path."/".$desc_filename);
		
		$Parser = new MimeMailParser ( );
		$Parser->setPath ( $trappedmail );
		$inqueuetime = date('Y-m-d H:i:s',$now_time);
		$mail_date_str = $Parser->getHeader('date');
		$temp = $Parser->getHeader('from');
		$mail_from = $Parser->decode_mime($temp);
		$mail_from = str_replace(",", ";", $mail_from);
		$temp = $Parser->getHeader('to');
		$mail_to = $Parser->decode_mime($temp);
		$mail_to = str_replace(",", ";", $mail_to);
		$temp = $Parser->getHeader('cc');
		$mail_cc = $Parser->decode_mime($temp);
		$mail_cc = str_replace(",", ";", $mail_cc);
		$temp = $Parser->getHeader('bcc');
		$mail_bcc = $Parser->decode_mime($temp);
		$mail_bcc = str_replace(",", ";", $mail_bcc);
		$temp = $Parser->getHeader('subject');
		$mail_subject = $Parser->decode_mime($temp);
		$mail_date = $inqueuetime;
		if ($mail_date_str != null && $mail_date_str != "") {
			$mail_date = date('Y-m-d H:i:s',strtotime($mail_date_str));
		}
		$hasattachment = 0;
		$attachments = $Parser->getAttachmentNames();
		if (count($attachments) > 2) {
			$hasattachment = 1;
		}
		$mail_size = 0;
		if ($mail_size == 0 || $mail_size == '' || $mail_size == null) {
			$mail_size = filesize($trappedmail);
		}
		$sendip = "127.0.0.1";

		$sql = "INSERT INTO mr_smtp_task(status, mode, hasattachment, inqueuetime, mailtime, sendtime, mailsize, sendfrom, emlpath, title, srcIP, sendto, cc, bcc) VALUES (0, 0,".$hasattachment.",'".$inqueuetime."','".$mail_date."','".$mail_date."',".$mail_size.",'".mysql_escape_string($mail_from)."','".$desc."','".mysql_escape_string($mail_subject)."','".$sendip."','".mysql_escape_string($mail_to)."','".mysql_escape_string($mail_cc)."','".mysql_escape_string($mail_bcc)."')";
		try {
			$result = $maildb->execSqlForMail($sql);
		}
		catch (Exception $e) {
		}
		unset($desc_filename);
		unset($mail_from);
		unset($mail_to);
		unset($mail_cc);
		unset($mail_bcc);
		unset($mail_subject);
		unset($Parser);
	}
	
	public static function ConvertDataFormat($data) {
		if ($data < 1024*1024) {
			$result = number_format($data/ (1024), 3, '.', '')." KB";
		} else if ($data < 1024*1024*1024) {
			$result = number_format($data/ (1024*1024), 3, '.', '')." MB";
		} else if ($data < 1024*1024*1024*1024) {
			$result = number_format($data/ (1024*1024*1024), 3, '.', '')." GB";
		} else {
			$result = number_format($data/ (1024*1024*1024*1024), 3, '.', '')." TB";
		}
		$result = str_replace( ",", "", $result );
		return $result;
	}
	
	public static function getSearchPermission($uid, $role) {
		$where='';
		return "";
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
            $where = " 1=1 and uid in(" . $str . ")";
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
}
?>