<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Model');
requireClass('lib/contact/Person');
requireMVC('Person');

class PersonModel extends Model {
	
	private $person;
	
	public function __construct() {}
	public function __clone() {}
	
	public function setPerson(Person $person) {
		$this -> person = $person;
	}
	public function getPerson() {
		return $this -> person;
	}
	
	public function load(array $data, array $files = array()) {
		if ($this -> getPerson() === null)
			$this -> setPerson(new Person());
			
			$this -> getPerson() -> load($data);
	}
	
	public function loadAll() {
		return Person::select();
	}
	
	public function toString() {
		return $this -> getPerson() -> toString();
	}
}

?>