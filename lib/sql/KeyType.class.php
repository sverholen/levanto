<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

class KeyType {
	
	private static $PRIMARY			= null;
	private static $UNIQUE			= null;
	private static $FOREIGN			= null;
	
	public static $TYPE_NONE		= 0;
	public static $TYPE_PRIMARY		= 1;
	public static $TYPE_FOREIGN		= 2;
	public static $TYPE_UNIQUE		= 3;
	
	public function __construct($type = null) {
		
	}
	
	public function setType($type = 0) {
		switch ($type) {
			case self::$TYPE_PRIMARY: 
				$this -> type = self::$TYPE_PRIMARY; break;
			case self::$TYPE_FOREIGN:
				$this -> type = self::$TYPE_FOREIGN; break;
			case self::$TYPE_UNIQUE:
				$this -> type = self::$TYPE_UNIQUE; break;
			case self::$TYPE_NONE:
			default:
				$this -> type = self::$TYPE_NONE; break;
		}
	}
	public function getType() {
		return $this -> type;
	}
	
	public function isNoneKey() {
		return $this -> isNoneKey() ||
				!($this -> isPrimaryKey() ||
				$this -> isUniqueKey() ||
				$this -> isForeignKey());
	}
	public function isPrimaryKey() {
		return $this -> getType() == self::$TYPE_PRIMARY;
	}
	public function isUniqueKey() {
		return $this -> getType() == self::$TYPE_UNIQUE;
	}
	public function isForeignKey() {
		return $this -> getType() == self::$TYPE_FOREIGN;
	}
	
	public static function getPrimary() {
		if (self::$PRIMARY == null)
			self::$PRIMARY = new KeyType(self::$TYPE_PRIMARY);
		
		return self::$PRIMARY;
	}
	public static function getUnique() {
		if (self::$UNIQUE == null)
			self::$UNIQUE = new KeyType(self::$TYPE_UNIQUE);
		
		return self::$UNIQUE;
	}
	public static function getForeign() {
		if (self::$FOREIGN == null)
			self::$FOREIGN = new KeyType(self::$TYPE_FOREIGN);
		
		return self::$FOREIGN;
	}
}