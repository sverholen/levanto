<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/db/DBEnabled');
requireClass('lib/contact/Address');
requireClass('lib/contact/ContactDetails');

abstract class Contact extends DBEnabled {
	
	private $address = null;
	private $contactDetails = null;
	private $accounts = array();
	
	public static $KEY_ADDRESS			= 'address';
	public static $KEY_CONTACT_DETAILS	= 'contact_details';
	
	private function __construct() {}
	private function __clone() {}
	
	public function setAddress(Address $address = null) {
		$this -> address = $address;
	}
	public function getAddress() {
		return $this -> address;
	}
	
	public function setContactDetails(ContactDetails $contactDetails = null) {
		$this -> contactDetails = $contactDetails;
	}
	public function getContactDetails() {
		return $this -> contactDetails;
	}
	
	public function addAccount(Account $account = null) {
		$this -> accounts[] = $account;
	}
	public function addAccounts($accounts = array()) {
		foreach ($accounts as $account)
			$this -> addAccount($account);
	}
	public function getAccounts() {
		return $this -> accounts;
	}
	
	public function load(
			array $data, $idAlias = '', $prefix = '', array $files = array()) {
		$this -> checkID($data, $idAlias);
		
		$address = new Address();
		$address -> load($data, $idAlias, $prefix, $files);
		
		$this -> setAddress($address);
		
		$contactDetails = new ContactDetails();
		$contactDetails -> load($data, $idAlias, $prefix, $files);
		
		$this -> setContactDetails($contactDetails);
	}
	
	public function toString() {
		return $this ->getContactDetails() -> toString();
	}
}