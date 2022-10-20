<?php
class CPUStat extends Common {
	protected $_name = "cpu_stats";
	protected $_primary = 'id';
	
	public function getStats ( $ip, $starttime, $endtime, $order = "") {
		$db = $this->getAdapter ();
		if ( $order == "" || $order == null) {
			$sql = "SELECT * FROM mr_cpu_stats where deviceIP='".$ip."' and duration>='".$starttime."' and duration<='".$endtime."' order by duration DESC";
		} else {
			$sql = "SELECT * FROM mr_cpu_stats where deviceIP='".$ip."' and duration>='".$starttime."' and duration<='".$endtime."'";
		}
		$stmt = $db->query ( $sql );
		$stats = $stmt->fetchAll();
		return $stats;
	}
	
	public function addStats ( $data ) {
		return $this->insert ( $data );
	}
}
?>