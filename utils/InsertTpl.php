#!/usr/local/bin/php -q
<?php 
	if( empty($argv[2])){
		exit("\r\nThe Server-name is invalid, please input the correct server-name\r\n");
		}
	$path = $argv[1]; 
	$path_other = $argv[2];   //server-name


	$username = "longger";
	$password =  "longger136";
	$dbhost = "localhost";
	$dbdatabases = 'mailrelay';

	//连接数据库
	$db_connect=mysql_connect($dbhost,$username,$password) or die ('Unable to connect to the MySQL!');
	mysql_select_db($dbdatabases,$db_connect);
	mysql_query("SET NAMES utf8");
	//thumb paths
	$thumb_paths="/var/www/maildelivery/dist/thumb_images/";
			$newdirnames=scandir($path);
			$i=1;
			foreach($newdirnames as $vals){
				if($vals!="." &&  $vals!=".."){
								$tpl_name="预设模板".$i;
								$paths=$path."/".$vals;
							  //输出图片的位置与名称
								$newname=mt_rand(1,9999).time().".png";
								$temfile=@fopen($paths,'rb');		
								if($temfile){
									$tpl_body="";
									while(!feof($temfile)){
											$tpl_body.=fgets($temfile);
									}
									@fclose($temfile);
									$tpl_body =  str_replace("\$domainurl\$",$path_other,$tpl_body);
									$file=@fopen($thumb_paths."thumb.html","w+b");
									$nowfile=fwrite($file, $tpl_body);
									@fclose($file);
									$tpl_body = addslashes($tpl_body);
									$sql="insert into mr_template(tpl_name,tpl_body,tpl_img,tpl_style) values('".$tpl_name."','".$tpl_body."','".$newname."','-1')";
								 mysql_query($sql);

								
								$url = $thumb_paths.'thumb.html';
								$out = $thumb_paths.$newname;
								if ( !is_file($out) ){
									exec('sudo touch '.$out);
									exec('sudo chmod 777 '.$out);
								}
			  					$cmd="sudo /usr/bin/xvfb-run --server-args=\"-screen 0, 800x600x24\" /opt/bin/CutyCapt --url=file:".$url." --out=".$out." --max-wait=5000";
							    exec($cmd);
						   		$linuxs='sudo convert -resize 13%x18% '.$out." ".$out;
						   		@exec($linuxs,$outhtml);
								$i++;
								echo $vals." Success!\n";
								}
				}
			}
			echo "Total ".($i-1)." records";
			mysql_close(); 

