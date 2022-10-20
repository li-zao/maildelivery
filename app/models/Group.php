<?php
class Group extends Common
{
	protected $_name = "group";
	protected $_primary = 'id';
	
	/*
	 * Get all group info
	 */
	public function getAllGroupInfo($where) {
		 $db = $this->getAdapter ();
		 $sql="select * from mr_group where ".$where." order by createtime desc";
		 $stmt = $db->query ( $sql );
		 $array = $stmt->fetchAll();
		 return $array;
	}
	
	public function getCountfilterGroup($tablename,$w) {
		 $db = $this->getAdapter ();
		 $sql = "select count(id) as num from `".$tablename."` ".$w."";
		 $array = $db->fetchOne ( $sql );
		 return $array;
	}
	
	public function getAllCountperson($table) {
		$db = $this->getAdapter ();
		$sql="select count(id) as num from `".$table."`";
		$array = $db->fetchRow ( $sql );
		//$array = $stmt->fetchOne();
		return $array;
	}
	
	public function getSearchgroup($condition) {
		 $db = $this->getAdapter ();
		 $sql="select * from mr_group where ".$condition;
		 $stmt = $db->query ( $sql );
		 $array = $stmt->fetchAll();
		 return $array;
	}

	public function getAllGidTname() {
		$db = $this->getAdapter ();
		$sql="select id,tablename from mr_group";
		$stmt = $db->query ( $sql );
		$array = $stmt->fetchAll();
		return $array;
	}
}
?>