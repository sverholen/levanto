<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/db/DBEnabled');

class Songs extends DBEnabled {
	
	/**
	 * An instance of the SQL table that is represented by this class.
	 * @var tableInstance an instance of the Table class for this datastore.
	 */
	private static $tableInstance		= null;
	
	public static $TABLE				= 'songs';
	public static $TABLE_ALIAS			= 'son';
	
	public static $KEY_TITLE			= 'title';
	public static $KEY_ARTIST			= 'artist';
	public static $KEY_FILENAME			= 'filename';
	
	private $title = '';
	private $artist = '';
	
	public function __construct($title = '', $artist = '', $filename = '') {
		$this -> setTitle($title);
		$this -> setArtist($artist);
		$this -> setFilename($filename);
	}
	public function __clone() {}
	
	public static function getTable() {
		if (self::$tableInstance == null) {
			$table = new Table(self::$TABLE, self::$TABLE_ALIAS);
			
			$table -> parsePrimaryKey(self::$KEY_ID);
			$table -> parseColumn(self::$KEY_TITLE);
			$table -> parseColumn(self::$KEY_ARTIST);
			$table -> parseColumn(self::$KEY_FILENAME);
			
			self::$tableInstance = $table;
		}
		
		return self::$tableInstance;
	}
	
	public function setTitle($title = '') {
		$this -> title = $title;
	}
	public function getTitle() {
		return $this -> title;
	}
	
	public function setArtist($artist = '') {
		$this -> artist = $artist;
	}
	public function getArtist() {
		return $this -> artist;
	}
	
	public function setFilename($filename = '') {
		$this -> filename = $filename;
	}
	public function getFilename() {
		return $this -> filename;
	}
}