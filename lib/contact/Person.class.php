<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/contact/Contact');

class Person extends Contact {
	
	/**
	 * An instance of the SQL table that is represented by this class.
	 * @var tableInstance an instance of the Table class for this datastore.
	 */
	private static $tableInstance		= null;
	
	public static $TABLE				= 'people';
	public static $TABLE_ALIAS			= 'peo';
	
	public static $KEY_FIRST_NAME		= 'first_name';
	public static $KEY_LAST_NAME		= 'last_name';
	
	private $firstName = '';
	private $lastName = '';
	
	public function __construct(
			$firstName = '',
			$lastName = '',
			Address $address = null,
			ContactDetails $contactDetails = null) {
		$this -> setFirstName($firstName);
		$this -> setLastName($lastName);
		$this -> setAddress($address);
		$this -> setContactDetails($contactDetails);
	}
	public function __clone() {}
	
	public static function getTable() {
		if (self::$tableInstance == null) {
			$table = new Table(self::$TABLE, self::$TABLE_ALIAS);
			
			$table -> parsePrimaryKey(self::$KEY_ID);
			$table -> parseColumn(self::$KEY_FIRST_NAME);
			$table -> parseColumn(self::$KEY_LAST_NAME);
			$table -> parseForeignKey(
					self::$KEY_ADDRESS, Address::getTable());
			$table -> parseForeingKey(
					self::$KEY_CONTACT_DETAILS, ContactDetails::getTable());
			
			self::$tableInstance = $table;
		}
		
		return self::$tableInstance;
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
	
	public function toString() {
		return $this -> getFirstName() . ' ' . $this -> getLastName();
	}
	
	public function create() {
		$this -> getAddress() -> create();
		$this -> getContactDetails() -> create();
		
		if ($this -> hasID()) return true;
		
		$query  = 'INSERT INTO ';
		$query .= self::quoteTable(self::$TABLE) ;
		$query .= ' (';
		$query .= self::quoteKeys(self::listKeys(), false);
		$query .= ') VALUES (';
		$query .= self::quoteValue($this -> getFirstName()) . ', ';
		$query .= self::quoteValue($this -> getLastName()) . ', ';
		$query .= $this -> getAddress() -> getID() . ', ';
		$query .= $this -> getContactDetails() -> getID() . ')';
		
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
		
		$keys = self::getKeys(self::$ALIAS_PEOPLE, 'person_id');
		
		$query  = 'SELECT ' . self::quoteKeys($keys);
		$query .= ' FROM ';
		$query .= self::quoteTable(self::$TABLE, self::$ALIAS_PEOPLE);
		
		$query .= ' LEFT JOIN ';
		$query .= self::quoteTable(Address::$TABLE, self::$ALIAS_ADDRESSES);
		$query .= ' ON ';
		$query .= self::quoteForeignKey(
				self::$KEY_ADDRESS, self::$ALIAS_PEOPLE);
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
				self::$KEY_CONTACT_DETAILS, self::$ALIAS_PEOPLE);
		$query .= ' = ';
		$query .= self::quoteForeignKey(
				ContactDetails::$KEY_ID, self::$ALIAS_CONTACT_DETAILS);
		
		$statement = getDB() -> prepare($query);
		$result = DBConnection::query($statement);
		
		if (!$result)
			return $objects;
		
		while ($row = $statement -> fetch(PDO::FETCH_ASSOC)) {
			$object = new Person();
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