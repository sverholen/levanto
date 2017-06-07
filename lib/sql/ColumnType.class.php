<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

class ColumnType {
	
	private static $UNKNOWN			= null;
	private static $CHAR			= null;
	private static $VARCHAR			= null;
	private static $TEXT			= null;
	private static $TINYINT			= null;
	private static $INT				= null;
	private static $BIGINT			= null;
	private static $DATE			= null;
	private static $DATETIME		= null;
	
	private static $TYPE_UNKNOWN	= 0;
	private static $NAME_UNKNOWN	= 'UNKNOWN';
	private static $TYPE_CHAR		= 10;
	private static $NAME_CHAR		= 'CHAR';
	private static $TYPE_VARCHAR	= 11;
	private static $NAME_VARCHAR	= 'VARCHAR';
	private static $TYPE_TEXT		= 12;
	private static $NAME_TEXT		= 'TEXT';
	private static $TYPE_TINYINT	= 20;
	private static $NAME_TINYINT	= 'TINYINT';
	private static $TYPE_INT		= 21;
	private static $NAME_INT		= 'INT';
	private static $TYPE_BIGINT		= 22;
	private static $NAME_BIGINT		= 'BIGINT';
	private static $TYPE_DATE		= 30;
	private static $NAME_DATE		= 'DATE';
	private static $TYPE_DATETIME	= 31;
	private static $NAME_DATETIME	= 'DATETIME';
	
	private static $FAMILY_STRING	= 1;
	private static $FAMILY_TIME		= 2;
	private static $FAMILY_NUMERIC	= 3;
	
	private $type;
	private $name;
	
	public function __construct($type) {
		$this -> setType($type);
	}
	
	private function setName($name = '') {
		$this -> name = $name;
	}
	public function getName() {
		return $this -> name;
	}
	
	public function setType($type = 0) {
		switch ($type) {
			case self::$TYPE_CHAR:
				$this -> setTypeChar(); break;
			case self::$TYPE_VARCHAR:
				$this -> setTypeVarchar(); break;
			case self::$TYPE_TEXT:
				$this -> setTypeText(); break;
			case self::$TYPE_TINYINT:
				$this -> setTypeTinyint(); break;
			case self::$TYPE_INT:
				$this -> setTypeInt(); break;
			case self::$TYPE_BIGINT:
				$this -> setTypeBigint(); break;
			case self::$TYPE_DATE:
				$this -> setTypeDate(); break;
			case self::$TYPE_DATETIME:
				$this -> setTypeDatetime(); break;
			case self::$TYPE_UNKNOWN:
			default:
				$this -> setTypeUnknown(); break;
		}
	}
	private function setTypeUnknown() {
		$this -> setName(self::$NAME_UNKNOWN);
		$this -> type = self::$TYPE_UNKNOWN;
		$this -> setFamilyString();
	}
	private function setTypeChar() {
		$this -> setName(self::$NAME_CHAR);
		$this -> type = self::$TYPE_CHAR;
		$this -> setFamilyString();
	}
	private function setTypeVarchar() {
		$this -> setName(self::$NAME_VARCHAR);
		$this -> type = self::$TYPE_VARCHAR;
		$this -> setFamilyString();
	}
	private function setTypeText() {
		$this -> setName(self::$NAME_TEXT);
		$this -> type = self::$TYPE_TEXT;
		$this -> setFamilyString();
	}
	private function setTypeTinyint() {
		$this -> setName(self::$NAME_TINYINT);
		$this -> type = self::$TYPE_TINYINT;
		$this -> setFamilyNumeric();
	}
	private function setTypeInt() {
		$this -> setName(self::$NAME_INT);
		$this -> type = self::$TYPE_INT;
		$this -> setFamilyNumeric();
	}
	private function setTypeBigint() {
		$this -> setName(self::$NAME_BIGINT);
		$this -> type = self::$TYPE_BIGINT;
		$this -> setFamilyNumeric();
	}
	private function setTypeDate() {
		$this -> setName(self::$NAME_DATE);
		$this -> type = self::$TYPE_DATE;
		$this -> setFamilyTime();
	}
	private function setTypeDatetime() {
		$this -> setName(self::$NAME_DATETIME);
		$this -> type = self::$TYPE_DATETIME;
		$this -> setFamilyTime();
	}
	public function getType() {
		return $this -> type;
	}
	
	private function setFamily($family = '') {
		$this -> family = $family;
	}
	private function setFamilyString() {
		$this -> setFamily(self::$FAMILY_STRING);
	}
	private function setFamilyTime() {
		$this -> setFamily(self::$FAMILY_TIME);
	}
	private function setFamilyNumeric() {
		$this -> setFamily(self::$FAMILY_NUMERIC);
	}
	public function getFamily() {
		return $this -> family;
	}
	public function isString() {
		return $this -> getFamily() == self::$FAMILY_STRING;
	}
	public function isTime() {
		return $this -> getFamily() == self::$FAMILY_TIME;
	}
	public function isNumeric() {
		return $this -> getFamily() == self::$FAMILY_NUMERIC;
	}
	
	public function isTypeChar() {
		return $this -> getType() == self::$TYPE_CHAR;
	}
	public function isTypeVarchar() {
		return $this -> getType() == self::$TYPE_VARCHAR;
	}
	public function isTypeText() {
		return $this -> getType() == self::$TYPE_TEXT;
	}
	public function isTypeDate() {
		return $this -> getType() == self::$TYPE_DATE;
	}
	public function isTypeDatetime() {
		return $this -> getType() == self::$TYPE_DATETIME;
	}
	public function isTypeTinyint() {
		return $this -> getType() == self::$TYPE_TINYINT;
	}
	public function isTypeInt() {
		return $this -> getType() == self::$TYPE_INT;
	}
	public function isTypeBigint() {
		return $this -> getType() == self::$TYPE_BIGINT;
	}
	
	public function __toString() {
		return $this -> getName();
	}
	
	public static function parseType($type) {
		$type = strtolower($type);
		
		switch ($type) {
			
		}
		
		return null;
	}
	
	public static function getChar() {
		if (self::$CHAR == null) {
			self::$CHAR = new ColumnType(self::$TYPE_CHAR);
		}
		
		return self::$CHAR;
	}
	public static function getVarchar() {
		if (self::$VARCHAR == null)
			self::$VARCHAR = new ColumnType(self::$TYPE_VARCHAR);
		
		return self::$VARCHAR;
	}
	public static function getText() {
		if (self::$TEXT == null)
			self::$TEXT = new ColumnType(self::$TYPE_TEXT);
		
		return self::$TEXT;
	}
	public static function getDate() {
		if (self::$DATE == null)
			self::$DATE = new ColumnType(self::$TYPE_DATE);
		
		return self::$DATE;
	}
	public static function getDatetime() {
		if (self::$DATETIME == null)
			self::$DATETIME = new ColumnType(self::$TYPE_DATETIME);
		
		return self::$DATETIME;
	}
	public static function getTinyint() {
		if (self::$TINYINT == null)
			self::$TINYINT = new ColumnType(self::$TYPE_TINYINT);
		
		return self::$TINYINT;
	}
	public static function getInt() {
		if (self::$INT == null)
			self::$INT = new ColumnType(self::$TYPE_INT);
			
			return self::$INT;
	}
	public static function getBigint() {
		if (self::$BIGINT == null)
			self::$BIGINT = new ColumnType(self::$TYPE_BIGINT);
			
			return self::$BIGINT;
	}
}

?>