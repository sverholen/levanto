<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/sql/Table');

abstract class DBEnabled implements DBKeyIterable {
	
	public static $KEY_ID						= 'id';
	
	private $id = 0;
	private $table = null;
	
	public abstract function load(
			array $data, $idAlias = '', $prefix = '', array $files = array());
	public abstract function toString();
	
	public abstract function create();
	public abstract function read();
	public abstract function update();
	public abstract function delete();
	
	protected function setSQLTable(Table $table) {
		$this -> table = $table;
	}
	protected function getSQLTable() {
		return $this -> table;
	}
	protected function hasSQLTable() {
		return $this -> getSQLTable() != null;
	}
	
	protected function setPrimaryKey($primaryKey) {
		$this -> primaryKey = $primaryKey;
	}
	protected function addKey($key) {
		$this -> keys[] = $key;
	}
	protected function getKeys() {
		return $this -> keys;
	}
	protected function addForeignKey($foreignKey) {
		$this -> foreignKeys[] = $foreignKey;
	}
	protected function getForeignKeys() {
		return $this -> foreignKeys;
	}
	
	public function setID($id) {
		$this -> id = $id;
	}
	public function getID() {
		return $this -> id;
	}
	public function hasID() {
		return $this -> getID() &&
				is_int($this -> getID()) &&
				$this -> getID() > 0;
	}
	public function checkID(array $data, $idAlias = '', $prefix = '') {
		if (!isset($idAlias) || strlen($idAlias) == 0)
			$idAlias = self::$KEY_ID;
		
		if (isset($data[$idAlias]))
			$this -> setID(intval($data[$idAlias]));
	}
	
	protected function checkKey(array $data, $key, $default = '') {
		if (is_array($data) && sizeof($data) > 0 &&
				isset($data[$key]))
			return $data[$key];
		
		return $default;
	}
	
	protected function insertQuery(array $keys, array $values) {
		$size = sizeof($keys);
		if (sizeof($values) != $size) {
			// throw error
			return false;
		}
		
		$query  = 'INSERT INTO ' . $this -> quoteKey($this -> getTable());
		$query .= ' (';
		
		for ($i = 0; $i < $size; $i++) {
			$query .= $this -> quoteKey($keys[$i]);
			
			if ($i < $size - 2)
				$query .= ', ';
		}
		
		$query .= ') VALUES ';
	}
	/*
	public static function getKeys(
			$alias, $idAlias, $includeForeignKeys = false, $includeID = false) {
		$class = get_called_class();
		
		return $class::listKeys(
				$alias, $idAlias, $includeForeignKeys, $includeID);
	}
	*/
	protected static function quoteTable($table, $alias = '') {
		return '`' . $table . '`' . ($alias ? ' AS `' . $alias . '`' : '');
	}
	
	protected static function quoteKey(
			$key, $keyAlias = '', $tableAlias = '', $useAlias = false) {
		if ($useAlias && !$alias) $alias = $key;
		
		$quoted = '`' . $key . '`';
		
		if ($table)
			$quoted = '`' . $table . '`.' . $quoted;
		
		if (!$useAlias) return $quoted;
		
		return $quoted . ' AS `' . $alias . '`';
	}
	protected static function quoteForeignKey($key, $table) {
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
	protected static function quoteKeys(array $keys, $useAlias = false) {
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
	
	protected function quoteValue($value) {
		return '"' . $value . '"';
	}
}

interface DBKeyIterable {
	
	/**
	 * 
	 * @param string $keyAlias The alias of the key (`id` AS `country`.`id`)
	 * @param string $tableAlias The table alias (FROM `table` AS `t`)
	 * @param string $includeForeignKeys Include the keys of the foreign table
	 * in the output array (set to false for output intended for INSERT
	 * statements and to true for output intended for SELECT statements).
	 * @param string $includeID Include the table id in the output array (set 
	 * to false for output intended for INSERT statements and to true for
	 * output intended for SELECT statements).
	 */
	/*
	public static function listKeys(
			$keyAlias = '',
			$tableAlias = '',
			$includeForeignKeys = false,
			$includeID = false);
	*/
	public static function getTable();
	
	/*
	public static function insertKeys();
	public static function selectKeys();
	
	public static function getIDAlias();
	public static function getTableAlias();
	*/
}

?>