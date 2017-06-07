<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

class Session {
	
	public static $KEY_PAGE_VISITS = 'pages';
	
	public function __construct() {
		if (!isset($_SESSION[self::$KEY_PAGE_VISITS]) ||
			!is_array($_SESSION[self::$KEY_PAGE_VISITS])) {
			$_SESSION[self::$KEY_PAGE_VISITS] = array();
		}
	}
	public function __clone() {}
	
	public function addPageVisit() {
		$_SESSION[self::$KEY_PAGE_VISITS][] = $_SERVER['REQUEST_URI'];
	}
}

?>