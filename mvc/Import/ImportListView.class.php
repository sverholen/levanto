<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('lib/contact/Organisation');
requireClass('lib/contact/ContactDetails');
requireMVC('Organisation');

class ImportListView extends View {
	
	public function __construct(
			ImportController $controller,
			ImportModel $model) {
		$this -> setController($controller);
		$this -> setModel($model);
	}
	public function __clone() {}
	
	public function output() {
		$organisations = $this -> getModel() -> loadAll();
		$size = sizeof($organisations);
		
		print HTML::tab();
		print HTML::form('index.php');
		print HTML::tab();
		print HTML::header('Organisaties', 'formheader');
		
		for ($i = 0; $i < $size; $i++) {
			print '<div>' . $organisations[$i] -> toString() . '</div>';
		}
	}
}

?>