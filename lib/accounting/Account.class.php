<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/db/DBEnabled');
requireClass('lib/accounting/AccountValidation');

class Account extends DBEnabled {
	
	/**
	 * An instance of the SQL table that is represented by this class.
	 * @var tableInstance an instance of the Table class for this datastore.
	 */
	private static $tableInstance		= null;
	
	private static $TABLE				= 'accounts';
	private static $TABLE_ALIAS			= 'acc';
	
	public static $KEY_ORGANISATION		= 'organisation';
	public static $KEY_ACCOUNT			= 'account';
	public static $KEY_BANK				= 'bank';
	public static $KEY_BIC				= 'bic';
	public static $KEY_IBAN				= 'iban';
	public static $KEY_NUMBER			= 'number';
	
	private $organisation = null;
	private $account = '';
	private $bank = '';
	private $bic = '';
	private $iban = '';
	private $number = '';
	
	public function __construct(
			$organisation = null,
			$account = '',
			$bank = '',
			$bic = '',
			$iban = '',
			$number = '') {
		$this -> setOrganisation($organisation);
		$this -> setAccount($account);
		$this -> setBank($bank);
		$this -> setBIC($bic);
		$this -> setIBAN($iban);
		$this -> setNumber($number);
	}
	public function __clone() {}
	
	public static function getTable() {
		if (self::$tableInstance == null) {
			$table = new Table(self::$TABLE, self::$TABLE_ALIAS);
			
			$table -> parsePrimaryKey(self::$KEY_ID);
			$table -> parseForeignKey(
					self::$KEY_ORGANISATION, Organisation::getTable());
			$table -> parseColumn(self::$KEY_ACCOUNT);
			$table -> parseColumn(self::$KEY_BANK, ColumnType::getChar());
			$table -> parseColumn(self::$KEY_BIC, ColumnType::getChar());
			$table -> parseColumn(self::$KEY_IBAN, ColumnType::getChar());
			$table -> parseColumn(self::$KEY_NUMBER, ColumnType::getChar());
			
			self::$tableInstance = $table;
		}
		
		return self::$tableInstance;
	}
	
	public function setOrganisation(Organisation $organisation = null) {
		$this -> organisation = $organisation;
	}
	public function getOrganisation() {
		return $this -> organisation;
	}
	
	public function setAccount($account = '') {
		$this -> account = $account;
	}
	public function getAccount() {
		return $this -> account;
	}
	
	public function setBank($bank = '') {
		$this -> bank = $bank;
	}
	public function getBank() {
		return $this -> bank;
	}
	
	public function setBIC($bic = '') {
		$this -> bic = $bic;
	}
	public function getBIC() {
		return $this -> bic;
	}
	
	public function setIBAN($iban = '') {
		$this -> iban = $iban;
	}
	public function getIBAN() {
		return $this -> iban;
	}
	
	public function setNumber($number = '') {
		$this -> number = $number;
	}
	public function getNumber() {
		return $this -> number;
	}
	
	public function toString() {
		return $this -> getAccount() . ' (' . $this -> getBank() . ')';
	}
	
	public function create() {
		$this -> getOrganisation() -> create();
		
		if ($this -> hasID()) return true;
		
		$query  = 'INSERT INTO ';
		$query .= self::quoteTable(self::$TABLE) ;
		$query .= ' (';
		$query .= self::quoteKeys(self::insertKeys(), false);
		$query .= ') VALUES (';
		$query .= $this -> getOrganisation() -> getID() . ', ';
		$query .= self::quoteValue($this -> getAccount()) . ', ';
		$query .= self::quoteValue($this -> getBank()) . ', ';
		$query .= self::quoteValue($this -> getBIC()) . ', ';
		$query .= self::quoteValue($this -> getIBAN()) . ', ';
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
		
		$keys = self::listKeys(self::$ALIAS_ACCOUNTS, 'account_id');
		
		$query  = 'SELECT ' . self::quoteKeys($keys);
		$query .= ' FROM ' . self::quoteTable(
				self::$TABLE, self::$ALIAS_ACCOUNTS);
		
		$query .= ' LEFT JOIN ';
		$query .= self::quoteTable(
				Organisation::$TABLE, self::$ALIAS_ORGANISATIONS);
		$query .= ' ON ' . self::quoteForeignKey(
				self::$KEY_ORGANISATION, self::$ALIAS_ACCOUNTS);
		$query .= ' = ' . self::quoteForeignKey(
				Organisation::$KEY_ID, self::$ALIAS_ORGANISATIONS);
		
		$query .= ' LEFT JOIN ';
		$query .= self::quoteTable(Address::$TABLE, self::$ALIAS_ADDRESSES);
		$query .= ' ON ';
		$query .= self::quoteForeignKey(
				Organisation::$KEY_ADDRESS, self::$ALIAS_ORGANISATIONS);
		$query .= ' = ';
		$query .= self::quoteForeignKey(
				Address::$KEY_ID, self::$ALIAS_ADDRESSES);
		
		$query .= ' LEFT JOIN ';
		$query .= self::quoteTable(
				Country::$TABLE, self::$ALIAS_COUNTRIES);
		$query .= ' ON ';
		$query .= self::quoteForeignKey(
				Address::$KEY_COUNTRY, self::$ALIAS_ADDRESSES);
		$query .= ' = ';
		$query .= self::quoteForeignKey(
				Country::$KEY_ID, self::$ALIAS_COUNTRIES);
		
		$query .= ' LEFT JOIN ';
		$query .= self::quoteTable(
				ContactDetails::$TABLE, self::$ALIAS_CONTACT_DETAILS);
		$query .= ' ON ';
		$query .= self::quoteForeignKey(
				Organisation::$KEY_CONTACT_DETAILS, self::$ALIAS_ORGANISATIONS);
		$query .= ' = ';
		$query .= self::quoteForeignKey(
				ContactDetails::$KEY_ID, self::$ALIAS_CONTACT_DETAILS);
		
		$statement = getDB() -> prepare($query);
		$result = DBConnection::query($statement);
		
		if (!$result)
			return $objects;
		
		while ($row = $statement -> fetch(PDO::FETCH_ASSOC)) {
			$object = new Account();
			$object -> load($row, 'account_id');
			
			$object -> getOrganisation() -> load($row, 'organisation_id');
			
			$objects[] = $object;
		}
		
		return $objects;
	}
}
?>