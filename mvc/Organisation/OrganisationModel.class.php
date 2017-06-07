<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Model');
requireClass('lib/contact/Organisation');
requireMVC('Organisation');

class OrganisationModel extends Model {
	
	private $organisation;
	
	public function __construct() {}
	public function __clone() {}
	
	public function setOrganisation(Organisation $organisation) {
		$this -> organisation = $organisation;
	}
	public function getOrganisation() {
		return $this -> organisation;
	}
	
	public function load(array $data, array $files = array()) {
		if ($this -> getOrganisation() === null)
			$this -> setOrganisation(new Organisation());
		
		$this -> getOrganisation() -> load($data);
	}
	
	public function loadAll() {
		return Organisation::select();
	}
	
	public function toString() {
		return $this -> getOrganisation() -> toString();
	}
}

?>