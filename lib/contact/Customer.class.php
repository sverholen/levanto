<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/contact/Contact');
requireClass('lib/contact/CustomerOrganisation');
requireClass('lib/contact/Person');

class Customer extends Contact {
	
	public static $TABLE			= 'customers';
	
	public static $PREFIX			= 'customer_';
	
	public static $KEY_FIRST_NAME	= 'first_name';
	public static $KEY_LAST_NAME	= 'last_name';
	public static $KEY_FUNCTION		= 'function';
	public static $KEY_ORGANISATION	= 'organisation';
	
	private $firstName = '';
	private $lastName = '';
	private $function = '';
	private $organisation = null;
	
	public function __construct(
			$firstName = '',
			$lastName = '',
			$function = '',
			CustomerOrganisation $organisation = null) {
		$this -> setFirstName($firstName);
		$this -> setLastName($lastName);
		$this -> setFunction($function);
		$this -> setOrganisation($organisation);
		
		$this -> setTable(self::$TABLE);
		$this -> setPrimaryKey(self::$KEY_ID);
		$this -> addKey(self::$KEY_FIRST_NAME);
		$this -> addKey(self::$KEY_LAST_NAME);
		$this -> addKey(self::$KEY_FUNCTION);
		$this -> addForeignKey(self::$KEY_ORGANISATION);
	}
	
	public function setFirstName($firstName) {
		$this -> firstName = $firstName;
	}
	public function getFirstName() {
		return $this -> firstName;
	}
	
	public function setLastName($lastName) {
		$this -> lastName = $lastName;
	}
	public function getLastName() {
		return $this -> lastName;
	}
	
	public function setFunction($function) {
		$this -> function = $function;
	}
	public function getFunction() {
		return $this -> function;
	}
	
	public function setOrganisation(CustomerOrganisation $organisation = null) {
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
		$keys = array_merge(
				Person::listKeys(
					$alias, $idAlias, $includeForeignKeys, $includeID),
				array(self::$KEY_FUNCTION, $alias),
				array(self::$KEY_ORGANISATION, 'organisation_fk'));
		
		if ($includeForeignKeys)
			$keys = array_merge($keys, CustomerOrganisation::listKeys(
					self::$ALIAS_CUSTOMER_ORGANISATIONS, 'organisation_id',
					$includeForeignKeys, $includeID));
		
		return $keys;
	}
	
	public function load(
			array $data, $idAlias = '', $prefix = '', array $files = array()) {
		$this -> checkID($data, $idAlias);
		
		$this -> setFirstName(
				$this -> checkKey($data, $prefix . self::$KEY_FIRST_NAME, ''));
		$this -> setLastName(
				$this -> checkKey($data, $prefix . self::$KEY_LAST_NAME, ''));
		$this -> setFunction(
				$this -> checkKey($data, $prefix . self::$KEY_FUNCTION, ''));
		
		$organisation = new CustomerOrganisation();
		$organisation -> load($data, $idAlias, $prefix, $files);
		
		$this -> setOrganisation($organisation);
		
		parent::load($data, $idAlias, $prefix, $files);
	}
	
	public function toString() {
		return $this -> getFirstName() . ' ' . $this -> getLastName();
	}
	
	public function create() {
		$this -> getAddress() -> create();
		$this -> getContactDetails() -> create();
		$this -> getOrganisation() -> create();
		
		if ($this -> hasID()) return true;
		
		$query  = 'INSERT INTO ';
		$query .= self::quoteTable(self::$TABLE) ;
		$query .= ' (';
		$query .= self::quoteKeys(self::listKeys(), false);
		$query .= ') VALUES (';
		$query .= self::quoteValue($this -> getFirstName()) . ', ';
		$query .= self::quoteValue($this -> getLastName()) . ', ';
		$query .= $this -> getAddress() -> getID() . ', ';
		$query .= $this -> getContactDetails() -> getID() . ', ';
		$query .= self::quoteValue($this -> getFunction()) . ', ';
		$query .= $this -> getOrganisation() -> getID() . ')';
		
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
		
		$keys = self::getKeys(self::$ALIAS_CUSTOMERS, 'person_id');
		
		$query  = 'SELECT ' . self::quoteKeys($keys);
		$query .= ' FROM ';
		$query .= self::quoteTable(self::$TABLE, self::$ALIAS_CUSTOMERS);
		
		$query .= ' LEFT JOIN ';
		$query .= self::quoteTable(Address::$TABLE, self::$ALIAS_ADDRESSES);
		$query .= ' ON ';
		$query .= self::quoteForeignKey(
				self::$KEY_ADDRESS, self::$ALIAS_CUSTOMERS);
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
				self::$KEY_CONTACT_DETAILS, self::$ALIAS_CUSTOMERS);
		$query .= ' = ';
		$query .= self::quoteForeignKey(
				ContactDetails::$KEY_ID, self::$ALIAS_CONTACT_DETAILS);
		
		$statement = getDB() -> prepare($query);
		$result = DBConnection::query($statement);
		
		if (!$result)
			return $objects;
		
		while ($row = $statement -> fetch(PDO::FETCH_ASSOC)) {
			$object = new Customer();
			$object -> load($row, 'people_id');
			
			$object -> getAddress() -> setID(
					$result['address_id']);
			$object -> getContactDetails() -> setID(
					$result['contact_details_id']);
			
			$objects[] = $object;
		}
			
		return $objects;
	}
}

?>