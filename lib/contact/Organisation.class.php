<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/contact/Contact');

class Organisation extends Contact {
	
	public static $TABLE				= 'organisations';
	
	public static $KEY_ORGANISATION		= 'organisation';
	public static $KEY_FULL_NAME		= 'full_name';
	public static $KEY_VAT_NUMBER		= 'vat_number';
	
	private $organisation = '';
	private $fullName = '';
	private $vatNumber = '';
	
	public function __construct(
			$organisation = '',
			$fullName = '',
			$vatNumber = '',
			Address $address = null,
			ContactDetails $contactDetails = null) {
		$this -> setOrganisation($organisation);
		$this -> setFullName($fullName);
		$this -> setVATNumber($vatNumber);
		$this -> setAddress($address);
		$this -> setContactDetails($contactDetails);
		
		$this -> setTable(self::$TABLE);
	}
	
	public function setOrganisation($organisation) {
		$this -> organisation = $organisation;
	}
	public function getOrganisation() {
		return $this -> organisation;
	}
	
	public function setFullName($fullName) {
		$this -> fullName = $fullName;
	}
	public function getFullName() {
		return $this -> fullName;
	}
	public function hasFullName() {
		return $this -> getFullName() != null &&
				strlen($this -> getFullName()) > 0;
	}
	
	public function setVATNumber($vatNumber) {
		$this -> vatNumber = $vatNumber;
	}
	public function getVATNumber() {
		return $this -> vatNumber;
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
				array(self::$KEY_ORGANISATION, $alias),
				array(self::$KEY_FULL_NAME, $alias),
				array(self::$KEY_VAT_NUMBER, $alias),
				array(self::$KEY_ADDRESS, $alias, 'address_fk'),
				array(self::$KEY_CONTACT_DETAILS, $alias, 'contact_fk')));
		
		if ($includeForeignKeys) {
			$keys = array_merge($keys, Address::getKeys(
				self::$ALIAS_ADDRESSES, 'address_id',
					$includeForeignKeys, $includeID));
			$keys = array_merge($keys, ContactDetails::getKeys(
				self::$ALIAS_CONTACT_DETAILS, 'contact_details_id',
					$includeForeignKeys, $includeID));
		}
		
		return $keys;
	}
	
	public function load(
			array $data, $idAlias = '', $prefix = '', array $files = array()) {
		$this -> checkID($data, $idAlias);
		
		$this -> setOrganisation(
				$this -> checkKey(
						$data, $prefix . self::$KEY_ORGANISATION, ''));
		$this -> setFullName(
				$this -> checkKey(
						$data, $prefix . self::$KEY_FULL_NAME, ''));
		$this -> setVATNumber(
				$this -> checkKey(
						$data, $prefix . self::$KEY_VAT_NUMBER, ''));
		
		parent::load($data, $idAlias, $prefix, $files);
	}
	
	public function toString() {
		return $this -> getOrganisation() . ' (' . $this -> getFullName() . ')';
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
		$query .= self::quoteValue($this -> getOrganisation()) . ', ';
		$query .= self::quoteValue($this -> getFullName()) . ', ';
		$query .= self::quoteValue($this -> getVATNumber()) . ', ';
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
	
	public static function select($addNullRow = false) {
		$objects = array();
		
		$keys = self::listKeys(self::$ALIAS_ORGANISATIONS, 'organisation_id');
		
		$query  = 'SELECT ' . self::quoteKeys($keys);
		$query .= ' FROM ';
		$query .= self::quoteTable(self::$TABLE, self::$ALIAS_ORGANISATIONS);
		$query .= ' LEFT JOIN ';
		$query .= self::quoteTable(Address::$TABLE, self::$ALIAS_ADDRESSES);
		$query .= ' ON ';
		$query .= self::quoteForeignKey(
				self::$KEY_ADDRESS, self::$ALIAS_ORGANISATIONS);
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
				self::$KEY_CONTACT_DETAILS, self::$ALIAS_ORGANISATIONS);
		$query .= ' = ';
		$query .= self::quoteForeignKey(
				ContactDetails::$KEY_ID, self::$ALIAS_CONTACT_DETAILS);
		
		$statement = getDB() -> prepare($query);
		$result = DBConnection::query($statement);
		
		if (!$result)
			return $objects;
		
		if ($addNullRow)
			$objects[] = null;
		
		while ($row = $statement -> fetch(PDO::FETCH_ASSOC)) {
			$object = new Organisation();
			$object -> load($row, 'organisation_id');
			
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