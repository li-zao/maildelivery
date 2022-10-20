<?php
class Account extends Common
{
	protected $_name = "accounts";
	protected $_primary = 'id';
	
	/*
	 * Get all users
	 */
	public function getAllUsers() {
		$db = $this->getAdapter ();
		$sql = "select * from mr_accounts where 1=1 order by id desc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getSAdminUsers() {
		$db = $this->getAdapter ();
		$sql = "select * from mr_accounts where 1=1 and id='".$id."' or userrole='sadmin' or userrole='admin' order by id desc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getSTaskerUsers($id) {
		$db = $this->getAdapter ();
		//$sql = "select * from mr_accounts where 1=1 and id='".$id."' or parentid ='".$id."' order by id desc";
		$sql = "select * from mr_accounts where 1=1 and userrole = 'stasker' or userrole = 'tasker' order by id desc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getTaskerUsers($id) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_accounts where 1=1 and id='".$id."'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function getAdminUsers($id) {
		$db = $this->getAdapter ();
		//$sql = "select * from mr_accounts where 1=1 and id='".$id."' or userrole='stasker' or userrole='tasker' order by id desc";
		$sql = "select * from mr_accounts where 1=1 and id='".$id."' order by id desc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function insertAccount ($data) {
		return $this->insert($data);
	}
	
	public function getAccountInfoByID ($id) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_accounts where 1=1 and id='".$id."'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0];
	}
	
	public function getAccountInfoByMail ($mail, $uname) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_accounts where 1=1 and mail='".$mail."' and username='".$uname."'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0];
	}
	
	public function delAccount ($id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->delete($where);
	}
	
	public function updateLastAccess($id, $cur_time) {
		$db = $this->getAdapter ();
		$sql = "UPDATE mr_accounts SET lastaccess = '".$cur_time."' WHERE id = '".$id."'";
		$stmt = $db->query ( $sql );
	}
	
	public function checkUserName($uname) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_accounts where 1=1 and username='".$uname."'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0];
	}
	
	public function checkUserMail($mail) {
		$db = $this->getAdapter ();
		$sql = "select * from mr_accounts where 1=1 and mail='".$mail."'";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0];
	}
	
	public function updateAccount ($data, $id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->update($data, $where);
	}
	
	public function getAllCommonUsers () {
		$db = $this->getAdapter ();
		$sql = "select * from mr_accounts where 1=1 and usertype=1 order by id desc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function checkAccountName($name, $checkwhat='') {
		$db = $this->getAdapter ();
		$sql = "SELECT * FROM mr_accounts where username='".$name."'";
		$stmt = $db->query ( $sql );
		$accounts = $stmt->fetchAll();
		if (count($accounts) > 0) {
			if ($checkwhat == 'checklock') {
				if ($accounts[0]['islock'] == 1) {
					return true;
				} else {
					return false;
				}
			}
			return false;
		} else {
			return true;
		}
	}
	
	public function getLoginsByName ($name) {
		$db = $this->getAdapter ();
		$sql = "SELECT id,username,userrole,logins,lastaccess FROM mr_accounts where username = '".$name."' ";
		$stmt = $db->query ( $sql );
		$accounts = $stmt->fetchAll();
		return $accounts[0];
	}

	public function updateLoginsById($id, $logins, $lock) {
		$db = $this->getAdapter ();
		$sql = "UPDATE mr_accounts SET logins = '".$logins."',`islock` = '".$lock."' WHERE id = '".$id."'";
		$stmt = $db->query ( $sql );
	}
}
?>