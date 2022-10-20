<?php
class SecurityParam extends Common {
	protected $_name = "securityparam";
	protected $_primary = 'id';
	
	public function getSecurityParam() {
		$db = $this->getAdapter ();
		$sql = "SELECT * FROM ".$this->_name;
		$stmt = $db->query ( $sql );
		$domains = $stmt->fetchAll();
		return $domains;
	}
	
	public function addSecurityParam($data) {
		return $this->insert($data);
	}
	
	public function updateSecurityParam($data, $id) {
		$data_id = $id;
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $data_id);
		return $this->update($data, $where);
	}
}
?>