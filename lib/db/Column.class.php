<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

class Column {
	
	private $name = '';
	private $alias = '';
	
	function __construct(string $name = '') {
		$this -> setName($name);
	}
	
	function setName(string $name) {
		$this -> name = $name;
	}
	function getName() {
		return $this -> name;
	}
}
?>