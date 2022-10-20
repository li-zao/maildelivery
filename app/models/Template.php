<?php 
	class template extends Common{
		protected $_name="template";
		protected $_primary="id";

		/*插入一条数据*/
		public function  insertOne($arrs){
			$db=$this->getAdapter();	
			$rows=$this->insert($arrs);
			return $rows;
		}
		/*查询用户下的模版*/
		public function  getUserALl($id){
			$db=$this->getAdapter();
			$sql="select id,tpl_name,tpl_img from mr_template where uid=".$id;
			$res=$db->query($sql);
			$resoult=$res->fetchAll();
			return $resoult;
		} 

		/*查询id下的模板属性*/
		public function getOneTpl($id){
			$row=$this->find($id);
			$res=$row->current();
			return $res;
		}

		/*更新内容，标题，行业分类*/		
		public function updateTpl($id,$content,$title,$style,$thumb_image){
			$db=$this->getAdapter();
			$table="mr_template";
			$set=array('tpl_body'=>$content,
						'tpl_name'=>$title,
						'tpl_style'=>$style,
						'tpl_img'=>$thumb_image);
			$where="id=".$id;
			$row=$db->update($table,$set,$where);
			return $row;
		}

		/*查询多条数据o*/
		public function searchAll($strs,$uid){
			$db=$this->getAdapter();
			$sql="select id,tpl_name,tpl_img from mr_template where tpl_style in(".$strs.") and uid in ({$uid})";
			// echo $sql;
			$row=$db->query($sql);
			$resoult=$row->fetchAll();
			return $resoult;
		}

		/*删除一条数据*/
		public function delOne($id){
			$where="id=".$id;	
			$rows=$this->delete($where);
			return $rows;
		}

		/*根据模板名字匹配*/
		public function checkTplOne($tplname){
			$db=$this->getAdapter();	
			$sql='SELECT id FROM mr_template WHERE tpl_name="'.$tplname.'"';
			$row=$db->fetchOne($sql);

			return $row;
		}

	

		
	}
