<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Model');

class FrontPageModel extends Model {
	
	public function __construct() {}
	public function __clone() {}
	
	public function load(array $data, array $files = array()) {}
	public function toString() {}
}