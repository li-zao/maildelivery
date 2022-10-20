<?php
class Console extends Common
{
	protected $_name = "console";
	protected $_primary = 'id';
	
	/*
	 * Get all users
	 */
	public function getAllInfos() {
		$db = $this->getAdapter ();
		$sql = "select * from mr_console where 1=1 order by id desc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
		return $info;
	}
	
	public function updateConsole($data) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $data['id']);
		return $this->update($data, $where);
	}
}
?>