<?php

function endsWith($string, $end, $ignoreCase = true) {
	if (($length = strlen($end)) == 0)
		return true;
	
	if ($ignoreCase) {
		$string = strtolower($string);
		$end = strtolower($end);
	}
	
	return (substr($string, -$length) === $end);
}

function detectEncoding($input) {
	return mb_detect_encoding($input, mb_detect_order(), true);
}

function convertEncoding($input, $encoding) {
	return iconv($encoding, 'UTF-8', $input);
}

function convertToUTF8($input) {
	if (is_array($input)) { 
		$encodings = array_map('detectEncoding', $input);
		
		$encoding = false;
		foreach ($encodings as $detected) {
			if ($detected !== false) {
				$encoding = $detected;
				break;
			}
		}
		
		if ($encoding === false) {print_r($encodings);print $encoding;exit;}
		
		return array_map(
				array(new Converter($encoding), 'map'), $input);
	}
	
	$encoding = detectEncoding($input);
	
	if ($encoding === false)
		return $input;
	
	return convertEncoding($input, $encoding);
}

class Converter {
	
	private $encoding;
	
	public function __construct($encoding) {
		$this -> encoding = $encoding;
	}
	
	public function map($input) {
		if (is_array($input)) {
			for ($i = 0; $i < sizeof($input); $i++) {
				$input[$i] = convertEncoding($input[$i], $this -> encoding);
			}
		}
		else
			$input = convertEncoding($input, $this -> encoding);
		
		return $input;
	}
}