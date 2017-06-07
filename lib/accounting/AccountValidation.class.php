<?php

class AccountValidator {
	
	public function __construct() {}
	public function __clone() {}
	
	public static $PATTERN_BIC
	= '^([a-zA-Z]){4}([a-zA-Z]){2}([0-9a-zA-Z]){2}([0-9a-zA-Z]{3})?$';
	public static $BIC_HEAD_OFFICE = 'XXX';
	
	public static $IBAN_COUNTRIES
	= array(
			'al' => 28,
			'ad' => 24,
			'at' => 20,
			'az' => 28,
			'bh' => 22,
			'be' => 16,
			'ba' => 20,
			'br' => 29,
			'bg' => 22,
			'cr' => 21,
			'hr' => 21,
			'cy' => 28,
			'cz' => 24,
			'dk' => 18,
			'do' => 28,
			'ee' => 20,
			'fo' => 18,
			'fi' => 18,
			'fr' => 27,
			'ge' => 22,
			'de' => 22,
			'gi' => 23,
			'gr' => 27,
			'gl' => 18,
			'gt' => 28,
			'hu' => 28,
			'is' => 26,
			'ie' => 22,
			'il' => 23,
			'it' => 27,
			'jo' => 30,
			'kz' => 20,
			'kw' => 30,
			'lv' => 21,
			'lb' => 28,
			'li' => 21,
			'lt' => 20,
			'lu' => 20,
			'mk' => 19,
			'mt' => 31,
			'mr' => 27,
			'mu' => 30,
			'mc' => 27,
			'md' => 24,
			'me' => 22,
			'nl' => 18,
			'no' => 15,
			'pk' => 24,
			'ps' => 29,
			'pl' => 28,
			'pt' => 25,
			'qa' => 29,
			'ro' => 24,
			'sm' => 27,
			'sa' => 24,
			'rs' => 22,
			'sk' => 24,
			'si' => 19,
			'es' => 24,
			'se' => 24,
			'ch' => 21,
			'tn' => 24,
			'tr' => 26,
			'ae' => 23,
			'gb' => 22,
			'vg' => 24);
	
	public static $IBAN_CHARS
	= array(
			'a' => 10,
			'b' => 11,
			'c' => 12,
			'd' => 13,
			'e' => 14,
			'f' => 15,
			'g' => 16,
			'h' => 17,
			'i' => 18,
			'j' => 19,
			'k' => 20,
			'l' => 21,
			'm' => 22,
			'n' => 23,
			'o' => 24,
			'p' => 25,
			'q' => 26,
			'r' => 27,
			's' => 28,
			't' => 29,
			'u' => 30,
			'v' => 31,
			'w' => 32,
			'x' => 33,
			'y' => 34,
			'z' => 35);
	
	public static function isValidBIC($bic) {
		return iregi(self::$PATTERN_BIC, $bic);
	}
	
	public static function isHeadOffice($bic) {
		return	self::isValidBIC($bic) &&
				endsWith($bic, self::$BIC_HEAD_OFFICE, true);
	}
	
	public static function isValidIBAN($iban) {
		$iban = strtolower(str_replace(' ', '', $iban));
		
		/* Empty string, return false. */
		if (strlen($iban) == 0) return false;
		
		$ibanCountry = substr($iban, 0, 2);
		/* Nonsensical input: IBAN country is not in the list, return false. */
		if (!in_array($ibanCountry, self::$IBAN_COUNTRIES))
			return false;
		
		/*
		 * IBAN length matches the specified length for the designated country.
		 */
		if (strlen($iban) == self::$IBAN_COUNTRIES[$ibanCountry]) {
			$movedChar = substr($iban, 4) . substr($iban, 0, 4);
			$chars = str_split($movedChar);
			$newString = '';
			
			foreach ($chars as $key => $value){
				if (!is_numeric($chars[$key])){
					$chars[$key] = self::$IBAN_CHARS[$chars[$key]];
				}
				
				$newString .= $chars[$key];
			}
			
			if (bcmod($newString, '97') == 1)
				return true;
		}
		
		return false;
	}
	
	public static function isValidAccount($iban, $bic = '') {
		return self::isValidIBAN($iban) &&
			(strlen($bic) > 0 ? self::isValidBIC($bic) : true);
	}
}
?>