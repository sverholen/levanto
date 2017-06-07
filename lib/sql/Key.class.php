<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/sql/Column');
requireClass('lib/sql/KeyType');
requireClass('lib/sql/PrimaryKey');
requireClass('lib/sql/ForeignKey');

class Key {
	
	private $columns = array();
	private $type = null;
	
	public function __construct(
			KeyType $type = null, array $columns = array()) {
		$this -> setType($type);
		$this -> setColumns($columns);
	}
	
	public function setType(KeyType $type = null) {
		$this -> type = $type;
	}
	public function getType() {
		return $this -> type;
	}
	
	public function getName() {
		if (sizeof($this -> getColumns()) == 1)
			return $this -> getColumns()[0] -> getName();
		
		return '';
	}
	
	public function addColumn(Column $column) {
		$this -> columns[] = $column;
	}
	public function setColumns(array $columns = array()) {
		foreach ($columns as $column)
			$this -> addColumn($column);
	}
	public function getColumns() {
		return $this -> columns;
	}
	public function getColumn() {
		if (sizeof($this -> getColumns()) == 1)
			return $this -> getColumns()[0];
		
		return null;
	}
	
	public function isPrimaryKey() {
		return $this -> getKey() -> isPrimaryKey();
	}
	public function isUniqueKey() {
		return $this -> getKey() -> isUniqueKey();
	}
	public function isForeignKey() {
		return $this -> getKey() -> isForeignKey();
	}
	
	public static function getPrimary(Column $column = null) {
		return new PrimaryKey($column);
	}
	public static function getUnique(array $columns = array()) {
		return new Key(KeyType::getUnique(), $columns);
	}
	public static function getForeign(
			Column $column = null,
			Table $foreignTable = null,
			Column $foreignColumn = null) {
		if ($foreignColumn == null && $foreignTable != null)
			$foreignColumn = $foreignTable -> getPrimaryKey() -> getColumn();
		
		return new ForeignKey($column, $foreignTable, $foreignColumn);
	}
}

?>