<?php
class CounterModel extends Common
{
	protected $_name = "counter";
	protected $_primary = 'id';
	
	public function fetchOSTotal() {
		$db = $this->getAdapter ();
		$sql = "select ostotal from " . $this->_name . " limit 1";
		$stmt = $db->query($sql);
		$info = $stmt->fetchAll();
		return is_numeric($info[0]['ostotal']) ? $info[0]['ostotal'] : 0;
	}
	
}