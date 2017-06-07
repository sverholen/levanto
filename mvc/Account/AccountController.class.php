<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Controller');
requireClass('lib/mvc/ControllerAction');
requireMVC('Account');

class AccountController extends Controller {
	
	public static $IDENTIFIER = 'Account';
	
	public function __construct(AccountModel $model) {
		$this -> setIdentifier(self::$IDENTIFIER);
		$this -> setModel($model);
	}
	public function __clone() {}
	
	public function process(
			ControllerAction $action, array $input, array $files) {
		$result = false;
		
		$this -> getModel() -> load($input);
		
		if ($action == ControllerAction::$CREATE) {
			$result = $this -> getModel() -> getAccount() -> create();
		}
		
		return $result;
	}
}

?>