<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/contact/Country');

class Address extends DBEnabled {
	
	/**
	 * An instance of the SQL table that is represented by this class.
	 * @var tableInstance an instance of the Table class for this datastore.
	 */
	private static $tableInstance		= null;
	
	public static $TABLE				= 'addresses';
	public static $TABLE_ALIAS			= 'add';
	
	public static $KEY_ENABLE			= 'enable_address';
	public static $KEY_STREET			= 'street';
	public static $KEY_NUMBER			= 'number';
	public static $KEY_BOX				= 'box';
	public static $KEY_POSTAL_CODE		= 'postal_code';
	public static $KEY_CITY				= 'city';
	public static $KEY_COUNTRY			= 'country';
	
	private $street = '';
	private $number = '';
	private $box = '';
	private $postalCode = '';
	private $country = null;
	
	public function __construct(
			$street = '',
			$number = '',
			$box = '',
			$postalCode = '',
			$city = '',
			$country = null) {
		$this -> setStreet($street);
		$this -> setNumber($number);
		$this -> setBox($box);
		$this -> setPostalCode($postalCode);
		$this -> setCity($city);
		$this -> setCountry($country);
	}
	public function __clone() {}
	
	public static function getTable() {
		if (self::$tableInstance == null) {
			$table = new Table(self::$TABLE, self::$TABLE_ALIAS);
			
			$table -> parsePrimaryKey(self::$KEY_ID);
			$table -> parseColumn(self::$KEY_STREET);
			$table -> parseColumn(self::$KEY_NUMBER, ColumnType::getChar());
			$table -> parseColumn(self::$KEY_BOX, ColumnType::getChar());
			$table -> parseColumn(self::$KEY_POSTAL_CODE, ColumnType::getChar());
			$table -> parseColumn(self::$KEY_CITY);
			$table -> parseForeignKey(self::$KEY_COUNTRY, Country::getTable());
			
			self::$tableInstance = $table;
		}
		
		return self::$tableInstance;
	}
	
	public function setStreet($street) {
		$this -> street = $street;
	}
	public function getStreet() {
		return $this -> street;
	}
	
	public function setNumber($number) {
		$this -> number = $number;
	}
	public function getNumber() {
		return $this -> number;
	}
	
	public function setBox($box) {
		$this -> box = $box;
	}
	public function getBox() {
		return $this -> box;
	}
	
	public function setPostalCode($postalCode) {
		$this -> postalCode = $postalCode;
	}
	public function getPostalCode() {
		return $this -> postalCode;
	}
	
	public function setCity($city) {
		$this -> city = $city;
	}
	public function getCity() {
		return $this -> city;
	}
	
	public function setCountry($country) {
		$this -> country = $country;
	}
	public function getCountry() {
		return $this -> country;
	}
	
	public function toString() {
		return $this -> getStreet() . ' ' . $this -> getNumber() . ' ' .
				$this -> getBox() . ' ' . $this -> getPostalCode() . ' ' .
				$this -> getCity() . ' ' . $this -> getCountry();
	}
	
	public static function load(array $pdoAssociativeArray = array()) {
		$object = new Address();
		
		print_r($pdoAssociativeArray);exit;
		return $object;
	}
	
	/*
	public function create() {
		$this -> getCountry() -> create();
		
		if ($this -> hasID()) return true;
		
		$query  = 'INSERT INTO ';
		$query .= self::quoteTable(self::$TABLE) ;
		$query .= ' (';
		$query .= self::quoteKeys(self::listKeys(), false);
		$query .= ') VALUES (';
		$query .= self::quoteValue($this -> getStreet()) . ', ';
		$query .= self::quoteValue($this -> getNumber()) . ', ';
		$query .= self::quoteValue($this -> getBox()) . ', ';
		$query .= self::quoteValue($this -> getCity()) . ', ';
		$query .= $this -> getCountry() -> getID() . ')';
		
		$statement = getDB() -> prepare($query);
		$result = DBConnection::query($statement);
		
		if ($result)
			$this -> setID(getDB() -> lastInsertId());
			
			return $result;
	}
	
	public function read() {
		
	}
	
	public function update() {
		if (!$this -> hasID())
			return false;
	}
	
	public function delete() {
		if (!$this -> hasID())
			return false;
	}
	*/
}