<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/db/DBEnabled');
requireClass('lib/contact/Organisation');

class CurrentAccount extends DBEnabled {
	
	public static $TABLE				= 'current_accounts';
	
	public static $KEY_NAME				= 'name';
	public static $KEY_ORGANISATION		= 'organisation';
	
	private $name = '';
	private $organisation = null;
	
	public function __construct(
			$name = '',
			Organisation $organisation = null) {
		$this -> setName($name);
		$this -> setOrganisation($organisation);
	}
	public function __clone() {}
	
	public static function getTable() {
		$table = new Table(self::$TABLE);
		
		$table -> parsePrimaryKey(self::$KEY_ID);
		$table -> parseColumn(self::$KEY_NAME);
		$table -> parseForeignKey(
				self::$KEY_ORGANISATION, Organisation::getTable());
		
		$this -> setTable($table);
	}
	
	public function setName($name) {
		$this -> name = $name;
	}
	public function getName() {
		return $this -> name;
	}
	
	public function setOrganisation(Organisation $organisation) {
		$this -> organisation = $organisation;
	}
	public function getOrganisation() {
		return $this -> organisation;
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
				array(self::$KEY_NAME, $alias),
				array(self::$KEY_ORGANISATION, $alias, 'organisation_fk')));
		
		if ($includeForeignKeys)
			$keys = array_merge($keys, Organisation::listKeys(
				self::$ALIAS_ORGANISATIONS, 'organisation_id',
				$includeForeignKeys, $includeID));
		
		return $keys;
	}
	
	public function load(
			array $data, $idAlias = '', $prefix = '', array $files = array()) {
		$this -> checkID($data, $idAlias);
		
		$this -> setName(
				$this -> checkKey($data, self::$KEY_NAME, ''));
		
		$organisation = new Organisation();
		$organisation -> load($data, $idAlias, $prefix, $files);
		
		$this -> setOrganisation($organisation);
	}
	
	public function toString() {
		return $this -> getName() . ' (' .
				$this -> getOrganisation() -> getOrganisation() . ')';
	}
	
	public function create() {
		$this -> getOrganisation() -> create();
		
		if ($this -> hasID()) return true;
		
		$query  = 'INSERT INTO ';
		$query .= self::quoteTable(self::$TABLE) ;
		$query .= ' (';
		$query .= self::quoteKeys(self::listKeys(), false);
		$query .= ') VALUES (';
		$query .= self::quoteValue($this -> getName()) . ', ';
		$query .= $this -> getOrganisation() -> getID();
		
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
		
		$keys = self::listKeys(
				self::$ALIAS_CURRENT_ACCOUNTS, 'current_account_id');
		
		$query  = 'SELECT ' . self::quoteKeys($keys);
		$query .= ' FROM ' . self::quoteTable(
				self::$TABLE, self::$ALIAS_CURRENT_ACCOUNTS);
		
		$query .= ' LEFT JOIN ';
		$query .= self::quoteTable(
				Organisation::$TABLE, self::$ALIAS_ORGANISATIONS);
		$query .= ' ON ' . self::quoteForeignKey(
				self::$KEY_ORGANISATION, self::$ALIAS_CURRENT_ACCOUNTS);
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
			$object = new CurrentAccount();
			$object -> load($row, 'current_account_id');
			
			$object -> getOrganisation() -> load($row, 'organisation_id');
			
			$objects[] = $object;
		}
			
		return $objects;
	}
}

?>