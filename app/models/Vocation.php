<?php 
	class Vocation extends Common{
		protected $_name="vocation";
		protected $_primary="id";

		public function addInsert($arr){
			$resoult=$this->insert($arr);
			return $resoult;
		}
		/*查询同一ID有没有相同的分类*/
		public function getOneselect($name){
			$db=$this->getAdapter();
			//$sql='select id from mr_vocation where vocation_name="'.$name.'" and uid='.$id;
			$sql='select id from mr_vocation where vocation_name="'.$name.'"';
			$rows=$db->query($sql);
			$resoult=$db->fetchOne($sql);
			return $resoult;
		}
		/*查询用户下的模版行业分类*/
		public function getAllTpls($uid){
			$db=$this->getAdapter();
			$sql="select id,uid,vocation_name,vocation_body from mr_vocation where uid in({$uid}) order by id desc";
			$res=$db->query($sql);
			$resoult=$res->fetchAll();
			return $resoult;
		}

		/*查询一个模板根据id*/
		public function getvoOne($id){
				$row=$this->find($id);
				$resoult=$row->current();
				return $resoult;
		}

		/*更新模板*/
		public function updateOne($str1,$str2,$id){
			$db=$this->getAdapter();
			$set=array('vocation_name'=>$str1,
						'vocation_body'=>$str2);
			$where='id='.$id;
			$row=$this->update($set,$where);
			return $row;
		}

		/*删除用户分类*/
		public function deleteOne($id,$uid){
			if($id!="" && $uid!=""){
				$where="id={$id} and uid={$uid}";
			}
			$rows=$this->delete($where);
			return $rows;

		}
		
		public function selectAllType ($where) {
			$db = $this->getAdapter ();
			$sql = "select id,uid,vocation_name,vocation_body from mr_vocation where ".$where."";
			$stmt = $db->query ( $sql );
			$array = $stmt->fetchAll();
			return $array;
		}
		
		public function getAllCountType($where) {
			$db = $this->getAdapter ();
			$sql="select count(id) as num from mr_vocation where ".$where."";
			$res = $db->fetchOne ( $sql );
			return $res;
		}
		
		public function getNameById($id){
			$db=$this->getAdapter();
			$sql='select vocation_name from mr_vocation where id="'.$id.'"';
			$rows=$db->query($sql);
			$resoult=$db->fetchOne($sql);
			return $resoult;
		}
	}
