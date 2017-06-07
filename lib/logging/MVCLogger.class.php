<?php

require_once('AbstractLogger.class.php');

class MVCLogger extends AbstractLogger {
	
	public function __construct() {}
	public function __clone() {}
	
	private static $instance = null;
	
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new MVCLogger();
		}
		
		return self::$instance;
	}
	
	public function log($message, $level, $source = '', $context = array()) {
		
	}
}