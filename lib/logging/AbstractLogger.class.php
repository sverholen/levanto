<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

require_once('LogLevel.class.php');

abstract class AbstractLogger {
	
	abstract function log($message, $level, $source = '', $context = array());
	
	protected $threshold = LogLevel::DEBUG;
	protected function setThreshold($threshold = LogLevel::DEBUG) {
		$this -> threshold = $threshold;
	}
	protected function getThreshold() {
		return $this -> threshold;
	}
	protected function aboveThreshold($threshold = LogLevel::DEBUG) {
		return $this -> getThreshold() <= $threshold;
	}
	
	public function fatal($message, $source = '', $context = array()) {
		if (aboveThreshold(LogLevel::FATAL))
			log($message, $this -> getThreshold(), $source, $context);
	}
	
	public function alert($message, $source = '', $context = array()) {
		if (aboveThreshold(LogLevel::ALERT))
			log($message, $this -> getThreshold(), $source, $context);
	}
	
	public function critical($message, $source = '', $context = array()) {
		if (aboveThreshold(LogLevel::CRITICAL))
			log($message, $this -> getThreshold(), $source, $context);
	}
	
	public function error($message, $source = '', $context = array()) {
		if (aboveThreshold(LogLevel::ERROR))
			log($message, $this -> getThreshold(), $source, $context);
	}
	
	public function warning($message, $source = '', $context = array()) {
		if (aboveThreshold(LogLevel::WARNING))
			log($message, $this -> getThreshold(), $source, $context);
	}
	
	public function notice($message, $source = '', $context = array()) {
		if (aboveThreshold(LogLevel::NOTICE))
			log($message, $this -> getThreshold(), $source, $context);
	}
	
	public function info($message, $source = '', $context = array()) {
		if (aboveThreshold(LogLevel::INFO))
			log($message, $this -> getThreshold(), $source, $context);
	}
	
	public function debug($message, $source = '', $context = array()) {
		if (aboveThreshold(LogLevel::DEBUG))
			log($message, $this -> getThreshold(), $source, $context);
	}
}

?>