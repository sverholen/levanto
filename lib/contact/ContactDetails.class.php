<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/db/DBEnabled');
requireClass('tools/InputCleaner');

class ContactDetails extends DBEnabled {
	
	/**
	 * An instance of the SQL table that is represented by this class.
	 * @var tableInstance an instance of the Table class for this datastore.
	 */
	private static $tableInstance		= null;
	
	public static $TABLE				= 'contact_details';
	public static $TABLE_ALIAS			= 'cod';
	
	public static $KEY_ENABLE			= 'enable_contact_details';
	public static $KEY_EMAIL			= 'email';
	public static $KEY_PHONE			= 'phone';
	public static $KEY_CELL				= 'cell';
	public static $KEY_FAX				= 'fax';
	public static $KEY_WEBSITE			= 'website';
	
	private $email = '';
	private $phone = '';
	private $cell = '';
	private $fax = '';
	private $website = '';
	
	public function __construct(
			$email = '',
			$phone = '',
			$cell = '',
			$fax = '',
			$website = '') {
		$this -> setEmail($email);
		$this -> setPhone($phone);
		$this -> setCell($cell);
		$this -> setFax($fax);
		$this -> setWebsite($website);
	}
	
	public static function getTable() {
		if (self::$tableInstance == null) {
			$table = new Table(self::$TABLE, self::$TABLE_ALIAS);
			
			$table -> parsePrimaryKey(self::$KEY_ID);
			$table -> parseColumn(self::$KEY_EMAIL);
			$table -> parseColumn(self::$KEY_PHONE);
			$table -> parseColumn(self::$KEY_CELL);
			$table -> parseColumn(self::$KEY_FAX);
			$table -> parseColumn(self::$KEY_WEBSITE);
			
			self::$tableInstance = $table;
		}
		
		return self::$tableInstance;
	}
	
	public function setEmail($email) {
		$email = InputCleaner::cleanEmailAddress($email);
		
		if (InputCleaner::isValidEmailAddress($email))
			$this -> email = $email;
		else
			$this -> email = '';
	}
	public function getEmail() {
		return $this -> email;
	}
	
	public function setPhone($phone) {
		$phone = InputCleaner::cleanPhoneNumber($phone);
		
		if (InputCleaner::isValidPhoneNumber($phone))
			$this -> phone = $phone;
		else
			$this -> phone = '';
	}
	public function getPhone() {
		return $this -> phone;
	}
	
	public function setCell($cell) {
		$cell = InputCleaner::cleanCellphoneNumber($cell);
		
		if (InputCleaner::isValidCellphoneNumber($cell))
			$this -> cell = $cell;
		else
			$this -> cell = '';
	}
	public function getCell() {
		return $this -> cell;
	}
	
	public function setFax($fax) {
		$fax = InputCleaner::cleanPhoneNumber($fax);
		
		if (InputCleaner::isValidPhoneNumber($fax))
			$this -> fax = $fax;
		else
			$this -> fax = '';
	}
	public function getFax() {
		return $this -> fax;
	}
	
	public function setWebsite($website) {
		$this -> website = $website;
	}
	public function getWebsite() {
		return $this -> website;
	}
	
	public function toString() {
		return $this -> getPhone() . ' / ' . $this -> getCell();
	}
	
	public static function load(array $pdoAssociativeArray = array()) {
		$object = new ContactDetails();
		
		print_r($pdoAssociativeArray);exit;
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
		$query .= self::quoteValue($this -> getEmail()) . ', ';
		$query .= self::quoteValue($this -> getPhone()) . ', ';
		$query .= self::quoteValue($this -> getCell()) . ', ';
		$query .= self::quoteValue($this -> getFax()) . ', ';
		$query .= self::quoteValue($this -> getWebsite()) . ')';
		
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