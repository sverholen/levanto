<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/sql/SQLEntity');
requireClass('lib/sql/Key');
requireClass('lib/sql/ForeignKey');

/**
 * Main entry point for the SQL abstraction layer.
 * 
 * Allows you to do something like the following code, with the abstraction
 * layer responsible for generating queries.
 * 
 * Resource intensive, not meant for production use !
 * 
 * CODE:
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
	public function getColumns($includePrimaryKeys = true) {
		if ($includePrimaryKeys)
			return $this -> columns;
		
		$columns = array();
		$size = sizeof($this -> columns);
		$name = $this -> getPrimaryKey() -> getColumn() -> getName();
		
		for ($i = 0; $i < $size; $i++) {
			if ($this -> columns[$i] != $name)
				$columns[] = $this -> columns[$i];
		}
		
		return $columns;
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
	public function isPrimaryKey(Column $column) {
		return $this -> getPrimaryKey() -> getColumn() == $column;
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
	
	public static function quoteTable($table, $alias = '') {
		return '`' . $table . '`' .
				($alias && $alias != $table ? ' AS `' . $alias . '`' : '');
	}
	public static function quoteColumn($column, $tableAlias = '', $alias = '') {
		$quoted = '`' . $column . '`';
		
		if ($tableAlias)
			$quoted = '`' . $table . '`.' . $quoted;
		
		if ($alias)
			$quoted = $quoted . ' AS `' . $alias . '`';
		
		return $quoted;
	}
	private static function quoteColumnList(array $columns = array()) {
		$size = sizeof($columns);
		$quoted = '';
		
		for ($i = 0; $i < $size; $i++) {
			$quoted .= self::quoteColumn($columns[$i] -> getName());
			
			if ($i < $size -1)
				$quoted .= ', ';
		}
		
		return $quoted;
	}
	public static function quoteForeignKey($key, $table) {
		$quoted = '`' . $key . '`';
		
		if ($table)
			$quoted = '`' . $table . '`.' . $quoted;
			
			return $quoted;
	}
	/**
	 *
	 * @param array $keys
	 * @param string $useAlias
	 * @return string
	 */
	public static function quoteKeys(array $keys, $useAlias = false) {
		$query = '';
		$count = sizeof($keys);
		
		for ($i = 0; $i < $count; $i++) {
			$size = sizeof($keys[$i]);
			
			if ($size == 1) {
				$query .= self::quoteKey(
						$keys[$i][0], '', '', $useAlias);
			}
			else if ($size == 2) {
				$query .= self::quoteKey(
						$keys[$i][0], $keys[$i][1], '', $useAlias);
			}
			else if ($size == 3) {
				$query .= self::quoteKey(
						$keys[$i][0], $keys[$i][1], $keys[$i][2], $useAlias);
			}
			else {
				continue;
			}
			
			if ($i < $count - 1)
				$query .= ', ';
		}
		
		return $query;
	}
	
	public function quoteValue($value) {
		return '"' . $value . '"';
	}
	
	public function selectQuery() {
		$query  = 'SELECT ';
		$query .= self::quoteColumnList($this -> getColumns());
		$query .= ' FROM ' . self::quoteTable($this -> getName());
		
		return $query;
	}
	
	public function insertQuery(array $data = array()) {
		$size = sizeof($this -> getColumns());
		$keys = array_keys($data);
		
		$query  = 'INSERT INTO ';
		$query .= self::quoteTable($this -> getName()) . ' (';
		$query .= self::quoteColumnList($this -> getColumns(false));
		$query .= ') VALUES (';
		
		for ($i = 0; $i < $size; $i++) {
			if ($this -> isPrimaryKey($this -> getColumns()[$i]))
				continue;
			
			for ($j = 0; $j < $size; $j++) {
				if ($this -> getColumns()[$i] -> getName() == $keys[$j]) {
					if ($this -> getColumns()[$i] -> getType() -> isNumeric())
						$query .= $data[$keys[$j]];
					else
						$query .= self::quoteValue($data[$keys[$j]]);
					
					break 1;
				}
				// @TODO: shouldn't be here, throw error
			}
			
			if ($i < $size - 1)
				$query .= ', ';
		}
		
		$query .= ')';
		
		return $query;
	}
}

?>