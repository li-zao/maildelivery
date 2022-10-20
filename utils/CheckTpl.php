<?php 

	// if( empty($argv[1])){
	// 	echo "\r\nThe path is invalid, please input the correct path\r\n";
	// 	}
	// $path = $argv[1]; 

	/*connect SQL*/	
	$username = "maildata";
	$password =  "maildata136";
	// $dbhost = "localhost";
	$dbhost = "192.168.10.114";
	$dbdatabases = 'maildelivery';

	//read files
	// $path="D:/AppServ/www/V1.1/web/dist/thumb_images/";
	$path="/var/www/maildelivery/dist/thumb_images/";  //Linux
	$array=scandir($path);

	//连接数据库
	$db_connect=mysql_connect($dbhost,$username,$password) or die ('Unable to connect to the MySQL!');
	mysql_select_db($dbdatabases,$db_connect);
	mysql_query("SET NAMES utf8");
	$sql="SELECT id,tpl_img,tpl_body FROM md_template ";
	$row=mysql_query($sql,$db_connect);
	$i=0;
	$y=0;
	$z=0;
	while($resoult=mysql_fetch_assoc($row)){
		$arr[]=$resoult['tpl_img'];
		if(!in_array($resoult['tpl_img'],$array) || $resoult['tpl_img']==" "){
					$file=@fopen($path."thumb.html","w+b");
					$nowfile=fwrite($file, $resoult['tpl_body']);
					@fclose($file);
					$url = $path.'thumb.html';
				  //输出图片的位置与名称
					if($resoult['tpl_img']){
					 	$out = $path.$resoult['tpl_img'];
					}else{
						$newname = mt_rand(1,9999).time().".png";
						$out = $path.$newname;
					}
  					$cmd="sudo /usr/bin/xvfb-run --server-args=\"-screen 0, 800x600x24\" /opt/bin/CutyCapt --url=file:".$url." --out=".$out." --max-wait=5000";
				   //Linux
				    if(exec($cmd)){
					    $linuxs='sudo convert -resize 13%x18% '.$out." ".$out;
					   	@exec($linuxs);
					   	$i++;	
				    }else{
				    	$y++;
				    }
			   		
		}else{
			$z++;
		}

	}
	print_r($arr);
	$Num=count($arr);	
	if($z==$Num){
		echo "No Error\n";	
	}else{
		echo "Total ".$i." Successful execution\n";
		echo "Total ".$y." Not implemented";	
	}
	
		
		
