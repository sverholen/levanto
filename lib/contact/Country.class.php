<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/db/DBEnabled');

class Country extends DBEnabled {
	
	public static $TABLE			= 'countries';
	
	public static $KEY_COUNTRY		= 'country';
	public static $KEY_CODE			= 'code';
	public static $KEY_CODE_3		= 'code_3';
	public static $KEY_NUMBER		= 'number';
	
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
		
		$this -> setTable(self::$TABLE);
		$this -> setPrimaryKey(self::$KEY_ID);
		$this -> addKey(self::$KEY_COUNTRY);
		$this -> addKey(self::$KEY_CODE);
		$this -> addKey(self::$KEY_CODE_3);
		$this -> addKey(self::$KEY_NUMBER);
	}
	public function __clone() {}
	
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
	
	public static function listKeys(
			$keyAlias = '',
			$tableAlias = '',
			$includeForeignKeys = false,
			$includeID = false) {
		$keys = array();
		
		if ($includeID)
			$keys = array_merge($keys, array(self::$KEY_ID, $alias, $idAlias));
		
		$keys = array_merge($keys, array(
				array(Country::$KEY_COUNTRY, $alias),
				array(Country::$KEY_CODE, $alias),
				array(Country::$KEY_CODE_3, $alias),
				array(Country::$KEY_NUMBER, $alias)));
		
		return $keys;
	}
	
	public function load(
			array $data, $idAlias = '', $prefix = '', array $files = array()) {
		$this -> checkID($data, $idAlias);
		print 'ID: ' . $this -> getID() . "\r\n";print ($this -> hasID() ? 'HAS ID' : 'DOES NOT HAVE ID') . "\r\n";
		$this -> setCountry(
				$this -> checkKey(
						$data, $prefix . self::$KEY_COUNTRY, ''));
		$this -> setCode(
				strtolower($this -> checkKey(
						$data, $prefix . self::$KEY_CODE, '')));
		$this -> setCode3Letters(
				strtolower($this -> checkKey(
						$data, $prefix . self::$KEY_CODE_3, '')));
		$this -> setNumber(
				$this -> checkKey(
						$data, $prefix . self::$KEY_NUMBER, ''));
	}
	
	public function toString() {
		return $this -> getCountry() . ' (' . $this -> getCode() . ')';
	}
	
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
		
	}
	
	public static function select() {
		$objects = array();
		
		$keys = self::listKeys(self::$ALIAS_COUNTRIES, '', true, true);
		
		$query  = 'SELECT ' . self::quoteKeys($keys);
		$query .= 'FROM ';
		$query .= self::quoteTable(self::$TABLE, self::$ALIAS_COUNTRIES);
		
		$statement = getDB() -> prepare($query);
		$result = DBConnection::query($statement);
		
		if (!$result)
			return $objects;
		
		while ($row = $statement -> fetch(PDO::FETCH_ASSOC)) {
			$object = new Country();
			$object -> load($row);
			
			$objects[] = $object;
		}
		
		return $objects;
	}
}