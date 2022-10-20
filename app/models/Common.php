<?php
class Common extends Zend_Db_Table
{
	public function __construct()
  {
		$dbprefix = Zend_Registry::get('dbprefix');
		$this->_name = $dbprefix.$this->_name;
		parent::__construct();
  }
}
?>