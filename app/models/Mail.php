<?php
class Mail extends Common {
	protected $_name = "smtp_task";
	protected $_primary = 'id';
	
	public function execSqlForMail($sql) {
		$db = $this->getAdapter ();
		$stmt = $db->query ( $sql );
		return $stmt;
	}
}
?>