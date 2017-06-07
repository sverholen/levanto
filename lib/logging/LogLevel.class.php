<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

abstract class LogLevel {
	
	const FATAL		= 0;
	const ALERT		= 1;
	const CRITICAL	= 2;
	const ERROR		= 3;
	const WARNING	= 4;
	const NOTICE	= 5;
	const INFO		= 6;
	const DEBUG		= 7;
	
	private static $LEVELS = array(
			self::FATAl,
			self::ALERT,
			self::CRITICAL,
			self::ERROR,
			self::WARNING,
			self::NOTICE,
			self::INFO,
			self::DEBUG
	);
	
	public static function validate($threshold) {
		return true;
	}
}