<?php
class SystemConsole {
	public static function getConsoleEth () {
		return Zend_Registry::get ( "console_eth" );
	}
	
	public static function getApacheConfigPath () {
		return Zend_Registry::get ( "apache_config_path" );
	}
	
	public static function getApacheWebConfigPath () {
		return Zend_Registry::get ( "apache_web_config_path" );
	}
	
	public static function getApacheSSLCrtPath () {
		return Zend_Registry::get ( "apache_ssl_crt_path" );
	}
	
	public static function getApacheSSLKeyPath () {
		return Zend_Registry::get ( "apache_ssl_key_path" );
	}
	
	public static function CheckSmtpService($server, $port, $timeout){
		if (empty($server)||empty($port))
			return false;
		
		if ($timeout == "")
			$timeout = 30;
			
		if ($port != 25) {
			$fs = fsockopen ("ssl://".$server, $port, $errno, $errstr, $timeout);
		} else {
			$fs = fsockopen ($server, $port, $errno, $errstr, $timeout);
		}
		
		if (!$fs)
			return false;
			
		//connected..
		$lastmessage=fgets($fs,256);
 		if ( substr($lastmessage,0,3) != 220 ) {
 			fclose($fs);
			return false;
 		} else {
			fclose($fs);
			return true;
		}
	}
	
	public static function GetNetworkAddr($eth) {
		$result = array();
		@exec("sudo ifconfig ".$eth, $return_array, $status);
		foreach ( $return_array as $value ) {
			if (strstr($value, 'inet addr') != false) {
				$configs = explode ( " ", $value );
				foreach ($configs as $config) {
					if (strstr($config,'addr' ) != false) {
						$ip = substr($config,5);
						$result['ip'] = $ip;
					} else if (strstr($config, 'Mask') != false) {
						$mask = substr($config,5);
						$result['mask'] = $mask;
					}
				}
			}
		}
		return $result;
	}
	
	public static function UpdateConsole($mc_url, $mc_port, $mc_ssl, $mc_dirname) {
		$console_eth = SystemConsole::getConsoleEth();
		$apacheConfigPath = SystemConsole::getApacheConfigPath();
		$apacheWebConfigPath = SystemConsole::getApacheWebConfigPath();
		$apacheSSLCrtPath = SystemConsole::getApacheSSLCrtPath();
		$apacheSSLKeyPath = SystemConsole::getApacheSSLKeyPath();
		$addr = SystemConsole::GetNetworkAddr($console_eth);
		$ip = $addr['ip'];
		$path = '/etc/apache2/sites-available/default';
		if($mc_dirname == "maildelivery"){
			if ($apacheConfigPath != null && $apacheConfigPath != "") {
				$path = $apacheConfigPath;
			}
		}else if($mc_dirname == "maildeliveryservice"){
			if ($apacheWebConfigPath != null && $apacheWebConfigPath != "") {
				$path = $apacheWebConfigPath;
			}
		}
		if ($apacheSSLCrtPath == null || $apacheSSLCrtPath == "") {
			$apacheSSLCrtPath = "/etc/httpd/ssl/maildata.crt";
		}
		if ($apacheSSLKeyPath == null || $apacheSSLKeyPath == "") {
			$apacheSSLKeyPath = "/etc/httpd/ssl/maildata.key";
		}
		$configFile = fopen ( $path, 'w+' );
		if (!$configFile) {
			@exec("sudo touch ".$path, $return_array, $status);
			$configFile = fopen ( $path, 'w+' );
		}
		if ($configFile) {
			fwrite ( $configFile, "listen *:".$mc_port."\r\n" );
			fwrite ( $configFile, "NameVirtualHost *:".$mc_port."\r\n" );
			
			fwrite ( $configFile, "<VirtualHost *:".$mc_port.">\r\n" );
			if ($mc_url != "" && $mc_url != null) {
				fwrite ( $configFile, "ServerName ".$mc_url.":".$mc_port."\r\n" );
			} else {
				fwrite ( $configFile, "ServerName ".$ip.":".$mc_port."\r\n" );
			}			
			fwrite ( $configFile, "DocumentRoot /var/www/".$mc_dirname."\r\n" );
			if ($mc_ssl) {
				fwrite ( $configFile, "SSLEngine ON\r\n" );
				fwrite ( $configFile, "SSLCertificateFile ".$apacheSSLCrtPath."\r\n" );
		 		fwrite ( $configFile, "SSLCertificateKeyFile ".$apacheSSLKeyPath."\r\n" );
			}
			fwrite ( $configFile, "<Directory \"/var/www/".$mc_dirname."\">\r\n" );
			fwrite ( $configFile, "Options Indexes FollowSymLinks\r\n" );
			fwrite ( $configFile, "AllowOverride all\r\n" );
			fwrite ( $configFile, "Order allow,deny\r\n" );
		 	fwrite ( $configFile, "Allow from all\r\n" );
		 	fwrite ( $configFile, "</Directory>\r\n" );
			fwrite ( $configFile, "</VirtualHost>\r\n" );
			$status = true;
		} else {
			$status = false;
		}
		fclose($configFile);
		return $status;
	}

	public static function ApacheRestart () {
		$backend_ip = Zend_Registry::get("backend_ip");
		$guardian_port = Zend_Registry::get("guardian_port");
		$status = DeliveryAPI::RestartApache($backend_ip, $guardian_port);
		return $status;
	}
	
	public static function GetLicenseInfo () {
		$backend_ip = Zend_Registry::get("backend_ip");
		$backend_port = Zend_Registry::get("backend_port");
		$infos = DeliveryAPI::GetLicenseInfo($backend_ip, $backend_port);
		return $infos;
	}
	
	public static function ReloadSpfCfg () {
		$backend_ip = Zend_Registry::get("backend_ip");
		$spfserver_port = Zend_Registry::get("spfserver_port");
		$status = DeliveryAPI::ReloadSpfCfg($backend_ip, $spfserver_port);
		return $status;
	}
	
	public static function ReloadRelayCfg () {
		$backend_ip = Zend_Registry::get("backend_ip");
		$spfserver_port = Zend_Registry::get("engine_port");
		$status = DeliveryAPI::ReloadSpfCfg($backend_ip, $spfserver_port);
		return $status;
	}
	
	public static function RebootDevice() {
		@exec ("sudo reboot");
		return;
	}
	
	public static function RestartMysqld() {
		$backend_ip = Zend_Registry::get("backend_ip");
		$guardian_port = Zend_Registry::get("guardian_port");
		$infos = DeliveryAPI::RestartMysqldService($backend_ip, $guardian_port);
		return $infos;
	}
	
	public static function RestartSMTP() {
		$backend_ip = Zend_Registry::get("backend_ip");
		$guardian_port = Zend_Registry::get("guardian_port");
		$infos = DeliveryAPI::RestartSmtpService($backend_ip, $guardian_port);
		return $infos;
	}
	
	public static function RestartDS() {
		$backend_ip = Zend_Registry::get("backend_ip");
		$guardian_port = Zend_Registry::get("guardian_port");
		$infos = DeliveryAPI::RestartMDService($backend_ip, $guardian_port);
		return $infos;
	}
	
	public static function RestartAuth() {
		$backend_ip = Zend_Registry::get("backend_ip");
		$guardian_port = Zend_Registry::get("guardian_port");
		$infos = DeliveryAPI::RestartAuthService($backend_ip, $guardian_port);
		return $infos;
	}
	
	public static function RestartConversion() {
		$backend_ip = Zend_Registry::get("backend_ip");
		$guardian_port = Zend_Registry::get("guardian_port");
		$infos = DeliveryAPI::RestartConversionService($backend_ip, $guardian_port);
		return $infos;
	}
	
	public static function RestartFilter() {
		$backend_ip = Zend_Registry::get("backend_ip");
		$guardian_port = Zend_Registry::get("guardian_port");
		$infos = DeliveryAPI::RestartFilterService($backend_ip, $guardian_port);
		return $infos;
	}
	
	public static function ShutDownDevice() {
		@exec ("sudo init 0");
		return;
	}
	
	public static function setSessionTimout($admin) {
		$admin = (int)$admin;
		if ($admin == "" || $admin == null || $admin <= 0) {
			$admin = 30;
		}
		$authSession = new Zend_Session_Namespace('Zend_Auth');
		$authSession->setExpirationSeconds($admin * 60);		
	}

	public static function CustomSysTime ($time_str) {
		@exec ("sudo date -s ".$time_str);
		@exec ("sudo hwclock -w");
	}
	
	public static function ReadSnmpConfig () {
		$path = '/var/www/maildelivery/app/config/snmp.ini';
		@exec ("sodu chmod 777 ".$path, $return, $status);
		$fopen = fopen($path,"r+");
		$snmp = array();
		if ( $fopen ) {
			$data = file ( $path );
			foreach ($data as $item) {
				if (strpos($item, "\r") !== false) {
					$item = str_replace("\r", "", $item);
				}
				if (strpos($item, "use=") !== false) {
					$snmp['use'] = substr($item, strlen("use="), (strpos($item, "\n")-strlen("use=")));
				} else if (strpos($item, "connection_str=") !== false) {
					$snmp['connection_str'] = substr($item, strlen("connection_str="), (strpos($item, "\n")-strlen("connection_str=")));
				} else if (strpos($item, "ip_area=") !== false) {
					$snmp['ip_area'] = substr($item, strlen("ip_area="), (strpos($item, "\n")-strlen("ip_area=")));
				} else if (strpos($item, "user=") !== false) {
					$snmp['user'] = substr($item, strlen("user="), (strpos($item, "\n")-strlen("user=")));
				} else if (strpos($item, "password=") !== false) {
					$snmp['password'] = substr($item, strlen("password="), (strpos($item, "\n")-strlen("password=")));
				} else if (strpos($item, "auth_type=") !== false) {
					$snmp['auth_type'] = substr($item, strlen("auth_type="), (strpos($item, "\n")-strlen("auth_type=")));
				} else if (strpos($item, "encryption=") !== false) {
					$snmp['encryption'] = substr($item, strlen("encryption="), (strpos($item, "\n")-strlen("encryption=")));
				} else if (strpos($item, "version=") !== false) {
					$snmp['version'] = substr($item, strlen("version="), (strpos($item, "\n")-strlen("version=")));
				}
			}
			return $snmp;
		}
		fclose( $fopen );
	}
	
	public static function ConfigSnmp ( $data ) {
		$path = '/var/www/maildelivery/app/config/snmp.ini';
		@exec ("sodu chmod 777 ".$path, $return, $status);
		$fopen = fopen($path,"r+");
		if ( $fopen ) {
			$infos = file ( $path );
			$num = count($infos);
			for ($i=0; $i<$num; $i++) {
				if ($data['snmpserver'] == "enable") {
					if (strpos($infos[$i], "use=") !== false) {
						$infos[$i] = "use="."enable\r\n";
					}
					if ($data['versions'] == '2') {
						if (strpos($infos[$i], "version=") !== false) {
							$infos[$i] = "version=".$data['versions']."\r\n";
						}
						if (strpos($infos[$i], "connection_str=") !== false) {
							$infos[$i] = "connection_str=".$data['connstr']."\r\n";
						}
						if (strpos($infos[$i], "ip_area=") !== false) {
							$infos[$i] = "ip_area=".$data['iparea']."\r\n";
						}
						
					} else {
						if (strpos($infos[$i], "version=") !== false) {
							$infos[$i] = "version=".$data['versions']."\r\n";
						}
						if (strpos($infos[$i], "user=") !== false) {
							$infos[$i] = "user=".$data['username']."\r\n";
						}
						if (strpos($infos[$i], "password=") !== false) {
							$infos[$i] = "password=".$data['pwd']."\r\n";
						}
						if (strpos($infos[$i], "auth_type=") !== false) {
							$infos[$i] = "auth_type=".$data['authtype']."\r\n";
						}
						if (strpos($infos[$i], "encryption=") !== false) {
							$infos[$i] = "encryption=".$data['encryption']."\r\n";
						}
						
					}
				} else {
					if (strpos($infos[$i], "use=") !== false) {
						$infos[$i] = "use="."disable\r\n";
					}
					
				}
			}
			file_put_contents( $path, $infos );
		}
		fclose( $fopen );
		if ($data['snmpserver'] == "enable") {
			if ($data['versions'] == 2) {
				self::WriteInConfigFileV2($data);
			} else {
				self::WriteInConfigFileV3($data);
			}
		} else {
			@exec("sudo /etc/init.d/snmpd stop", $r, $v);
		}
	}
	
	public static function WriteInConfigFileV3($data) {
		@exec("sudo /etc/init.d/snmpd stop", $r, $v);
		@exec("sudo net-snmp-config --create-snmpv3-user -ro -a ".$data['pwd']." -X ".$data['encryption']." -A ".$data['authtype']." ".$data['username'], $return_array, $status);	
		sleep(1);
		@exec("sudo /etc/init.d/snmpd restart", $r, $v);
	}
	
	public static function WriteInConfigFileV2($data) {
		$path = '/etc/snmp/snmpd.conf';
		@exec("sudo chmod 777 /etc/snmp/snmpd.conf", $r, $v);
		$fopen = fopen($path,"r+");
		if ( $fopen ) {
			$infos = file ( $path );
			$num = count($infos);
			$pnum = 0;
			for ($i=0; $i<$num; $i++) {
				if (strpos($infos[$i], "com2sec notConfigUser") !== false) {
					$pnum++;
					if ($pnum == 2) {
						$infos[$i] = "com2sec notConfigUser ".$data['iparea']." ".$data['connstr'].PHP_EOL;
					}
				}
			}
			file_put_contents( $path, $infos );
		}
		fclose( $fopen );	
		@exec("sudo /etc/init.d/snmpd restart", $r, $v);
	}
	
	public static function sendmail($user, $newpwd, $mailbox) {
		$sendmail = new Sendmail ();
		$mailauth = $sendmail->getAllInfos ();
		if ($mailauth == null || $mailauth == "") {
			return "No Configuration";
		}
		$account = $mailauth[0];
		$config = array('name' => $account['smtpserver'], 'port' => $account['smtpserverport'], 'auth' => 'login',
            'username' => $account['authuser'],
            'password' => $account['authpwd']);
		try {
			$transport = new Zend_Mail_Transport_Smtp( $account['smtpserver'], $config );
			$mail = new Zend_Mail("utf-8");
			//$mail = new Zend_Mail();
			$bodytext = "管理员 ".$user." 您好，您得新密码为：".$newpwd."<br>为了安全考虑，请您登陆系统后自行修改密码！";
			
			$mail->setBodyHtml( $bodytext );
			//$mail->setBodyText( $bodytext );
			$mail->setFrom($account['authuser']);						
			$mail->addTo(trim($mailbox));						
			$mailsubject = "MailData邮件分发投递系统密码找回";
			$mail->setSubject( $mailsubject );
			$mail->send($transport);
			return "success";
		} catch (Exception $e){
			$e->getMessage();
		}
		return "failure";
	}
	
	public static function sendAlertMail($mailbox, $cpu_alert, $subarea_alert, $mailqueue_alert, $dqueue_alert) {
		$sendmail = new Sendmail ();
		$mailauth = $sendmail->getAllInfos ();
		if ($mailauth == null || $mailauth == "") {
			return "No Configuration";
		}
		$account = $mailauth[0];
		$config = array('name' => $account['smtpserver'], 'port' => $account['smtpserverport'], 'auth' => 'login',
            'username' => $account['authuser'],
            'password' => $account['authpwd']);
		try {
			$transport = new Zend_Mail_Transport_Smtp( $account['smtpserver'], $config );
			$mail = new Zend_Mail("utf-8");
			//$mail = new Zend_Mail();
			$bodytext = "";
			if ($cpu_alert) {
				$bodytext .= "您好，CPU使用率已达到您设定的界限，为了保证MailDelivery邮件投递分发系统正常运行，请及时处理！";
			}
			if ($subarea_alert) {
				$subarea_alert = str_replace("@", "，", $subarea_alert);
				$bodytext .= "</br>分区：".$subarea_alert."已达到告警界限，为了保证MailDelivery邮件投递分发系统正常运行，请及时处理！";
			}
			if ($mailqueue_alert) {
				$bodytext .= "</br>投递队列中的数量已达到阀值，为了保证MailDelivery邮件投递分发系统正常运行，请及时处理！";
			}
			if ($dqueue_alert) {
				$bodytext .= "</br>投递失败的数量已达到阀值，为了保证MailDelivery邮件投递分发系统正常运行，请及时处理！";
			}
            $fromname = "MailData邮件分发投递系统";
            $subject = "MailData邮件分发投递系统告警邮件";
			if(is_array($mailbox)){
				foreach($mailbox as $mails){
                    SystemConsole::selfMadeMail($account, $fromname, $subject, $bodytext, trim($mails), "", "admin");
				}
			}else{
                SystemConsole::selfMadeMail($account, $fromname, $subject, $bodytext, trim($mailbox), "", "admin");
			}
			
			return "success";
		} catch (Exception $e){
			print $e->getMessage();
		}
		return "failure";
	}
	
	public static function readSmtpdIni () {
		$path = '/var/www/maildelivery/app/config/smtpd.ini';
		@exec("sudo chmod 777 ".$path, $r, $v);
		$smtpd = new Zend_Config_Ini ( '/var/www/maildelivery/app/config/smtpd.ini', null, true );
		$infos = array ();
		$infos['used'] = $smtpd->general->used;
		$infos['smtpd_ip'] = $smtpd->general->smtpd_ip;
		$infos['allow_ip'] = $smtpd->general->allow_ip;
		$infos['hostname'] = $smtpd->general->hostname;
		$infos['domain'] = $smtpd->general->domain;
		return $infos;
	}
	
	public static function configSmtpdIni ($data) {
		$path = '/var/www/maildelivery/app/config/smtpd.ini';
		@exec("sudo chmod 777 ".$path, $r, $v);
		$fopen = fopen ($path,"r+");
		if ($fopen) {
			$infos = file ( $path );
			$num = count($infos);
			for ($i=0; $i<$num; $i++) {
				if (strpos($infos[$i], "used") !== false) {
					$infos[$i] = "used = ".$data['enablesmtp'].PHP_EOL;
				}
				if (strpos($infos[$i], "smtpd_ip") !== false) {
					$infos[$i] = "smtpd_ip = ".$data['smtpip'].PHP_EOL;
				}
				if (strpos($infos[$i], "allow_ip") !== false) {
					$infos[$i] = "allow_ip = ".$data['smtparea'].PHP_EOL;
				}
				if (strpos($infos[$i], "hostname") !== false) {
					$infos[$i] = "hostname = ".$data['hostname'].PHP_EOL;
				}
				if (strpos($infos[$i], "domain") !== false) {
					$infos[$i] = "domain = ".$data['domain'].PHP_EOL;
				}
			}
			file_put_contents( $path, $infos );
		}
		fclose( $fopen );	
	}
	
	public static function configMrengineConf ($data) {
		$path = '/opt/share/longgerconf/mrengine.conf';
		@exec("sudo chmod 777 ".$path, $r, $v);
		$fopen = fopen ($path,"r+");
		$infos = "";
		if ($fopen) {
			$infos = file ( $path );
			$num = count($infos);
			for ($i=0; $i<$num; $i++) {
				if (strpos($infos[$i], "mail_return") !== false) {
					if ($data['bounce'] == '0') {
						$infos[$i] = "mail_return=false".PHP_EOL;
					} else {
						$infos[$i] = "mail_return=true".PHP_EOL;
					}
				} else if (strpos($infos[$i], "spf_default_domain") !== false) {
					$infos[$i] = "spf_default_domain=".$data['mainfield'].PHP_EOL;
				} else if (strpos($infos[$i], "default_domain") !== false && strpos($infos[$i], "use_default_domain") === false) {
					$infos[$i] = "default_domain=".$data['mainfield'].PHP_EOL;
				} else if (strpos($infos[$i], "default_thread_num_per_ip") !== false) {
					$infos[$i] = "default_thread_num_per_ip=".$data['threadnum'].PHP_EOL;
				}
			}
			file_put_contents( $path, $infos );
		}
		fclose( $fopen );
	}
	
	public static function configMainCf ($data) {
		$path = '/var/www/maildelivery/app/config/main.cf.tmpl';
		@exec("sudo chmod 777 ".$path, $r, $v);
		$fopen = fopen ($path,"r+");
		$infos = "";
		if ($fopen) {
			$infos = file ( $path );
			$num = count($infos);
			for ($i=0; $i<$num; $i++) {
				if (strpos($infos[$i], "myhostname = $$$1") !== false) {
					$infos[$i] = "myhostname = ".$data['hostname'].PHP_EOL;
				}
				if (strpos($infos[$i], "mydomain = $$$2") !== false) {
					$infos[$i] = "mydomain = ".$data['mainfield'].PHP_EOL;
				}
			}
		}
		fclose( $fopen );	
		$main_path = "/etc/postfix/main.cf";
		@exec("sudo chmod 777 ".$main_path, $r, $v);
		$main_fopen = fopen ($main_path, "r+");
		if ($main_fopen) {
			file_put_contents( $main_path, $infos );
		}
	}
	
	public static function configMasterCf ($data) {
		if ($data['enablesmtp'] == "0" || $data['smtpip'] == "" || $data['smtpip'] == "127.0.0.1") {
			$path = '/var/www/maildelivery/app/config/master2.cf.tmpl';
		} else {
			$path = '/var/www/maildelivery/app/config/master.cf.tmpl';
		}
		@exec("sudo chmod 777 ".$path, $r, $v);
		$fopen = fopen ($path,"r+");
		$infos = "";
		if ($fopen) {
			$infos = file ( $path );
			if ($data['enablesmtp'] != "0" && $data['smtpip'] != "" && $data['smtpip'] != "127.0.0.1") {
				$num = stripos ($infos[0], ":");
				$infos[0] = substr_replace($infos[0], $data['smtpip'], 0, $num);
			}
		}
		fclose( $fopen );	
		$main_path = "/etc/postfix/master.cf";
		@exec("sudo chmod 777 ".$main_path, $r, $v);
		$main_fopen = fopen ($main_path, "r+");
		if ($main_fopen) {
			file_put_contents( $main_path, $infos );
		}
		@exec ("sudo /etc/init.d/postfix restart");
	}
	
	public static function ConfigNetwork($eth, $ip, $mask, $gw, $dns_list) {
		SystemConsole::ConfigNetworkAddrForCentos($eth, $ip, $mask);
		SystemConsole::ConfigGWForCentos($eth, $gw);
		SystemConsole::ConfigDNS($dns_list);
		SystemConsole::RestartNetworkService();
	}
	
	public static function ConfigNetworkAddrForCentos($eth, $ip, $mask) {
		$command = 'ifconfig '.$eth.' '.$ip.' netmask '.$mask;
		@exec("sudo ".$command, $return_array, $status);		
		$path = '/etc/sysconfig/network-scripts/ifcfg-'.$eth;
		@exec("sudo chmod 777 ".$path, $return_array, $status);
		$ip_sections = split ( "\.", $ip );
		$ip_prefix = "";
		for($i = 0; $i < count ( $ip_sections ) - 1; $i++) {
			$ip_prefix = $ip_prefix.$ip_sections[$i].".";
		}
		$network = $ip_prefix."0";
		$broadcast = $ip_prefix."255";		
		$hwaddr = "";
		$file_handle = fopen($path, "r");
		while (!feof($file_handle)) {
			$line = fgets($file_handle);
			if (strstr($line,"HWADDR=") != false) {
				$hwaddr = $line;
				break;
			}			   
		}
		fclose($file_handle);
		
		$configFile = fopen ( $path, 'w+' );
		if ($configFile) {
			fwrite ( $configFile, "DEVICE=".$eth."\n" );
			fwrite ( $configFile, "BOOTPROTO=static\n" );
			fwrite ( $configFile, "IPADDR=".$ip."\n" );
			fwrite ( $configFile, "NETMASK=".$mask."\n" );
			if ($hwaddr) {
				fwrite ( $configFile, $hwaddr );
			}
			fwrite ( $configFile, "ONBOOT=yes\n" );
			$status = true;
		} else {
			$status = 888;
		}
		fclose($configFile);
		return $status;
	}
	
	private static function ConfigGWForCentos($eth, $gw) {
		$command = 'route add default gw '.$gw.' dev '.$eth;
		@exec("sudo ".$command, $return_array, $status);
		$path = '/etc/sysconfig/network';
		@exec("sudo chmod 777 ".$path, $return_array, $status);
		$configFile = fopen ( $path, 'r' );
		$result = array();
		while (!feof($configFile)) {
			$line = fgets($configFile);
			if (strstr($line, 'GATEWAY=') == false) {
				$result[] = trim($line);
			}
		}
		$result[] = "GATEWAY=".$gw;
		@fclose($configFile);
		$configFile2 = fopen ( $path, 'w+' );
		if ($configFile2) {
			foreach ($result as $config) {
				fwrite ( $configFile2, $config."\n" );
			}
			$status = true;
		} else {
			$status = false;
		}
		fclose($configFile2);
		return $status;
	}
	
	private static function ConfigDNS($dns_list) {
		$path = '/etc/resolv.conf';
		@exec("sudo chmod 777 ".$path, $return_array, $status);
		$configFile = fopen ( $path, 'w+' );
		if ($configFile) {
			for($i = 0; $i < count ( $dns_list ); $i++) {
				if ($dns_list[$i] != "" && $dns_list[$i] != null) {
					fwrite ($configFile, 'nameserver '.$dns_list[$i]."\n");
				}				
			}
			$status = true;
		} else {
			$status = false;
		}
		fclose($configFile);
		return $status;
	}
	
	private static function RestartNetworkService() {
		$status = system("sudo service network restart", $return_str);
		return $status;
	}
	
	public static function GetRoute($eth) {
		$result = array();
		@exec("sudo route -n", $return_array, $status);
		foreach ( $return_array as $value ) {
			if (strstr($value, $eth) != false) {
				$configs = explode ( " ", $value );
				$index = 0;
				foreach ($configs as $config) {
					if ($config != "" && $config != null) {
						if ($index == 1) {
							if ($config == '*' || $config == '0.0.0.0') {
								break;
							} else {
								return $config;
							}
						}
						$index++;
					}
				}
			}
		}
		return "";
	}
	
	public static function GetDNS() {
		$dnsfile = '/etc/resolv.conf';
		$result = array();
		if (is_file ( $dnsfile ) && file_exists($dnsfile)) {
			$file_handle = @fopen ( $dnsfile, "r" );
			while (!feof($file_handle)) {
				$line = fgets($file_handle);
				if (strstr($line, 'nameserver') != false) {
					$configs = explode ( " ", $line );
					$index = 0;
					foreach ($configs as $config) {
						if ($config != "" && $config != null) {
							if ($index == 1) {
								$result[] = trim($config);
								break;
							}
							$index++;
						}
					}
				}
			}
				
		}
		return $result;
	}
	
	public static function ConfigVIP($ip, $eth, $data, $mask, $usevip="") {
		if ($usevip != 1) {
			$usevip = 0;
		}
		$vippath = Zend_Registry::get ("vip_path");
		$vip_array = array ();
		if (strpos($data, "\r\n") !== false) {
			$vip_array = explode ("\r\n", $data);
		} else if (strpos($data, "\r") !== false) {
			$vip_array = explode ("\r", $data);
		} else if (strpos($data, "\n") !== false) {
			$vip_array = explode ("\n", $data);
		} else {
			$vip_array[] = $data;
		}
		@exec ("sudo rm -rf ".$vippath."ifcfg-".$eth.":*");
		$num = 0;
		$vip_db_str = "";
		for ($i=0; $i<count($vip_array); $i++) {
			if ($vip_array[$i] != "") {
				$vip_db_str .= $vip_array[$i].";";
				$num++;
				@exec ("sudo cp ".$vippath."ifcfg-source ".$vippath."ifcfg-".$eth.":".$num);
				@exec ("sudo chmod 777 ".$vippath."ifcfg-".$eth.":".$num);
				$path = $vippath."ifcfg-".$eth.":".$num;
				$fopen = fopen($path,"r+");
				if ( $fopen ) {
					$infos = file ( $path );
					$num_index = count($infos);
					for ($d=0; $d<$num_index; $d++) {
						if (strpos ($infos[$d], "DEVICE=") !== false) {
							$infos[$d] = "DEVICE=".$eth.":".$num.PHP_EOL;
						}
						if (strpos ($infos[$d], "IPADDR=") !== false) {
							$infos[$d] = "IPADDR=".$vip_array[$i].PHP_EOL;
						}
						if (strpos ($infos[$d], "NETMASK=") !== false) {
							$infos[$d] = "NETMASK=".$mask.PHP_EOL;
						}
						if (strpos ($infos[$d], "ONBOOT=") !== false) {
							$infos[$d] = "ONBOOT=yes";
						}
					}
					file_put_contents( $path, $infos );
				}
				fclose($fopen);
			}
		}
		if (strpos($vip_db_str, $ip) !== false) {
			$vip_db_str = str_replace($ip, "", $vip_db_str);
			$vip_db_str = $ip.";".$vip_db_str;
		} else {
			$vip_db_str = $ip.";".$vip_db_str;
		}
		$vipdb = new Vip();
		$insert = array (
			"eth" => $eth,
			"usevip" => $usevip,
			"vip" => $vip_db_str
		);
		$vipdb->deleteMatchEthInfo ($eth);
		$vipdb->insertVip ($insert);
		return;
	}
	
	public static function ParseAccessStr($acldata) {
		$aclstr = "";
		if ($acldata['firstpage'] == "on") {//首页
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		$aclstr = $aclstr."@";
		if ($acldata['systemmonitor'] == "on") {//系统监控
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['mailstatistics'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['mailqueue'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['searchlogs'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['denyaccess'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		$aclstr = $aclstr."@";//系统设置
		if ($acldata['consolesetting'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['sendmail'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['alertsetting'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['sysclocksetting'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['workingreport'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['license'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['resetsetting'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['publishedinfo'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		$aclstr = $aclstr."@";//网络设置
		if ($acldata['networksetting'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['networktool'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['snmpconfiguration'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		$aclstr = $aclstr."@";//参数设置
		if ($acldata['securityparam'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['singledomain'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['trustiptable'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['staticmx'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['userintercept'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		$aclstr = $aclstr."@";//联系人管理
		if ($acldata['personlist'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['expansion'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['contactlist'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['filter'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['formlist'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		$aclstr = $aclstr."@";//邮件内容管理
		if ($acldata['createtempl'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['mytempl'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['preset'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['mgattach'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['imgattach'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		$aclstr = $aclstr."@";//投递任务管理
		if ($acldata['create'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['addtask'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['drafttask'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['listtask'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['typetask'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		$aclstr = $aclstr."@";//统计分析
		if ($acldata['singletask'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['taskclassification'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['releaseperson'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['alltaskstatistics'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		if ($acldata['allforwardstatistics'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		$aclstr = $aclstr."@";//账号管理
		if ($acldata['accountmanage'] == "on") {
			$aclstr = $aclstr."1";
		} else {
			$aclstr = $aclstr."0";
		}
		return $aclstr;	
	}
	
	public static function checkHostStatus($type="") {
		if ($type == "") {
			//$TrustipTable = new TrustipTable ();
			//$trustips = $TrustipTable->getAllInfos ();
			$hostarray = array();
			//@exec ("sudo nc ".$trustips[0]['ips']." 25 -w3 -z", $re, $status);
			@exec ("sudo nc 127.0.0.1 25 -w3 -z", $re, $status);
			$hostarray[0] = $status;
			unset($re);
			unset($status);
			$overseasserver = new OverseasServer ();
			$infos = $overseasserver->getInfosLimit ();
			//if ($infos[0]['serverip'] != null && strstr($infos[0]['serverip'], ".") != false) {
				@exec ("sudo nc ".$infos[0]['serverip']." 25 -w3 -z", $re, $status);
				$hostarray[1] = $status;
				unset($re);
				unset($status);
			//}
			return $hostarray;
		} else {
			if ($type != null && strstr($type, ".") != false) {
				@exec ("sudo nc ".$type." 25 -w3 -z", $r, $e);
				return $e;
			} else {
				return 0;
			}
		}
	}
	
	public static function checklocalservice() {
		$result = array();
		@exec ("sudo nc 127.0.0.1 3306 -w3 -z", $r0, $e0);
		$result[0] = $e0;
		@exec ("sudo nc 127.0.0.1 25 -w3 -z", $r1, $e1);
		$result[1] = $e1;
		@exec ("sudo nc 127.0.0.1 32398 -w3 -z", $r2, $e2);
		$result[2] = $e2;
		@exec ("sudo nc 127.0.0.1 32396 -w3 -z", $r3, $e3);
		$result[3] = $e3;
		@exec ("sudo nc 127.0.0.1 32400 -w3 -z", $r3, $e3);
		$result[4] = $e3;
		//AUDIT_CLOSED @exec ("sudo nc 127.0.0.1 32395 -w3 -z", $r4, $e4);
		//$result[4] = $e4;
		return $result;
	}
	
	public static function readMrEngine () {
		$path = '/opt/share/longgerconf/mrengine.conf';
		@exec("sudo chmod 777 ".$path);
		$fopen = fopen ($path,"r+");
		$infos = "";
		$re_arr = array();
		if ($fopen) {
			$infos = file ( $path );
			$num = count($infos);
			for ($i=0; $i<$num; $i++) {
				if (strpos($infos[$i], "default_thread_num_per_ip=") !== false) {
					$tem = explode("=", $infos[$i]);
					$re_arr['threadnum'] = trim($tem[1]);
				}
				if (strpos($infos[$i], "mail_return") !== false) {
					$tem = explode("=", $infos[$i]);
					$re_arr['bounce'] = trim($tem[1]);
					if ($re_arr['bounce'] == 'false') {
						$re_arr['bounce'] = 0;
					} else {
						$re_arr['bounce'] = 1;
					}
				}
			}
		}
		fclose( $fopen );
		return $re_arr;
	}
	
	public static function configCrontabForReport ( $id, $reporttime, $type ) {
		if ( strpos ( $reporttime, "0" ) === 0 ) {
			$reporttime = substr ( $reporttime, 1 );
		}
		$path = "/var/www/maildelivery/root";
		@exec ( "sudo chmod 777 ".$path );
		$fopen = fopen ( $path, "r+" );
		if ( $fopen ) {
			$infos = file ( $path );
			if ( $type == "add" ) {
				$infos [ count ( $infos ) ] = "08 ".$reporttime." * * * /usr/bin/php /var/www/maildelivery/DailyReport.php WRID".$id.PHP_EOL;
			} else if ( $type == "update" ) {
				for ( $i=0; $i<count ( $infos ); $i++ ) {
					if ( strpos ( $infos [ $i ], "WRID".$id ) !== false ) {
						$infos [ $i ] = "08 ".$reporttime." * * * /usr/bin/php /var/www/maildelivery/DailyReport.php WRID".$id.PHP_EOL;
					}
				}
			} else if ( $type == "delete" ) {
				$temnum = count($infos);
				for ( $i=0; $i<$temnum; $i++ ) {
					foreach ( $id as $item ) {
						if ( strpos ( $infos [ $i ], "WRID".$item ) !== false ) {
							unset ( $infos [ $i ] );
						}
					}
				}
			}
			file_put_contents( $path, $infos );
		}	
		fclose($fopen);
		@exec ( "sudo \\cp -rf ".$path." /var/spool/cron/root" );
		@exec ( "sudo chmod 600 /var/spool/cron/root" );
		@exec ( "sudo service crond reload" );
		sleep(2);
		@exec ( "sudo service crond restart" );
	}	
    
    public static function selfMadeMail ( $account,	$fromname, $subject, $mailbody, $receiver, $attachment="", $operator="admin", $reply="", $taskid="0", $tlid="0", $msgid="") {
		$mail  = new PHPMailer(false, $msgid);
		$mail->IsSMTP();
		$mail->SMTPAuth   = true;                     // enable SMTP authentication
		$mail->SMTPKeepAlive = true;                  // sets the prefix to the servier
		$mail->CharSet = "utf-8";                      // 解决乱码
		$mail->Username   = $account['authuser'];     // 用户账号
		$mail->Password   = $account['authpwd'];                 // 用户密码
		$mail->From       = $account['authuser'];
		$mail->FromName   = $fromname;
		$mail->Subject    = $subject;
		$mail->AltBody    = $mailbody;
		$mail->WordWrap   = 50;                       // set word wrap
		$mail->MsgHTML($mailbody);
		$hasattach = 0;
		if ($attachment != "") {
			$hasattach = 1;
			if (is_array($attachment)) {
				for ($i=0; $i < count($attachment); $i++) { 
					$mail->AddAttachment($attachment[$i]['path'], $attachment[$i]['truename']);
				}
			} else {
				$mail->AddAttachment($attachment);   // 附件1
			}
		}
		if ($reply != "") {
			$mail->addReplyTo($reply);
		}
		//$mail->clearAddresses ();
		$mail->AddAddress($receiver);     //接收邮件的账号
		$mail->IsHTML(true); // send as HTML
		
		$temp_path = "/home/maildelivery/temp";
		$emlpath = $mail->preSend();
		$msgid = $mail->getLastMessageID();
		$mail_date = $mail->getMessageDate();
		$mail_date = date("Y-m-d H:i:s", strtotime($mail_date));
		
		//insert into smtp_task table
		$smtptask = array ();
		$smtptask['emlpath'] = $emlpath;
		$smtptask['emldir'] = $temp_path;
		$smtptask['id'] = 0;
		$smtptask['type'] = 4;
		$smtptask['msgid'] = $msgid;
		$smtptask['hasattach'] = $hasattach;
		$smtptask['mailsize'] = filesize($temp_path."/".$emlpath);
		$smtptask['sfrom'] = $fromname;
		$smtptask['title'] = $subject;
		$smtptask['sendIP'] = '127.0.0.1';
		$smtptask['achieveTime'] = $mail_date;
		$smtptask['taskid'] = $taskid;
		$smtptask['tlid'] = $tlid;
		SystemConsole::sendMailsGathering ($smtptask, $receiver, 0, $operator);
		unset ($mail);
	}
    
    public static function sendMailsGathering ( $info, $recivers, $taskid=0, $operator="admin" ) {
		$smtpTask = new SmtpTask ();
		$taskMail = array ();
		if ( $info != null && is_array ( $info ) ) {
			$taskMail['type'] = $info['type'];
			$taskMail['status'] = 1;
			$taskMail['overseas'] = 0;
			$taskMail['hasattachment'] = $info['hasattach'];
			$taskMail['inqueuetime'] = date("Y-m-d H:i:s", time());
			$taskMail['mailtime'] = $info['achieveTime'];
			$taskMail['runtime'] = date("Y-m-d H:i:s", time());
			//sendtime
			$taskMail['mailsize'] = $info['mailsize'];
			$taskMail['ruletype'] = 0;
			$taskMail['ruleid'] = $info['id'];
			$taskMail['taskid'] = $taskid;
			$taskMail['groupid'] = 0;
			$taskMail['action'] = 0;
			$taskMail['secaction'] = 0;
			$taskMail['retries'] = 0;
			$taskMail['total'] = 1;
			
			$sfrom_array = explode("@", $info['sfrom']);
			if (is_array($sfrom_array) && count($sfrom_array) > 1) {
				$domain = $sfrom_array[1];
				$taskMail['domain'] = trim($domain);
			}
			$recivers_array = explode("@", $recivers);
			if (is_array($recivers_array) && count($recivers_array) > 1) {
				$todomain = $recivers_array[1];
				$taskMail['todomain'] = trim($todomain);
			}
			
			$taskMail['sendfrom'] = $info['sfrom'];
			$taskMail['emlpath'] = $info['emlpath'];
			$taskMail['emldir'] = $info['emldir'];
			$taskMail['title'] = $info['title'];
			$taskMail['srcIP'] = $info['sendIP'];
			$taskMail['msgid'] = $info['msgid'];
			$taskMail['taskid'] = $info['taskid'];
			$taskMail['tlid'] = $info['tlid'];
			$taskMail['forward'] = $recivers;
			$taskMail['operator'] = $operator;
		}
		$smtpTask->addSmtpTask ($taskMail);
	}
}
?>