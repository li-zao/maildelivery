<?php
class TrustipTable extends Common
{
	protected $_name = "trustiptable";
	protected $_primary = 'id';
	
	/*
	 * Get all users
	 */
	public function getAllInfos($sqls="", $con) {
		$db = $this->getAdapter ();
		if ( $sqls == "" ) {
			$sql = "select * from ".$this->_name." where 1=1 ".$con;
		} else {
			$sql = "select * from ".$this->_name." where 1=1 ".$sqls." ".$con;
		}
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getInfoByRTID($id) {
		$db = $this->getAdapter ();
		$sql = "select * from ".$this->_name." where 1=1 and id='".$id."'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getInfoByIP ( $ip, $uid ) {
		$db = $this->getAdapter ();
		$sql = "select id from ".$this->_name." where 1=1 and ips='".$ip."' and belong != '".$uid."'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getAllCount ( $sqls="" ) {
		$db = $this->getAdapter ();
		$sql = "select count(id) from ".$this->_name." where 1=1 ".$sqls;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0]['count(id)'];
	}
	
	public function checkInfoByIP ( $ip ) {
		$db = $this->getAdapter ();
		$sql = "select ips from ".$this->_name." where 1=1 and ips='".$ip."'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function updateRT($data, $id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->update($data, $where);
	}
	
	public function insertRT ($data) {
		return $this->insert($data);
	}
	
	public function delRT ($id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->delete($where);
	}
	
	public function getInfoByBelong ($belong) {
		if (!is_numeric($belong)) {
			$belong = 0;
		}
		$db = $this->getAdapter ();
		$sql = "select * from ".$this->_name." where 1=1 and belong=".$belong;
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function clearDesignated ( $id ) {
		if (!is_numeric($id)) {
			$id = 0;
		}
		$db = $this->getAdapter ();
		$sql = "delete from ".$this->_name." where 1=1 and belong=".$id;
		$stmt = $db->query ( $sql );
	}
	
	public function changeBelong ( $sql, $dest_uname, $dest_uid ) {
		$db = $this->getAdapter ();
		$sql = "update ".$this->_name." set belong='".$dest_uid."', uname='".$dest_uname."' where 1=1 and id in (".$sql.")";
		$stmt = $db->query ( $sql );
	}
	
	public function getGroupByCon ( $con, $id="" ) {
		$db = $this->getAdapter ();
		if ( $id == "" ) {
			$sql = "select ".$con." from mr_trustiptable group by ".$con;
		} else {
			$sql = "select ".$con." from mr_trustiptable where 1=1 and belong='".$id."' group by ".$con;
		}
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll ();
		return $info;
	}
}
?>