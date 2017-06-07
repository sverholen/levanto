<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

class Table {
	
	private $name = '';
	private $alias = '';
	
	private $columns = array();
	private $foreignKeyColumns = array();
	
	function __construct(
			string $name = '',
			string $alias = '') {
		$this -> setName($name);
		$this -> setAlias($alias);
	}
	
	function setName(string $name) {
		$this -> name = $name;
	}
	function getName() {
		return $this -> name;
	}
	
	function setAlias(string $alias) {
		if (!$alias)
			$alias = $this -> getName();
		
		$this -> alias = $alias;
	}
	function getAlias() {
		return $this -> alias;
	}
	
	function addColumn(Column $column) {
		$this -> columns[] = $column;
	}
	function getColumns() {
		return $this -> columns;
	}
	
	function addForeignKeyColumn(Column $column) {
		$this -> foreignKeyColumns[] = $column;
	}
	function getForeignKeyColumns() {
		return $this -> foreignKeyColumns;
	}
}
?>