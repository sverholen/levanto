<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Model');
requireClass('lib/accounting/Account');
requireMVC('Account');

class AccountModel extends Model {
	
	private $account;
	
	public function __construct() {}
	public function __clone() {}
	
	public function setAccount(Account $account) {
		$this -> account = $account;
	}
	public function getAccount() {
		return $this -> account;
	}
	
	public function load(array $data, array $files = array()) {
		if ($this -> getAccount() === null)
			$this -> setAccount(new Account());
		
		$this -> getAccount() -> load($data);
	}
	
	public function loadAll() {
		return Account::select();
	}
	
	public function toString() {
		return $this -> getAccount() -> toString();
	}
}

?>