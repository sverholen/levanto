<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('lib/contact/CustomerOrganisation');
requireMVC('CustomerOrganisation');

class CustomerOrganisationListView extends View {
	
	public function __construct(
			CustomerOrganisationController $controller,
			CustomerOrganisationModel $model) {
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
		print HTML::header('Contactorganisaties', 'formheader');
		
		for ($i = 0; $i < $size; $i++) {
			print '<div>' . $organisations[$i] -> toString() . '</div>';
		}
	}
}

?>