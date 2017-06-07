<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('tools/HTML');
requireMVC('Customer');

class CustomerListView extends View {
	
	public function __construct(
			CustomerController $controller,
			CustomerModel $model) {
				$this -> setController($controller);
				$this -> setModel($model);
	}
	
	public function output() {
		$objects = $this -> getModel() -> loadAll();
		$size = sizeof($objects);
		
		print HTML::tab();
		print HTML::form('index.php');
		print HTML::tab();
		print HTML::header('Contactpersonen', 'formheader');
		
		for ($i = 0; $i < $size; $i++) {
			print '<div>' . $objects[$i] -> toString() . '</div>';
		}
	}
}

?>