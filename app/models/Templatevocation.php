<?php 
	class Templatevocation extends Common{
		protected $_name="template_vocation";
		protected $_primary="id";

		public function  sendInsertOne($arr){
				$rows=$this->insert($arr);
				return $rows;
		}
		/*更新模板行业风格*/
		public function updateTV($tid,$vid){
			$db=$this->getAdapter();
			$table="mr_template_vocation";
			$set=array('vid'=>$vid);
			$where="tid=".$tid;
			$row=$db->update($table,$set,$where);
			return $row;
		}
		public function deltvOne($tid){
			$where="tid=".$tid;	
			$db=$this->getAdapter();
			$sql="DELETE FROM mr_template_vocation WHERE tid=".$tid;
			// echo $sql;
			// $row=$this->getAdapter->delete("mr_template_vocation",$where);
			$row=$db->query($sql);
			return $row;
		}
	}