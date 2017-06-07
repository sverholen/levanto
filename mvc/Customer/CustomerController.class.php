<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Controller');
requireClass('lib/mvc/ControllerAction');
requireMVC('Customer');

class CustomerController extends Controller {
	
	public static $IDENTIFIER = 'Customer';
	
	public function __construct(CustomerModel $model) {
		$this -> setIdentifier(self::$IDENTIFIER);
		$this -> setModel($model);
	}
	public function __clone() {}
	
	public function process(
			ControllerAction $action, array $input, array $files) {
		$result = false;
		
		$this -> getModel() -> load($input);
		
		if ($action == ControllerAction::$CREATE) {
			$result = $this -> getModel() -> getCustomer() -> create();
		}
		
		return $result;
	}
}

?>