<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/db/DBEnabled');
requireClass('lib/accounting/AccountValidation');

class Account extends DBEnabled {
	
	private static $TABLE = 'accounts';
	
	public static $KEY_ORGANISATION = 'organisation';
	public static $KEY_ACCOUNT = 'account';
	public static $KEY_BANK = 'bank';
	public static $KEY_BIC = 'bic';
	public static $KEY_IBAN = 'iban';
	public static $KEY_NUMBER = 'number';
	
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
		$table = new Table(self::$TABLE);
		
		$table -> parsePrimaryKey(self::$KEY_ID);
		$table -> parseForeignKey(self::$KEY_ORGANISATION);
		$table -> parseKey(self::$KEY_ACCOUNT);
		$table -> parseKey(self::$KEY_BANK);
		$table -> parseKey(self::$KEY_BIC);
		$table -> parseKey(self::$KEY_IBAN);
		
		return $table;
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
	
	public static function listKeys(
			$keyAlias = '',
			$tableAlias = '',
			$includeForeignKeys = false,
			$includeID = false) {
		$keys = array();
		
		if ($includeID)
			$keys = array_merge($keys, array(self::$KEY_ID, $alias, $idAlias));
		
		$keys = array_merge($keys, array(
				array(self::$KEY_ORGANISATION, $alias, 'organisation_fk'),
				array(self::$KEY_ACCOUNT, $alias),
				array(self::$KEY_BANK, $alias),
				array(self::$KEY_BIC, $alias),
				array(self::$KEY_IBAN, $alias),
				array(self::$KEY_NUMBER, $alias)));
		
		if ($includeForeignKeys)
			$keys = array_merge($keys, Organisation::listKeys(
				self::$ALIAS_ORGANISATIONS, 'organisation_id',
				$includeForeignKeys, $includeID));
		
		return $keys;
	}
	
	public static function insertKeys() {
		
	}
	public static function selectKeys(
			$prefix = '', $includeForeignKeys = false) {
		return self::listKeys();
	}
	
	public static function getIDAlias() {
		return self::$ALIAS_ACCOUNTS . '_account_id';
	}
	public static function getTableAlias() {
		return self::$ALIAS_ACCOUNTS;
	}
	
	public function load(
			array $data, $idAlias = '', $prefix = '', array $files = array()) {
		$this -> checkID($data, $idAlias);
		
		$this -> setAccount(
				$this -> checkKey($data, self::$KEY_ACCOUNT, ''));
		$this -> setBank(
				$this -> checkKey($data, self::$KEY_BANK, ''));
		$this -> setBIC(
				$this -> checkKey($data, self::$KEY_BIC, ''));
		$this -> setIBAN(
				$this -> checkKey($data, self::$KEY_IBAN, ''));
		$this -> setNumber(
				$this -> checkKey($data, self::$KEY_IBAN, ''));
		
		parent::load($data, $idAlias, $prefix, $files);
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