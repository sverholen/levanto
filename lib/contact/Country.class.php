<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/db/DBEnabled');

class Country extends DBEnabled {
	
	/**
	 * An instance of the SQL table that is represented by this class.
	 * @var tableInstance an instance of the Table class for this datastore.
	 */
	private static $tableInstance		= null;
	
	public static $TABLE				= 'countries';
	public static $TABLE_ALIAS			= 'cou';
	
	public static $KEY_COUNTRY			= 'country';
	public static $KEY_CODE				= 'code';
	public static $KEY_CODE_3			= 'code_3';
	public static $KEY_NUMBER			= 'number';
	
	private $iso3166_1Country;
	private $iso3166_1Code;
	private $iso3166_1Code3Letters;
	private $iso3166_1Number;
	
	public function __construct(
			$iso3166_1Country = '',
			$iso3166_1Code = '',
			$iso3166_1Code3Letters = '',
			$iso3166_1Number = '') {
		$this -> setCountry($iso3166_1Country);
		$this -> setCode($iso3166_1Code);
		$this -> setCode3Letters($iso3166_1Code3Letters);
		$this -> setNumber($iso3166_1Number);
	}
	public function __clone() {}
	
	public static function getTable() {
		if (self::$tableInstance == null) {
			$table = new Table(self::$TABLE, self::$TABLE_ALIAS);
			
			$table -> parsePrimaryKey(self::$KEY_ID);
			$table -> parseColumn(self::$KEY_COUNTRY, ColumnType::getVarchar());
			$table -> parseColumn(self::$KEY_CODE, ColumnType::getChar());
			$table -> parseColumn(self::$KEY_CODE_3, ColumnType::getChar());
			$table -> parseColumn(self::$KEY_NUMBER, ColumnType::getChar());
			
			self::$tableInstance = $table;
		}
		
		return self::$tableInstance;
	}
	
	public function setCountry($iso3166_1Country) {
		$this -> iso3166_1Country = $iso3166_1Country;
	}
	public function getCountry() {
		return $this -> iso3166_1Country;
	}
	
	public function setCode($iso3166_1Code) {
		$this -> iso3166_1Code = $iso3166_1Code;
	}
	public function getCode() {
		return $this -> iso3166_1Code;
	}
	
	public function setCode3Letters($iso3166_1Code3Letters) {
		$this -> iso3166_1Code3Letters = $iso3166_1Code3Letters;
	}
	public function getCode3Letters() {
		return $this -> iso3166_1Code3Letters;
	}
	
	public function setNumber($iso3166_1Number) {
		$this -> iso3166_1Number = $iso3166_1Number;
	}
	public function getNumber() {
		return $this -> iso3166_1Number;
	}
	
	public function toString() {
		return $this -> getCountry() . ' (' . $this -> getCode() . ')';
	}
	
	public static function load(array $data = array()) {
		$object = new Country();
		
		$object -> setCountry($data[self::$KEY_COUNTRY]);
		$object -> setCode($data[self::$KEY_CODE]);
		$object -> setCode3Letters($data[self::$KEY_CODE_3]);
		$object -> setNumber($data[self::$KEY_NUMBER]);
		
		if (isset($data[self::$KEY_ID]))
			$object -> setID($data[self::$KEY_ID]);
		
		return $object;
	}
	
	/*
	public function create() {
		if ($this -> hasID()) return true;
		
		$query  = 'INSERT INTO ';
		$query .= self::quoteTable(self::$TABLE) ;
		$query .= ' (';
		$query .= self::quoteKeys(self::listKeys(), false);
		$query .= ') VALUES (';
		$query .= self::quoteValue($this -> getCountry()) . ', ';
		$query .= self::quoteValue($this -> getCode()) . ', ';
		$query .= self::quoteValue($this -> getCode3Letters()) . ', ';
		$query .= self::quoteValue($this -> getNumber()) . ')';
		
		$statement = getDB() -> prepare($query);
		$result = DBConnection::query($statement);
		
		if ($result)
			$this -> setID(getDB() -> lastInsertId());
			
			return $result;
	}
	
	public function read() {
		
	}
	
	public function update() {
		
	}
	
	public function delete() {
		
	}*/
}