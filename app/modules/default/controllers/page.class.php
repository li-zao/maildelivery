<?php 
class page{
	public $total;//数据库表中总记录数
	public $listRows;//每页显示行数
	public $limit;
	public $uri;
	public $page;
	public $pageNum;
	public $config=array('header'=>"记录","prev"=>"上一页","next"=>"下一页","first"=>"首   页","last"=>"尾  页");
	public $listNum =8;
	
	public function __construct($total,$listRows=10,$parameter=""){
		$this->total = $total;
		$this->listRows = $listRows;
		$this->uri = $this->getUri($parameter);
		$this->page = !empty($_GET['page'])?$_GET['page']:1;
		$this->pageNum = ceil($this->total/$this->listRows);
		$this->limit = $this->setLimit();
	}
	private function setLimit(){
		return "limit ".($this->page-1)*$this->listRows.",{$this->listRows}";
	}
	private function getUri($parameter){
		$url = $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],"?")?"":"?").$parameter;
		$parse = parse_url($url);
		//print_r($parse);
		if(isset($parse['query'])){
			parse_str($parse['query'],$params);
			unset($params['page']);
			http_build_query($params);
			$url = $parse['path']."?".http_build_query($params);
		}
		return $url;
	}
	
	public function __get($args){
		if($args == "limit"){
			return $this->limit;
		}else{
			return null;
		}
	}
	
	private function start(){
		if($this->total == 0){
			return 0;
		}else{
			return ($this->page-1)*$this->listRows+1;
		}
	}
	
	private function end(){
		return min($this->page*$this->listRows,$this->total);
	}
	
	private function first(){
		if($this->page == 1){
			$html .= "<a href='{$this->uri}&page=1' class='first ui-corner-tl ui-corner-bl fg-button ui-button ui-state-default'>{$this->config['first']}</a>";
		}else{
			$html .= "<a href='{$this->uri}&page=1' class='first ui-corner-tl ui-corner-bl fg-button ui-button ui-state-default'>{$this->config['first']}</a>";
		}
		return $html;
	}
	
	private function prev(){
		if($this->page == 1){
			$html .= "<a href='{$this->uri}&page=1' class='previous fg-button ui-button ui-state-default'>{$this->config['prev']}</a>";
		}else{
			$html .= "<a href='{$this->uri}&page=".($this->page-1)."' class='previous fg-button ui-button ui-state-default'>{$this->config['prev']}</a>";
		}
		return $html;
	}
	
	private function pageList(){
		$linkPage = "";
		$inum = floor($this->listNum/2);
		for($i=$inum-1;$i>=1;$i--){
			$page = $this->page-$i;
			if($page<=0){
				continue;
			}
			$linkPage .= "<a href='{$this->uri}&page={$page}' class='ui-corner-tr ui-corner-br fg-button ui-button ui-state-default'>{$page}</a>";
		}
		$linkPage .= "<a><span style='width:20px;background-color:#26B779;color:white' class='ui-corner-tr ui-corner-br fg-button ui-button ui-state-default'>{$this->page}</span></a>";
		for($i=1;$i<$inum;$i++){
			$page = $this->page+$i;
			if($page<=$this->pageNum){
				$linkPage .= "<a href='{$this->uri}&page={$page}' class='ui-corner-tr ui-corner-br fg-button ui-button ui-state-default'>{$page}</a>"; 
			}else{
				break;
			}
		}
		return $linkPage;
	}
	
	private function next(){
		if($this->page == $this->pageNum){
			$html .= "<a href='{$this->uri}&page=".($this->pageNum)."' class='next fg-button ui-button ui-state-default'>{$this->config['next']}</a>";
		}else if($this->pageNum==0){
			$html .= "<a href='{$this->uri}&page=1' class='next fg-button ui-button ui-state-default'>{$this->config['next']}</a>";
		}else{
			$html .= "<a href='{$this->uri}&page=".($this->page+1)."' class='next fg-button ui-button ui-state-default'>{$this->config['next']}</a>";
		}
		return $html;
	}
	
	private function last(){
		if($this->page == $this->pageNum){
			$html .= "<a href='{$this->uri}&page=".($this->pageNum)."' class='last ui-corner-tr ui-corner-br fg-button ui-button ui-state-default'>{$this->config['last']}</a>";
		}else{
			$html .= "<a href='{$this->uri}&page=".($this->pageNum)."' class='last ui-corner-tr ui-corner-br fg-button ui-button ui-state-default'>{$this->config['last']}</a>";
		}
		return $html;
	}
	
	private function goPage(){
		return '<input type="text" onkeydown="javascript:if(event.keyCode==13){var page=(this.value>'.$this->pageNum.')?'.$this->pageNum.':this.value;location=\''.$this->uri.'&page=\'+page+\'\'}" 
		value="'.$this->page.'" style="width:25px"><input type="button" onclick="javascript:var page=(this.previousSibling.value>'.$this->pageNum.')?'.$this->pageNum.':this.previousSibling.value;location=\''.$this->uri.'&page=\'+page+\'\'" value="GO">';
	}
	
	public function fpage($display=array(0,1,2,3,4,5,6,7,8)){
		$html = "";
		$html[0] = "共有&nbsp;<b>{$this->total}</b>&nbsp;条{$this->config['header']}";
//		$html[1] = "每页显示<b>".($this->end()-$this->start()+1)."</b>条,本页<b>{$this->start()}</b>-<b>{$this->end()}</b>条";
		if($this->pageNum ==0){
			$html[2] = "&nbsp;<b>{$this->page}</b>/<b>1</b>&nbsp;";
		}else{
			$html[2] = "&nbsp;<b>{$this->page}</b>/<b>{$this->pageNum}</b>&nbsp;";
		}
		$html[3] = $this->first();
		$html[4] = $this->prev();
		$html[5] = $this->pageList();
		$html[6] = $this->next();
		$html[7] = $this->last();
//		$html[8] = $this->goPage();
		$fpage = "";
		foreach($display as $index){
			$fpage .= $html[$index];
		}
		return $fpage;
	}
}
?>