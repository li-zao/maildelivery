<?php
class Sendmail extends Common
{
	protected $_name = "sendmail";
	protected $_primary = 'id';
	
	/*
	 * Get all users
	 */
	public function getAllInfos() {
		$db = $this->getAdapter ();
		$sql = "select * from mr_sendmail where 1=1 order by id desc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function updateSMTPServer($data, $smtpid) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $smtpid);
		return $this->update($data, $where);
	}
	
	public function insertSMTPServer ($data) {
		return $this->insert($data);
	}
	
}
?>