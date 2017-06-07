<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

class PrimaryKey extends Key {
	
	function __construct(Column $column = null) {
		$this -> setType(KeyType::getPrimary());
		$this -> addColumn($column);
	}
	
	public function getColumn() {
		return $this -> getColumns()[0];
	}
}