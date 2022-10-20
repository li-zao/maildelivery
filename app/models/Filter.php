<?php
class Filter extends Common
{
	protected $_name = "filter";
	protected $_primary = 'id';
	
	/*
	 * Get all filter info
	 */
	public function getAllFilter($uid) {
		 $db = $this->getAdapter ();
		 $sql="select id,name from `mr_filter` where uid=".$uid." order by id desc";
		 $stmt = $db->query ( $sql );
		 $array = $stmt->fetchAll();
		 return $array;
	}	
	
	public function selectAllFilter($where) {
		 $db = $this->getAdapter ();
		 $sql="select id,name from `mr_filter` where ".$where." order by id desc";
		 $stmt = $db->query ( $sql );
		 $array = $stmt->fetchAll();
		 return $array;
	}
	
	public function getOnefilter($fid) {
		 $db = $this->getAdapter ();
		 $sql = "select * from `mr_filter` where id =".$fid;
		 $stmt = $db->query ( $sql );
		 $array = $stmt->fetchAll();
		 return $array;
	}
	
	
}
?>