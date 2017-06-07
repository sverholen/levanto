<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Model');

abstract class Controller {
	
	private $identifier;
	private $model;
	
	private $registeredControllers = array();
	
	private function __contruct(Model $model) {}
	private function __clone() {}
	
	public abstract function process(
			ControllerAction $action, array $input, array $files);
	
	public function setIdentifier($identifier) {
		$this -> identifier = $identifier;
	}
	public function getIdentifier() {
		return $this -> identifier;
	}
	
	public function setModel(Model $model) {
		$this -> model = $model;
	}
	public function getModel() {
		return $this -> model;
	}
	
	function registerControllers(array $controllers) {
		$this -> registeredControllers = $controllers;
	}
	function registerController(Controller $controller) {
		if (!in_array($controller, $this -> registerControllers))
			$this -> registeredControllers[] = $controller;
	}
	function getRegisteredControllers() {
		return $this -> registeredControllers;
	}
	function getRegisteredControllerIdentifiers() {
		$identifiers = array();
		
		foreach ($this -> registeredControllers as $controller) {
			$identifiers[] = $controller -> getIdentifier();
		}
	}
}
?>