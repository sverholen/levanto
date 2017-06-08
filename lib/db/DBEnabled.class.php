<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/db/DBIterable');
requireClass('lib/db/CRUD');
requireClass('lib/sql/Table');

abstract class DBEnabled implements DBIterable, CRUD {
	
	public static $KEY_ID				= 'id';
	
	private $id = 0;
	
	public abstract function toString();
	
	//public abstract function create();
	//public abstract function read();
	//public abstract function update();
	//public abstract function delete();
	
	public static function selectAll() {
		return self::select();
	}
	
	public static function select() {
		$objects = array();
		
		$statement = getDB() -> prepare(static::getTable() -> selectQuery());
		$result = DBConnection::query($statement);
		
		if (!$result)
			return $objects;
		
		while ($row = $statement -> fetch(PDO::FETCH_ASSOC)) {
			$objects[] = static::load($row);
		}
		
		return $objects;
	}
	
	public static function create(array $data = array()) {
		$statement = getDB() -> prepare(
				static::getTable() -> insertQuery($data));
		$result = DBConnection::query($statement);
		
		if ($result) {
			$object = static::load($data);
			
			$object -> setID(getDB() -> lastInsertId());
			
			return $object;
		}
		
		return null;
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
}



?>