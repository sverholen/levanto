<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/contact/Contact');

class Department extends Contact {
	
	/**
	 * An instance of the SQL table that is represented by this class.
	 * @var tableInstance an instance of the Table class for this datastore.
	 */
	private static $tableInstance		= null;
	
	public static $TABLE				= 'departments';
	public static $TABLE_ALIAS			= 'dep';
	
	public static $KEY_NAME				= 'name';
	
	private $name = '';
	
	function __construct(
			$name = '',
			Address $address = null,
			ContactDetails $contactDetails = null) {
		$this -> setName($Name);
		$this -> setAddress($address);
		$this -> setContactDetails($contactDetails);
	}
	public function __clone() {}
	
	public static function getTable() {
		if (self::$tableInstance == null) {
			$table = new Table(self::$TABLE, self::$TABLE_ALIAS);
			
			$table -> parsePrimaryKey(self::$KEY_ID);
			$table -> parseColumn(self::$KEY_NAME);
			$table -> parseForeignKey(
					self::$KEY_ADDRESS, Address::getTable());
			$table -> parseForeignKey(
					self::$KEY_CONTACT_DETAILS, ContactDetails::getTable());
			
			self::$tableInstance = $table;
		}
		
		return self::$tableInstance;
	}
	
	public function setName($name = '') {
		$this -> name = $name;
	}
	public function getName() {
		return $this -> name;
	}
}