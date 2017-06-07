<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/sql/SQLEntity');
requireClass('lib/sql/Key');
requireClass('lib/sql/ForeignKey');

/**
 * $tableCountries = new Table('countries');
 * $tableCountries -> parsePrimaryKey('id');
 * $tableCountries -> parseColumn('country', ColumnType::VARCHAR);
 * $tableCountries -> parseColumn('code', ColumnType::CHAR);
 * $tableCountries -> parseColumn('code_3', ColumnType::CHAR);
 * $tableCountries -> parseColumn('number', ColumnType::CHAR);
 * 
 * $tableAddresses = new Table('addresses');
 * $tableAddresses -> parsePrimaryKey('id');
 * $tableAddresses -> parseColumn('street', ColumnType::VARCHAR);
 * $tableAddresses -> parseColumn('number', ColumnType::CHAR);
 * $tableAddresses -> parseColumn('box', ColumnType::CHAR);
 * $tableAddresses -> parseColumn('postal_code', ColumnType::CHAR);
 * $tableAddresses -> parseColumn('city', ColumnType::CHAR);
 * $tableAddresses -> parseForeignKey('country', $tableCountries);
 * 
 * $tableContactDetails = new Table('contact_details');
 * $tableContactDetails -> parsePrimaryKey('id');
 * $tableContactDetails -> parseColumn('email', ColumnType::VARCHAR);
 * $tableContactDetails -> parseColumn('phone', ColumnType::VARCHAR);
 * $tableContactDetails -> parseColumn('cell', ColumnType::VARCHAR);
 * $tableContactDetails -> parseColumn('fax', ColumnType::VARCHAR);
 * $tableContactDetails -> parseColumn('website', ColumnType::VARCHAR);
 * 
 * @author stijn.verholen
 */
class Table extends SQLEntity {
	
	private $primaryKey = null;
	private $columns = array();
	private $keys = array();
	private $foreignKeys = array();
	
	public function __construct(
			$name = '',
			$alias = '') {
		$this -> setName($name);
		$this -> setAlias($alias);
	}
	
	public function addColumn(Column $column) {
		$this -> columns[] = $column;
	}
	public function getColumns() {
		return $this -> columns;
	}
	
	public function setPrimaryKey(Key $primaryKey = null) {
		$this -> primaryKey = $primaryKey;
	}
	public function getPrimaryKey() {
		return $this -> primaryKey;
	}
	public function hasPrimaryKey() {
		return $this -> getPrimaryKey();
	}
	
	public function setKeys(array $keys = array()) {
		foreach ($keys as $key)
			$this -> addKey($key);
	}
	public function addKey(Key $key) {
		$this -> keys[] = $key;
	}
	public function getKeys() {
		return $this -> keys;
	}
	
	public function setForeignKeys(array $foreignKeys = array()) {
		foreach ($foreignKeys as $foreignKey)
			$this -> addForeignKey($foreignKey);
	}
	public function addForeignKey(ForeignKey $foreignKey) {
		$this -> foreignKeys[] = $foreignKey;
	}
	public function getForeignKeys() {
		return $this -> foreignKeys;
	}
	
	public function parsePrimaryKey($name) {
		$column = Column::getBigint($name);
		$key = Key::getPrimary($column);
		
		$this -> addColumn($column);
		$this -> setPrimaryKey($key);
	}
	public function parseColumn($name, ColumnType $type = null) {
		if ($type == null)
			$type = ColumnType::getVarchar();
		
		$column = Column::parse($name, $type);
		
		$this -> addColumn($column);
	}
	/**
	 * 
	 * @param string $name the foreign key name.
	 * @param Table $foreignTable the foreign table being referenced.
	 * @param Column $foreignColumn the unique key in the foreign table.
	 */
	public function parseForeignKey(
			$name, Table $foreignTable, Column $foreignColumn = null) {
		$type = $foreignTable -> getPrimaryKey() -> getColumn() -> getType();
		$column = Column::parse($name, $type);
		$key = Key::getForeign($column, $foreignTable, $foreignColumn);
		
		$this -> addColumn($column);
		$this -> addForeignKey($key);
	}
	
	public function __toString() {
		$output  = 'TABLE ' . $this -> getName() . ' (PRIMARY KEY: ';
		
		if (!$this -> hasPrimaryKey()) $output .= 'none';
		else $output .= $this -> getPrimaryKey() -> getName();
		
		$output .= '; COLUMNS: ';
		
		$columns = $this -> getColumns();
		$size = sizeof($columns);
		
		if ($size == 0) $output .= 'none';
		else {
			for ($i = 0; $i < $size; $i++) {
				$output .= $columns[$i] -> getName();
				$output .= '(' . $columns[$i] -> getType() . ')';
				
				if ($i < $size - 1) $output .= ', ';
			}
		}
		
		$output .= '; FOREIGN KEYS: ';
		
		$foreign = $this -> getForeignKeys();
		$size = sizeof($foreign);
		if ($size == 0) $output .= 'none';
		else {
			for ($i = 0; $i < $size; $i++) {
				$output .= $foreign[$i] -> getColumn() . '(';
				$output .= $foreign[$i] -> getForeignTable() -> getName() . '(';
				$output .= $foreign[$i] -> getForeignColumn() . '))';
				
				if ($i < $size - 1) $output .= ', ';
			}
		}
		
		$output .= ')';
		
		return $output;
	}
}

?>