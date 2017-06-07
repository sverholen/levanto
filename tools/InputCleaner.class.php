<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

class InputCleaner {
	
	private function __construct() {}
	private function __clone() {}
	
	public static function cleanString($str) {
		return $str;
	}
	
	public static function cleanEmailAddress($email) {
		//$email = self::cleanString($email);
		
		//return preg_replace('//', '', $email);
		// @todo write email cleaner function
		return $email;
	}
	public static function isValidEmailAddress($email) {
		// @TODO: doesn't seem to work with valid e-mail addresses.
		//return filter_var($email, FILTER_VALIDATE_EMAIL);
		//return $email;
		return true;
	}
	
	public static function cleanPhoneNumber($phone) {
		//$phone = self::cleanString($phone);
		
		//return preg_replace('//', '', $phone);
		// @todo write phone number cleaner function
		return $phone;
	}
	public static function isValidPhoneNumber($phone) {
		// @todo validate phone number
		return true;
	}
	
	public static function cleanCellphoneNumber($cell) {
		//$cell = self::cleanString($cell);
		
		//return preg_replace('//', '', $cell);
		// @todo write cellphone number cleaner
		return $cell;
	}
	public static function isValidCellphoneNumber($cell) {
		// @todo validate cell phone number
		return true;
	}
}