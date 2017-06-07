<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

class FrontPageView extends View {
	
	function __construct(
			FrontPageController $controller,
			FrontPageModel $model) {
		$this -> setController($controller);
		$this -> setModel($model);
	}
	function __clone() {}
	
	public function output() {
		return '<h1>TeST</H1>';
	}
}