<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Model');
requireClass('lib/contact/Customer');
requireMVC('Customer');

class CustomerModel extends Model {
	
	private $object;
	
	public function __construct() {}
	public function __clone() {}
	
	public function setCustomer(Customer $object) {
		$this -> object = $object;
	}
	public function getCustomer() {
		return $this -> object;
	}
	
	public function load(array $data, array $files = array()) {
		if ($this -> getCustomer() === null)
			$this -> setCustomer(new Customer());
			
			$this -> getCustomer() -> load($data);
	}
	
	public function loadAll() {
		return Customer::select();
	}
	
	public function toString() {
		return $this -> getCustomer() -> toString();
	}
}

?>