<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('lib/contact/Customer');
requireClass('lib/contact/CustomerOrganisation');
requireMVC('CustomerInput');

class CustomerInputListView extends View {
	
	public function __construct(
			CustomerInputController $controller,
			CustomerInputModel $model) {
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
		print HTML::header('Contactpersonen', 'formheader');
		
		for ($i = 0; $i < $size; $i++) {
			print '<div>' . $organisations[$i] -> toString() . '</div>';
		}
	}
}

?>