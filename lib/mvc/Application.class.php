<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Controller');
requireClass('lib/mvc/Model');
requireClass('lib/mvc/View');

class Application {
	
	private static $instance = null;
	
	private $skeleton = null;
	
	private $controller = null;
	private $model = null;
	private $view = null;
	
	private function __construct() {
		
	}
	private function __clone() {}
	
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new Application();
		}
		
		return self::$instance;
	}
	
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
	
	public function setView(View $view) {
		$this -> view = $view;
	}
	public function getView() {
		return $this -> view;
	}
	
	public function hasMVC() {
		return	$this -> getController() != null &&
				$this -> getModel() != null &&
				$this -> getView() != null;
	}
	
	public function getSkeleton() {
		if (!isset($this -> skeleton)) {
			requireClass('mvc/FrontPageSkeleton');
			
			requireMVC('FrontPage');
			
			if (!$this -> hasMVC()) {
				$this -> setModel(new FrontPageModel());
				$this -> setController(
						new FrontPageController($this -> getModel()));
				$this -> setView(new FrontPageView(
						$this -> getController(),
						$this -> getModel()));
			}
			
			$this -> skeleton = new FrontPageSkeleton($this -> getView());
		}
		
		return $this -> skeleton;
	}
}
?>