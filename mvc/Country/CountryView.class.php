<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('tools/HTML');
requireMVC('Country');

class CountryView extends View {
	
	public function __construct(
			CountryController $controller,
			CountryModel $model) {
		$this -> setController($controller);
		$this -> setModel($model);
	}
	
	public function output() {
		print HTML::header('Voeg een land toe', 'formheader');
	}
}

?>