<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Model');
requireClass('lib/contact/Country');
requireMVC('Country');

class CountryModel extends Model {
	
	private $object;
	
	public function __construct() {}
	public function __clone() {}
	
	public function setCountry(Country $object) {
		$this -> object = $object;
	}
	public function getCountry() {
		return $this -> object;
	}
	
	public function load(array $data, array $files = array()) {
		if ($this -> getCountry() === null)
			$this -> setCountry(new Country());
			
			$this -> getCountry() -> load($data);
	}
	
	public function loadAll() {
		return Country::select();
	}
	
	public function toString() {
		return $this -> getCountry() -> toString();
	}
}

?>