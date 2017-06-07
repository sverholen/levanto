<?php

require_once('AbstractLogger.class.php');

/**
 * Helper class to allow for logging to multiple channels.
 * 
 * @author metastable
 */
class LoggerManager extends AbstractLogger {
	
	function __construct() {}
	function __clone() {}
	
	private static $instance = null;
	private $loggers = array();
	
	public static function getInstance() {
		if (!isset(self::$instance))
			self::$instance = new LoggerManager();
		
		return self::$instance;
	}
	
	public function addLogger(AbstractLogger $logger) {
		$this -> loggers[] = $logger;
	}
	
	public function log($message, $level, $source = '', $context = array()) {
		foreach ($this -> logger as $logger) {
			$logger -> log($message, $level, $source, $context);
		}
	}
}