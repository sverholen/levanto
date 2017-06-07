<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/sql/Table');

class ForeignKey extends Key {
	
	private $foreignTable = null;
	
	public function __construct(
			Column $column = null,
			Table $foreignTable = null,
			Column $foreignColumn = null) {
		$this -> setType(KeyType::getForeign());
		
		$this -> setColumns(array($column));
		$this -> setForeignTable($foreignTable);
		$this -> setForeignColumn($foreignColumn);
	}
	
	public function setForeignTable(Table $foreignTable = null) {
		$this -> foreignTable = $foreignTable;
	}
	public function getForeignTable() {
		return $this -> foreignTable;
	}
	
	public function setForeignColumn(Column $foreignColumn = null) {
		$this -> foreignColumn = $foreignColumn;
	}
	public function getForeignColumn() {
		return $this -> foreignColumn;
	}
}