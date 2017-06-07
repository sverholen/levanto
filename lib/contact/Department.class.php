<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/contact/Contact');

class Department extends Contact {
	
	private $name = '';
	
	function __construct(
			$name = '',
			Address $address = null,
			ContactDetails $contactDetails = null) {
		$this -> setName($Name);
		$this -> setAddress($address);
		$this -> setContactDetails($contactDetails);
	}
	
	public function setName($name = '') {
		$this -> name = $name;
	}
	public function getName() {
		return $this -> name;
	}
	
	public function load(
			array $data, $idAlias = '', $prefix = '', array $files = array()) {
		
	}
}