<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Controller');
requireClass('lib/mvc/Model');

abstract class View {
	
	private $controller;
	private $model;
	
	private function __construct(
			Controller $controller,
			Model $model) {}
	private function __clone() {}
	
	public function setController(Controller $controller) {
		$this -> controller = $controller;
	}
	public function getController() {
		return $this -> controller;
	}
	
	public function setModel(Model $model) {
		$this -> model = $model;
	}
	public function getModel() {
		return $this -> model;
	}
	
	abstract function output();
}
?>