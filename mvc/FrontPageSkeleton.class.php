<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Page');
requireClass('tools/HTML');

class FrontPageSkeleton extends Page {
	
	public function __construct() {}
	public function __clone() {}
	
	public function printHTMLStart() {
		print HTML::line('<!DOCTYPE html>', true);
		print HTML::line('<html lang="nl">');
		HTML::tab();
		print HTML::line('<head>');
		HTML::tab();
		print HTML::line('<meta charset="utf-8">', true);
		print HTML::line('<title>LEVANTO Rekening courant</title>', true);
		print HTML::line('<meta name="description" ' .
				'content="LEVANTO Rekening courant">');
		print HTML::line('<meta name="author" ' .
				'content="Stijn Verholen/metastable (sverholen@gmail.com)">');
		print HTML::line('<meta name="viewport" ' .
				'content="width=device-width, initial-scale=1.0">', true);
		
		foreach ($this -> getStylesheets() as $stylesheet) {
			print HTML::line('<link type="text/css" rel="stylesheet" href="' .
					$stylesheet . '">');
		}
		
		print HTML::line();
		print HTML::line('<!--[if lt IE 9]>');
		HTML::tab();
		print HTML::script(HTML::$IE_HTML5);	
		HTML::untab();
		print HTML::line('<![endif]-->');		
		HTML::untab();
		print HTML::line('</head>', true);
	}
	
	public function printHeader() {
		print HTML::line('<body>');
		print HTML::line('<div id="content">');
		HTML::tab();
		
		print HTML::line('<div class="wrapper">');
		HTML::tab();
		
		print HTML::line('<div class="header">');
		HTML::tab();
		print HTML::line('<div class="logo">');
		HTML::tab();
		print HTML::line(
				'<img src="./img/logo.png" alt="Levanto logo" class="logo" />');
		HTML::untab();
		print HTML::line('</div>');
		print HTML::line('<h1>Rekeningen courant</h1>');
		
		HTML::untab();
		print HTML::line('</div>');
		
		HTML::untab();
		print HTML::line('</div>');
		print HTML::line('<div class="divider"></div>');
	}
	
	public function printMenu(MenuContainer $menucontainer = null) {
		if ($menucontainer == null) return '';
		
		$menus = array();
		$items = array();
		
		print HTML::line('<div class="wrapper">');
		HTML::tab();
		
		foreach ($menucontainer -> getMenus() as $menu) {
			foreach ($menu -> getMenuItems() as $item) {
				$items[] = HTML::menuitem(
						$item -> getLabel(),
						$item -> getMVC(),
						$item -> getAction());
			}
			
			$menus[] = HTML::menu($menu -> getLabel(), $items);
			
			$items = array();
		}
		
		print HTML::menucontainer($menus);
		
		HTML::untab();
		print HTML::line('</div>');
	}
	
	public function printContentSection() {
		print HTML::line('<div class="wrapper">');
		HTML::tab();
		
		print HTML::line('<h1>Rekeningen courant</h1>', true);
		
		if ($this -> hasCurrentView())
			print $this -> getCurrentView() -> output();
			
		HTML::untab();
		print HTML::line('</div>');
	}
	
	public function printFooter() {
		foreach ($this -> getPostloadJavascripts() as $script)
			print HTML::script($script);
	}
	
	public function printHTMLEnd() {
		HTML::untab();
		print HTML::line('</div>');
		print HTML::line('</body>');
		HTML::untab();
		print HTML::line('</html>');
	}
}