<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('tools/HTML');
requireMVC('Country');

class CountryListView extends View {
	
	public function __construct(
			CountryController $controller,
			CountryModel $model) {
		$this -> setController($controller);
		$this -> setModel($model);
	}
	
	public function output() {
		$objects = $this -> getModel() -> loadAll();
		$size = sizeof($objects);
		
		print HTML::tab();
		print HTML::form('index.php');
		print HTML::tab();
		print HTML::header('Landen', 'formheader');
		
		for ($i = 0; $i < $size; $i++) {
			print HTML::line('<div>' . $objects[$i] -> toString() . '</div>');
		}
		
		print HTML::untab();
		print HTML::line('</form>');
		print HTML::untab();
	}
}

?>