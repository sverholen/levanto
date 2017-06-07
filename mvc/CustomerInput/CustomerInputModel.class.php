<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Model');
requireClass('lib/contact/Customer');
requireClass('lib/contact/CustomerOrganisation');
requireMVC('CustomerInput');

class CustomerInputModel extends Model {
	
	private $organisation;
	private $customer;
	
	public function __construct() {}
	public function __clone() {}
	
	public function setOrganisation(CustomerOrganisation $organisation) {
		$this -> organisation = $organisation;
	}
	public function getOrganisation() {
		return $this -> organisation;
	}
	
	public function setCustomer(Customer $customer) {
		$this -> customer = $customer;
	}
	public function getCustomer() {
		return $this -> customer;
	}
	
	public function load(array $data, array $files = array()) {
		if ($this -> getOrganisation() === null)
			$this -> setOrganisation(new CustomerOrganisation());
		
		$this -> getOrganisation() -> load(
				$data, '', CustomerOrganisation::$PREFIX, $files);
		
		if ($this -> getCustomer() === null)
			$this -> setCustomer(new Customer());
		
		$this -> getCustomer() -> load(
				$data, '', Customer::$PREFIX, $files);
	}
	
	public function loadAll() {
		return CustomerOrganisation::select();
	}
	
	public function toString() {
		return $this -> getOrganisation() -> toString();
	}
}

?>