<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/db/DBEnabled');
requireClass('lib/contact/Person');
requireClass('lib/contact/Department');
requireClass('lib/contact/Song');

class Registration extends DBEnabled {
	
	/**
	 * An instance of the SQL table that is represented by this class.
	 * @var tableInstance an instance of the Table class for this datastore.
	 */
	private static $tableInstance		= null;
	
	public static $TABLE			= 'registrations';
	public static $TABLE_ALIAS		= 'reg';
	
	public static $KEY_PERSON		= 'person';
	public static $KEY_DEPARTMENT	= 'department';
	public static $KEY_ATTENDANCE	= 'attendance';
	public static $KEY_SONG1		= 'song1';
	public static $KEY_SONG2		= 'song2';
	public static $KEY_SONG3		= 'song3';
	public static $KEY_REMARKS		= 'remarks';
	
	private $contact = null;
	private $department = null;
	private $attendance = null;
	private $allergies = array();
	private $song1 = null;
	private $song2 = null;
	private $song3 = null;
	private $remarks = '';
	
	public function __construct(
			Person $contact = null,
			Department $department = null,
			Attendance $attendance = null,
			array $allergies = array(),
			Song $song1 = null,
			Song $song2 = null,
			Song $song3 = null,
			$remarks = '') {
		$this -> setContact($contact);
		$this -> setDepartment($department);
		$this -> setAttendance($attendance);
		$this -> setAllergies($allergies);
		$this -> setSong1($song1);
		$this -> setSong2($song2);
		$this -> setSong3($song3);
		$this -> setRemarks($remarks);
	}
	public function __clone() {}
	
	public static function getTable() {
		if (self::$tableInstance == null) {
			$table = new Table(self::$TABLE, self::$TABLE_ALIAS);
			
			$table -> parsePrimaryKey(self::$KEY_ID);
			$table -> parseForeignKey(
					self::$KEY_PERSON, Person::getTable());
			$table -> parseForeignKey(
					self::$KEY_DEPARTMENT, Department::getTable());
			$table -> parseColumn(self::$KEY_ATTENDANCE);
			$table -> parseForeignKey(
					self::$KEY_SONG1, Song::getTable());
			$table -> parseForeignKey(
					self::$KEY_SONG2, Song::getTable());
			$table -> parseForeignKey(
					self::$KEY_SONG3, Song::getTable());
			$table -> parseColumn(self::$KEY_REMARKS, ColumnType::getText());
			
			self::$tableInstance = $table;
		}
		
		return self::$tableInstance;
	}
	
	public static function getTable() {
		if (!$this -> hasSQLTable()) {
			$table = new Table(self::$TABLE);
			
			$table -> parsePrimaryKey(self::$KEY_ID);
			
			$this -> setSQLTable($table);
		}
		
		return $this -> getSQLTable();
	}
	
	public function setContact(Person $contact) {
		$this -> contact = $contact;
	}
	public function getContact() {
		return $this -> contact;
	}
	
	public function setDepartment(Department $department = null) {
		$this -> department = $department;
	}
	public function getDepartment() {
		return $this -> department;
	}
	
	public function setAttendance(Attendance $attendance = null) {
		$this -> attendance = $attendance;
	}
	public function getAttendance() {
		return $this -> attendance;
	}
	
	public function setAllergies(array $allergies = array()) {
		foreach ($allergies as $allergy) {
			$this -> addAllergy($allergy);
		}
	}
	public function addAllergy($allergy = '') {
		$this -> allergies[] = $allergy;
	}
	public function getAllergies() {
		return $this -> allergies;
	}
	
	public function setSong1(Song $song1 = null) {
		$this -> song1 = $song1;
	}
	public function getSong1() {
		return $this -> song1;
	}
	
	public function setSong2(Song $song2 = null) {
		$this -> song2 = $song2;
	}
	public function getSong2() {
		return $this -> song2;
	}
	
	public function setSong3(Song $song3 = null) {
		$this -> song3 = $song3;
	}
	public function getSong3() {
		return $this -> song3;
	}
	
	public function setRemarks($remarks = '') {
		$this -> remarks = $remarks;
	}
	public function getRemarks() {
		return $this -> remarks;
	}
}

class Attendance {
	
	private static $AT_NO =
			'Ik ben niet aanwezig';
	private static $AT_18 =
			'Ik ben aanwezig en eet mee om 18.00 uur';
	private static $AT_22 =
			'Ik ben aanwezig en eet mee om 22.00 uur (halal)';
	private static $AT_20 =
			'Ik ben een ex-medewerker van Levanto en ben om 20.00 uur aanwezig';
	
	public static $INDEX_NO = 0;
	public static $ATTENDANCE_NO = null;
	
	public static $INDEX_18 = 1;
	public static $ATTENDANCE_18 = null;
	
	public static $INDEX_22 = 2;
	public static $ATTENDANCE_22 = null;
	
	public static $INDEX_20 = 3;
	public static $ATTENDANCE_20 = null;
	
	private $index = 0;
	private $label = 0;
	
	private function __construct($index, $label) {
		$this -> index = $index;
		$this -> label = $label;
	}
	
	public function getIndex() {
		return $this -> index;
	}
	public function getLabel() {
		return $this -> label;
	}
	
	public static function getNotAttending() {
		if (self::$ATTENDANCE_NO == null)
			self::$ATTENDANCE_NO = new Attendance(
					self::$INDEX_NO, self::$AT_NO);
		
		return self::$ATTENDANCE_NO;
	}
	public static function getAttendingAt18() {
		if (self::$ATTENDANCE_18 == null)
			self::$ATTENDANCE_18 = new Attendance(
					self::$INDEX_18, self::$AT_18);
		
		return self::$ATTENDANCE_18;
	}
	public static function getAttendingAt22() {
		if (self::$ATTENDANCE_22 == null)
			self::$ATTENDANCE_22 = new Attendance(
					self::INDEX_22, self::$AT_22);
			
			return self::$ATTENDANCE_22;
	}
	public static function getAttendingAt20() {
		if (self::$ATTENDANCE_20 == null)
			self::$ATTENDANCE_20 = new Attendance(
					self::INDEX_20, self::$AT_20);
			
			return self::$ATTENDANCE_20;
	}
	
	public static function getAttendance($label) {
		$label = strtolower($label);
		
		switch ($label) {
			case strtolower(self::$AT_18):
				return self::getAttendingAt18();
			case strtolower(self::$AT_22):
				return self::getAttendingAt22();
			case strtolower(self::$AT_20):
				return self::getAttendingAt20();
			case strtolower(self::$AT_NO):
			default:
				return self::getNotAttending();
		}
	}
	
	public static function parseAttendance($index) {
		switch ($index) {
			case self::$AT_18:
				return self::getAttendingAt18();
			case self::$AT_22:
				return self::getAttendingAt22();
			case self::$AT_20:
				return self::getAttendingAt20();
			case self::$AT_NO:
			default:
				return self::getNotAttending();
		}
	}
}