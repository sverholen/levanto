<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('tools/HTML');
requireMVC('Account');

class AccountListView extends View {
	
	public function __construct(
			AccountController $controller,
			AccountModel $model) {
		$this -> setController($controller);
		$this -> setModel($model);
	}
	
	public function output() {
		$accounts = $this -> getModel() -> loadAll();
		$size = sizeof($accounts);
		
		print HTML::tab();
		print HTML::form('index.php');
		print HTML::tab();
		print HTML::header('Rekeningen', 'formheader');
		
		for ($i = 0; $i < $size; $i++) {
			print '<div>' . $accounts[$i] -> toString() . '</div>';
		}
	}
}

?>