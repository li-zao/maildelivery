<?php

require_once 'Zend/Acl.php';
require_once 'Zend/Acl/Role.php';
require_once 'Zend/Acl/Resource.php';

/**
 * Access Control List (ACL)
 */
class Common_Plugin_MyAcl extends Zend_Acl {
	/**
	 * Constructor.
	 * @return void
	 */
	public function __construct() {
		//Add resource
		$resource = new Zend_Config_Ini ( './app/config/resource.ini', null );
		foreach ( $resource->toArray () as $key_one => $arr ) {
			$this->add ( new Zend_Acl_Resource ( $key_one ) );
			foreach ( $arr as $key_two => $value ) {
				$this->add ( new Zend_Acl_Resource ( $value ), $key_one );
			}
		}
		
		//Add role
		$this->addRole ( new Zend_Acl_Role ( 'guest' ) );
		$this->addRole ( new Zend_Acl_Role ( 'admin' ), 'guest' );
		$this->addRole ( new Zend_Acl_Role ( 'sadmin' ), 'admin' );
		$this->addRole ( new Zend_Acl_Role ( 'superadmin' ), 'sadmin' );		
	}
}