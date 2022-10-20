<?php
class Ntp extends Common
{
	protected $_name = "ntp";
	protected $_primary = 'id';
	
	/*
	 * Get all users
	 */
	public function getAllInfos() {
		$db = $this->getAdapter ();
		$sql = "select * from mr_ntp where 1=1 order by id desc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function updateNtp($data, $id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->update($data, $where);
	}
	
	public function insertNtp ($data) {
		return $this->insert($data);
	}
}
?>