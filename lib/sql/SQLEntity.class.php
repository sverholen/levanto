<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

abstract class SQLEntity {
	
	public static $KEY_ID = 'id';
	
	private $name = '';
	private $alias = '';
	
	private function __construct() {}
	private function __clone() {}
	
	public function setName($name = '') {
		$this -> name = $name;
	}
	public function getName() {
		return $this -> name;
	}
	
	public function setAlias($alias = '') {
		$this -> alias = $alias;
	}
	public function getAlias() {
		return $this -> alias;
	}
}

?>