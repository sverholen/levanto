<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('tools/MenuContainer');

abstract class Page {
	
	private $currentView = null;
	
	private $stylesheets = array();
	private $preloadScripts = array();
	private $postloadScripts = array();
	
	public function __construct() {}
	public function __clone() {}
	
	public function setCurrentView(View $currentView) {
		$this -> currentView = $currentView;
	}
	public function getCurrentView() {
		return $this -> currentView;
	}
	public function hasCurrentView() {
		return $this -> getCurrentView() != null;
	}
	
	public abstract function printHTMLStart();
	public abstract function printHeader();
	public abstract function printMenu(MenuContainer $menu = null);
	public abstract function printContentSection();
	public abstract function printFooter();
	public abstract function printHTMLEnd();
	
	public function output(MenuContainer $menu = null) {
		$this -> printHTMLStart();
		$this -> printHeader();
		$this -> printMenu($menu);
		$this -> printContentSection();
		$this -> printFooter();
		$this -> printHTMLEnd();
	}
	
	public function setStylesheets(array $stylesheets) {
		$this -> stylesheets = $stylesheets;
	}
	public function addStylesheet($stylesheet) {
		$this -> stylesheets[] = $stylesheet;
	}
	public function getStylesheets() {
		return $this -> stylesheets;
	}
	
	public function setPreloadJavascripts(array $scripts) {
		$this -> preloadScripts = $scripts;
	}
	public function addPreloadJavascript($script) {
		$this -> preloadScripts[] = $script;
	}
	public function getPreloadJavascripts() {
		return $this -> preloadScripts;
	}
	
	public function setPostloadJavascripts(array $scripts) {
		$this -> postloadScripts = $scripts;
	}
	public function addPostloadJavascript($script) {
		$this -> postloadScripts[] = $script;
	}
	public function getPostloadJavascripts() {
		return $this -> postloadScripts;
	}
}

?>