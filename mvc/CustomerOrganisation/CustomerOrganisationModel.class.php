<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Model');
requireClass('lib/contact/CustomerOrganisation');
requireMVC('CustomerOrganisation');

class CustomerOrganisationModel extends Model {
	
	private $organisation;
	
	public function __construct() {}
	public function __clone() {}
	
	public function setOrganisation(CustomerOrganisation $organisation) {
		$this -> organisation = $organisation;
	}
	public function getOrganisation() {
		return $this -> organisation;
	}
	
	public function load(array $data, array $files = array()) {
		if ($this -> getOrganisation() === null)
			$this -> setOrganisation(new CustomerOrganisation());
			
			$this -> getOrganisation() -> load($data);
	}
	
	public function loadAll() {
		return CustomerOrganisation::select();
	}
	
	public function toString() {
		return $this -> getOrganisation() -> toString();
	}
}

?>