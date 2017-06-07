<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('tools/HTML');

class MenuContainer {
	
	private static $instance = null;
	
	private $file = null;
	private $menus = array();
	
	private function __construct($file = null) {
		$this -> setFile($file);
	}
	function __clone() {}
	
	public static function load($file) {
		if (self::$instance == null) {
			self::$instance = new MenuContainer($file);
			self::$instance -> loadMenu();
		}
		
		return self::$instance;
	}
	
	private function setFile($file) {
		$this -> file = $file;
	}
	private function getFile() {
		return $this -> file;
	}
	
	private function addMenu(Menu $menu) {
		if (!in_array($menu, $this -> menus))
			$this -> menus[] = $menu;
		
		return $menu;
	}
	private function getMenu($name) {
		foreach ($this -> menus as $menu)
			if ($menu -> getName() == $name)
				return $menu;
		
		$menu = new Menu($name);
		
		$this -> addMenu($menu);
		
		return $menu;
	}
	public function getMenus() {
		return $this -> menus;
	}
	
	private function loadMenu() {
		ini_set('auto_detect_line_endings', true);
		$handle = fopen($this -> getFile(),'r');
		
		$counter = -1;
		
		while (($data = fgetcsv($handle)) !== false) {
			$counter++;
			
			$data = array_map('utf8_encode', $data);
			
			if ($counter == 0) {
				$keys = $data;
				
				continue;
			}
			
			$menuName = $data[0];
			$submenuName = $data[1];
			$itemName = $data[2];
			$mvc = $data[3];
			$action = $data[4];
			
			$menuItem = new MenuItem(
					$itemName, $itemName, $mvc, $action);
			
			// IGNORE submenus for now
			
			$this -> getMenu($menuName) -> addMenuItem($menuItem);
		}
		
		ini_set('auto_detect_line_endings', false);
	}
}

class Menu {
	
	private $name = '';
	private $label = '';
	
	private $submenus = array();
	private $menuItems = array();
	
	public function __construct($name, $label = '') {
		$this -> name = $name;
		$this -> label = $label ? $label : $name;
	}
	public function __clone() {}
	
	public function getName() {
		return $this -> name;
	}
	public function getLabel() {
		return $this -> label;
	}
	
	public function addSubmenu(Menu $submenu) {
		if (!in_array($submenu, $this -> submenus))
			$this -> submenus[] = $submenu;
	}
	public function getSubmenus() {
		return $this -> submenus;
	}
	
	public function addMenuItem(MenuItem $menuItem) {
		if (!in_array($menuItem, $this -> menuItems))
			$this -> menuItems[] = $menuItem;
	}
	public function getMenuItems() {
		return $this -> menuItems;
	}
}

class MenuItem {
	
	private $name = '';
	private $label = '';
	private $mvc = '';
	private $action = '';
	
	public function __construct($name, $label = '', $mvc, $action) {
		$this -> name = $name;
		$this -> label = $label ? $label : $name;
		$this -> mvc = $mvc;
		$this -> action = $action;
	}
	public function __clone() {}
	
	public function getName() {
		return $this -> name;
	}
	public function getLabel() {
		return $this -> label;
	}
	public function getMVC() {
		return $this -> mvc;
	}
	public function getAction() {
		return $this -> action;
	}
}
?>