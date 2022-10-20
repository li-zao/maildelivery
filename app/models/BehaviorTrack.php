<?php
class BehaviorTrack {
	
	public static function addBehaviorLog($uname, $role, $userid, $subject, $description, $subject_en, $description_en, $ip) {
		$data = array();
		$data['operator'] = $uname;
		$data['subject'] = $subject;
		$data['userid'] = $userid;
		$data['description'] = $description;
		$data['subject_en'] = $subject_en;
		$data['description_en'] = $description_en;
		$data['ip'] = $ip;
		$data['userrole'] = $role;
		$accesslog_db = new AccessLog();
		$res = $accesslog_db->addLog($data);
	}
}
?>