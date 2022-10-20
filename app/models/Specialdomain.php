<?php
class Specialdomain extends Common
{
	protected $_name = "specialdomain";
	protected $_primary = 'id';
	
	/*
	 * Get all users
	 */
	public function getAllInfos() {
		$db = $this->getAdapter ();
		$sql = "select * from mr_specialdomain where 1=1 order by id desc limit 1";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info[0];
	}
	
	public function updateSpecialDomain($data, $gpid) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $gpid);
		return $this->update($data, $where);
	}
	
	public function insertSpecialDomain ($data) {
		return $this->insert($data);
	}
}
?>