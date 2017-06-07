<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/sql/ColumnType');

class Column extends SQLEntity {
	
	private $type = null;
	
	public function __construct($name = '', ColumnType $type = null) {
		$this -> setName($name);
		$this -> setType($type);
	}
	
	public function setType(ColumnType $type = null) {
		$this -> type = $type;
	}
	public function getType() {
		return $this -> type;
	}
	
	public static function getChar($name) {
		return new Column($name, ColumnType::getChar());
	}
	public static function getVarchar($name) {
		return new Column($name, ColumnType::getVarchar());
	}
	public static function getText($name) {
		return new Column($name, ColumnType::getText());
	}
	public static function getTinyint($name) {
		return new Column($name, ColumnType::getTinyint());
	}
	public static function getInt($name) {
		return new Column($name, ColumnType::getInt());
	}
	public static function getBigint($name) {
		return new Column($name, ColumnType::getBigint());
	}
	public static function getDate($name) {
		return new Column($name, ColumnType::getDate());
	}
	public static function getDatetime($name) {
		return new Column($name, ColumnType::getDateTime());
	}
	public static function parse($name, ColumnType $type) {
		if ($type -> isTypechar())
			return self::getChar($name);
		
		if ($type -> isTypeVarchar())
			return self::getVarchar($name);
		
		if ($type -> isTypeText())
			return self::getText($name);
		
		if ($type -> isTypeTinyint())
			return self::getTinyint($name);
		
		if ($type -> isTypeInt())
			return self::getInt($name);
		
		if ($type -> isTypeBigint())
			return self::getBigint($name);
		
		if ($type -> isTypeDate())
			return self::getDate($name);
		
		if ($type -> isTypeDatetime())
			return self::getDatetime($name);
		
		return null;
	}
	
	public function __toString() {
		return $this -> getName();
	}
}