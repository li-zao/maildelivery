<?php
class Alertsetting extends Common
{
	protected $_name = "alertsetting";
	protected $_primary = 'id';
	
	/*
	 * Get All  Infos
	 */
	public function getAllInfos() {
		$db = $this->getAdapter ();
		$sql = "select * from mr_alertsetting where 1=1 order by id desc";
		$stmt = $db->query ( $sql );
		$info = $stmt->fetchAll();
        if (!empty($info[0])) {
            return $info[0];
        } else {
            return array();
        }
	}
	
	public function insertAlertSetting ($data) {
		return $this->insert($data);
	}
	
	public function updateAlertSetting ($data, $id) {
		$db = $this->getAdapter();
		$where = $db->quoteInto('id = ?', $id);
		return $this->update($data, $where);
	}
}
?>