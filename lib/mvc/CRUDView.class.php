<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('lib/db/ICRUD');

class CRUDView extends View implements ICRUD {
	
	public function __construct() {}
	public function __clone() {}
	
	public function create() {
		
	}
	
	public function read() {
		
	}
	
	public function update() {
		
	}
	
	public function delete() {
		
	}
}