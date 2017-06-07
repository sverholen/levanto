<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('tools/HTML');
requireMVC('Person');

class PersonListView extends View {
	
	public function __construct(
			PersonController $controller,
			PersonModel $model) {
		$this -> setController($controller);
		$this -> setModel($model);
	}
	
	public function output() {
		$people = $this -> getModel() -> loadAll();
		$size = sizeof($people);
		
											print HTML::tab();
		print HTML::form('index.php');
											print HTML::tab();
		print HTML::header('Contactpersonen', 'formheader');
		
		for ($i = 0; $i < $size; $i++) {
			print '<div>' . $people[$i] -> toString() . '</div>';
		}
	}
}

?>