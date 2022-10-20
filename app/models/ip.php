<?php
/*--------------------------------------------------
	ip2address 
--------------------------------------------------*/

class ip {

	//获取省份
	function getProvince($area) {
		switch($area){
			case 0: 
				$province['area'] = '其他';
				$province['cha'] = 'QT';
			break;
			case 1: 
				$province['area'] = '北京市';
				$province['cha'] = 'BEJ';
			break;
			case 2: 
				$province['area'] = '天津市';
				$province['cha'] = 'TAJ';
			break;
			case 3: 
				$province['area'] = '上海市';
				$province['cha'] = 'SHH';
			break;
			case 4: 
				$province['area'] = '重庆市';
				$province['cha'] = 'CHQ';
			break;
			case 5: 
				$province['area'] = '河北省';
				$province['cha'] = 'HEB';
			break;
			case 6: 
				$province['area'] = '河南省';
				$province['cha'] = 'HEN';
			break;
			case 7: 
				$province['area'] = '云南省';
				$province['cha'] = 'YUN';
			break;
			case 8: 
				$province['area'] = '辽宁省';
				$province['cha'] = 'LIA';
			break;
			case 9: 
				$province['area'] = '黑龙江省';
				$province['cha'] = 'HLJ';
			break;
			case 10: 
				$province['area']= '湖南省';
				$province['cha'] = 'HUN';
			break;
			case 11: 
				$province['area'] = '安徽省';
				$province['cha'] = 'ANH';
			break;
			case 12: 
				$province['area'] = '山东省';
				$province['cha'] = 'SHD';
			break;
			case 13: 
				$province['area'] = '新疆';
				$province['cha'] = 'XIN';
			break;
			case 14: 
				$province['area'] = '江苏省';
				$province['cha'] = 'JSU';
			break;
			case 15: 
				$province['area'] = '浙江省';
				$province['cha'] = 'ZHJ';
			break;
			case 16: 
				$province['area'] = '江西省';
				$province['cha'] = 'JXI';
			break;
			case 17: 
				$province['area'] = '湖北省';
				$province['cha'] = 'HUB';
			break;
			case 18: 
				$province['area'] = '广西';
				$province['cha'] = 'GXI';
			break;
			case 19: 
				$province ['area']= '甘肃省';
				$province['cha'] = 'GAN';
			break;
			case 20: 
				$province['area'] = '山西省';
				$province['cha'] = 'SHX';
			break;
			case 21: 
				$province['area'] = '内蒙古';
				$province['cha'] = 'NMG';
			break;
			case 22: 
				$province['area'] = '陕西省';
				$province['cha'] = 'SHA';
			break;
			case 23: 
				$province['area'] = '吉林省';
				$province['cha'] = 'JIL';
			break;
			case 24: 
				$province['area'] = '福建省';
				$province['cha'] = 'FUJ';
			break;
			case 25: 
				$province['area'] = '贵州省';
				$province['cha'] = 'GUI';
			break;
			case 26: 
				$province['area'] = '广东省';
				$province['cha'] = 'GUD';
			break;
			case 27: 
				$province['area'] = '青海省';
				$province['cha'] = 'QIH';
			break;
			case 28: 
				$province['area'] = '西藏';
				$province['cha'] = 'TIB';
			break;
			case 29: 
				$province['area'] = '四川省';
				$province['cha'] = 'SCH';
			break;
			case 30: 
				$province['area'] = '宁夏';
				$province['cha'] = 'NXA';
			break;
			case 31: 
				$province['area'] = '海南省';
				$province['cha'] = 'HAI';
			break;
			case 32: 
				$province['area'] = '台湾省';
				$province['cha'] = 'TAI';
			break;
			case 33: 
				$province['area'] = '香港';
				$province['cha'] = 'HKG';
			break;
			case 34: 
				$province['area'] = '澳门';
				$province['cha'] = 'MAC';
			break;
			default: 
				$province['area'] = '未知或错误';
				$province['cha'] = 'WEZ';
			break; 
		}		
		return $province;
	}


	function ip2addr($ip) {
		// $location['country'] = $this->getProvince();
		$ipAddr= $ip;
		$ipInfoApi= 'http://ip.taobao.com/service/getIpInfo.php?ip='.$ipAddr;
		$areaInfo= file_get_contents($ipInfoApi);
		$areaInfo= json_decode($areaInfo);
		$location['total']=$areaInfo->data;
					
		return $location;
	}


     function getBrowse(){
		    global $_SERVER;
		    $browser = $_SERVER['HTTP_USER_AGENT']; 
		   	// echo "<pre>"; 
		    // print_r($browser);
		    // echo "</pre>";
		    if (preg_match('/msie/i',$browser) || preg_match('/rv:11.0/i',$browser)) { 
            $browser = 'MSIE'; 
	        } 
	        elseif (preg_match('/firefox/i',$browser)) { 
	            $browser = 'Firefox'; 
	        } 
	        elseif (preg_match('/chrome/i',$browser)) { 
	            $browser = 'Chrome'; 
	        } 
	        elseif (preg_match('/safari/i',$browser)) { 
	            $browser = 'Safari'; 
	        } 
	        elseif (preg_match('/opera/i',$browser)) { 
	            $browser = 'Opera'; 
	        }
	        elseif (preg_match('/Mozilla/', $browser) && !preg_match('/MSIE/', $browser)) {
    			$temp = explode('(', $browser); 
		        $Part = $temp[0];
		        $temp = explode('/', $Part);
		        $browserver = $temp[1];
		        $temp = explode(' ', $browserver); 
		        $browserver = $temp[0];
		        $browserver = preg_replace('/([d.]+)/', '1', $browserver);
		        $browserver = $browserver;
		        $browser = 'Netscape Navigator'.$browserver; 
	        }
	        else { 
	            $browser = 'Other'; 
	        } 

	        return $browser; 
   		  
     }


     function getIP (){
		     global $_SERVER;
		     if (getenv('HTTP_CLIENT_IP')) {
		         $ip = getenv('HTTP_CLIENT_IP');
		     } else if (getenv('HTTP_X_FORWARDED_FOR')) {
		         $ip = getenv('HTTP_X_FORWARDED_FOR'); 
		     } else if (getenv('REMOTE_ADDR')) {
		         $ip = getenv('REMOTE_ADDR');
		     } else {
		         $ip = $_SERVER['REMOTE_ADDR'];
		     }

   		  return $ip; 
     }


     function getOS (){
			     global $_SERVER;
			     $agent = $_SERVER['HTTP_USER_AGENT'];
			     $os = false;
				 if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent)){ 
			         $os = 'Windows XP';
			     }
				 else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent)){ 
			         $os = 'Windows 7';
			     }
				 else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent)){ 
			         $os = 'Windows 8';
			     }
			     else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent)){
			         $os = 'Windows NT';
			     }
			     else if (preg_match('/linux/i', $agent)){
			         $os = 'Linux';
			     }
			     else if (preg_match('/unix/i', $agent)){ 
			         $os = 'Unix';
			     }
				 else if (preg_match('/ipad/i',$agent)){
					 $os = "Ipad";
				 }
				 else if (preg_match('/android/i',$agent)){
					 $os = "Android";
				 }
				 else if (preg_match('/iphone\s*os/i',$agent)){
					 $os = "Iphone";
				}
			     else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent)){ 
			         $os = 'Macintosh';
			     }
			     else {
			         $os = 'Unknown';
			     }

   				  return $os;

     }



}

// $ip = new ip('','');
// $addr = $ip -> ip2addr();
// print_r($addr);
