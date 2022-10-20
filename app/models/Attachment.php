<?php 
	class Attachment extends Common{
		protected $_name="attachment";
		protected $_primary="id";

		public function InsertOne($arrs){
				$rows=$this->insert($arrs);
				return $rows;
		}

		public function delOne($id){
			$where="id=".$id;	
			$rows=$this->delete($where);
			return $rows;
		}

		public function selOne($id){
			$row=$this->find($id);
			$res=$row->current();
			return $res;
		}

		public function SelAllattach($uid){
			$db=$this->getAdapter();
			$sql="select id,tid from mr_attachment where uid=".$uid;
			$rows=$db->query($sql);
			$resoult=$rows->fetchAll();
			return $resoult;
		}

		public function selAlltaskattach($uid){
			$db=$this->getAdapter();
			$sql="select * from mr_attachment where uid=".$uid." group by truename order by createtime desc";
			$rows=$db->query($sql);
			$resoult=$rows->fetchAll();
			return $resoult;
		}
		
		public function seltaskattach($tid,$uid){
			$db=$this->getAdapter();
			$sql="select id,truename,path from `mr_attachment` where tid = ".$tid." and uid = ".$uid."";
			$rows=$db->query($sql);
			$resoult=$rows->fetchAll();
			return $resoult;
		}
		
		public function searchTaskattach($where){
			$db=$this->getAdapter();
			$sql="select id,path,truename,aliasname,createtime from mr_attachment WHERE ".$where." group by truename order by createtime desc";
			$rows=$db->query($sql);
			$resoult=$rows->fetchAll();
			return $resoult;
		}
}
