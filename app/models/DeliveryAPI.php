<?php
class DeliveryAPI {
	
	public static function SendCtrlCmd($ip, $port, $cmd, $data) {
		$sock = socket_create ( AF_INET, SOCK_STREAM, SOL_TCP );
		socket_set_nonblock ( $sock );
		$sock_return = socket_connect ( $sock, $ip, $port );
		socket_set_option ( $sock, SOL_SOCKET, SO_RCVTIMEO, array ("sec" => 2, "usec" => 0 ) );
		socket_set_option ( $sock, SOL_SOCKET, SO_SNDTIMEO, array ("sec" => 2, "usec" => 0 ) );
		if (! $sock_return) {
			socket_set_block ( $sock );
			$read = array ($sock );
			$write = array ($sock );
			$f = NULL;
			switch (socket_select ( $read, $write, $f, 5 )) {
				case 2 :
					return NULL;
				case 1 :
					break;
				case 0 :
					return NULL;
				default :
					return NULL;
			}
		} else {
			socket_set_block ( $sock );
			return NULL;
		}
		// send cmd type
		if (socket_write ( $sock, $cmd, 1 ) != 1) {
			socket_shutdown ( $sock );
			return NULL;
		}
		
		// send cmd len
		$cmr_len = strlen ( $data );
		$cmr_len_str = pack ( 'V', $cmr_len );
		
		$sent = socket_write ( $sock, $cmr_len_str, 4 );
		if ($sent === FALSE) {
			socket_shutdown ( $sock );
			return NULL;
		}
		while ( $sent != 4 ) {
			$cmr_len_str = substr ( $cmr_len_str, $sent );
			$add = socket_write ( $sock, $cmr_len_str, 4 - $sent );
			if ($add === FALSE) {
				socket_shutdown ( $sock );
				return NULL;
			}
			$sent += $add;
		}
		
		// send data
		$sent = socket_write ( $sock, $data );
		while ( $cmr_len > $sent ) {
			if ($sent === FALSE) {
				socket_shutdown ( $sock );
				return NULL;
			}
			$data = substr ( $data, $sent );
			$cmr_len -= $sent;
		}
		
		// get response length
		$resp_len_str = socket_read ( $sock, 4 );
		$resp_len = unpack ( 'V', $resp_len_str );
		$resp_len = $resp_len [1];
		// get response string
		$resp = socket_read ( $sock, $resp_len );
		while ( strlen ( $resp ) < $resp_len ) {
			$add = socket_read ( $sock, $resp_len - strlen ( $resp ) );
			if ($add === FALSE) {
				socket_shutdown ( $sock );
				return NULL;
			}
			$resp = $resp . $add;
		}
		socket_shutdown ( $sock );
		
		return $resp;
	}
	public static function RestartApache($ip, $port) {
		$resp = DeliveryAPI::SendCtrlCmd ( $ip, $port, "r", NULL );
		if ($resp == false || $resp == NULL)
			return NULL;
		return $resp != NULL && $resp != - 1;
	}
	//新增数据库服务
	public static function RestartMysqldService($ip, $port) {
		$resp = DeliveryAPI::SendCtrlCmd ( $ip, $port, "m", NULL );
		if ($resp == false || $resp == NULL)
			return NULL;
		return $resp != NULL && $resp != - 1;
	}
	//新增SMTP服务
	public static function RestartSmtpService($ip, $port) {
		$resp = DeliveryAPI::SendCtrlCmd ( $ip, $port, "p", NULL );
		if ($resp == false || $resp == NULL)
			return NULL;
		return $resp != NULL && $resp != - 1;
	}
	//新增信任服务
	public static function RestartAuthService($ip, $port) {
		$resp = DeliveryAPI::SendCtrlCmd ( $ip, $port, "a", NULL );
		if ($resp == false || $resp == NULL)
			return NULL;
		return $resp != NULL && $resp != - 1;
	}
	//新增转换服务
	public static function RestartConversionService($ip, $port) {
		$resp = DeliveryAPI::SendCtrlCmd ( $ip, $port, "j", NULL );
		if ($resp == false || $resp == NULL)
			return NULL;
		return $resp != NULL && $resp != - 1;
	}
	//新增用户重启邮件过滤服务。
	public static function RestartFilterService($ip, $port) {
		$resp = DeliveryAPI::SendCtrlCmd ( $ip, $port, "f", NULL );
		if ($resp == false || $resp == NULL)
			return NULL;
		return $resp != NULL && $resp != - 1;
	}
	
	public static function RestartMDService($ip, $port) {
		$resp = DeliveryAPI::SendCtrlCmd ( $ip, $port, "z", NULL );
		if ($resp == false || $resp == NULL)
			return NULL;
		return $resp != NULL && $resp != - 1;
	}
	
	public static function GetLicenseInfo($ip, $port) {
		$resp = DeliveryAPI::SendCtrlCmd ( $ip, $port, "l", NULL );
		if ($resp == false || $resp == NULL)
			return NULL;
		$info = array ();
		$temp = substr($resp, 0, 4);
		$cnt = unpack ( 'V', $temp );
		$info['store_ok'] = $cnt [1];
		$temp = substr($resp, 4, 4);
		$cnt = unpack ( 'V', $temp );
		$info['overseas_ok'] = $cnt [1];		
		$temp = substr($resp, 8, 4);
		$cnt = unpack ( 'V', $temp );
		$info['usernumber'] = $cnt [1];
		$temp = substr($resp, 12, 4);
		$cnt = unpack ( 'V', $temp );
		$info['start_year'] = $cnt [1];		
		$temp = substr($resp, 16, 4);
		$cnt = unpack ( 'V', $temp );
		$info['start_month'] = $cnt [1];
		$temp = substr($resp, 20, 4);
		$cnt = unpack ( 'V', $temp );
		$info['start_date'] = $cnt [1];
		$temp = substr($resp, 24, 4);
		$cnt = unpack ( 'V', $temp );
		$info['end_year'] = $cnt [1];
		$temp = substr($resp, 28, 4);
		$cnt = unpack ( 'V', $temp );
		$info['end_month'] = $cnt [1];
		$temp = substr($resp, 32, 4);
		$cnt = unpack ( 'V', $temp );
		$info['end_date'] = $cnt [1];
		$temp = substr($resp, 36, 4);
		$cnt = unpack ( 'V', $temp );
		$info['overseas_relay_start_year'] = $cnt [1];
		$temp = substr($resp, 40, 4);
		$cnt = unpack ( 'V', $temp );
		$info['overseas_relay_start_month'] = $cnt [1];
		$temp = substr($resp, 44, 4);
		$cnt = unpack ( 'V', $temp );
		$info['overseas_relay_start_date'] = $cnt [1];
		$temp = substr($resp, 48, 4);
		$cnt = unpack ( 'V', $temp );
		$info['overseas_relay_end_year'] = $cnt [1];
		$temp = substr($resp, 52, 4);
		$cnt = unpack ( 'V', $temp );
		$info['overseas_relay_end_month'] = $cnt [1];
		$temp = substr($resp, 56, 4);
		$cnt = unpack ( 'V', $temp );
		$info['overseas_relay_end_date'] = $cnt [1];
		return $info;
	}
	
	public static function ReloadSpfCfg($ip, $port) {
		$resp = DeliveryAPI::SendCtrlCmd ( $ip, $port, "c", NULL );
		if ($resp == false || $resp == NULL)
			return NULL;
		return $resp != NULL && $resp != - 1;
	}
}

?>
