<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

require_once('AbstractLogger.class.php');

class FileLogger extends AbstractLogger {

	function __clone() {}
	
	private $logFilePath;
	private $logLineCount = 0;
	
	private $logFileHandle = null;
	
	/**
	 * Octal notation for default permissions of log file (rw for everyone).
	 * @var integer the octal notation for file permissions.
	 */
	private $defaultFilePermissions = 0666;
	
	public function __construct(
			$logDirectory,
			$threshold = LogLevel::DEBUG,
			$options = array()) {
		
	}
	
	public function log($message, $level, $source = '', $context = array()) {
		
	}
}