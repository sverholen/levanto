<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

abstract class Model {
	
	private function __construct() {}
	private function __clone() {}
	
	public abstract function load(array $data, array $files = array());
	public abstract function toString();
}
?>