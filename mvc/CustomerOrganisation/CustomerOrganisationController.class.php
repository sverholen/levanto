<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Controller');
requireClass('lib/mvc/ControllerAction');
requireMVC('CustomerOrganisation');

class CustomerOrganisationController extends Controller {
	
	public static $IDENTIFIER = 'CustomerOrganisation';
	
	function __construct(CustomerOrganisationModel $model) {
		$this -> setIdentifier(self::$IDENTIFIER);
		$this -> setModel($model);
	}
	function __clone() {}
	
	public function process(
			ControllerAction $action, array $input, array $files) {
		$result = false;
		
		$this -> getModel() -> load($input);
		
		if ($action == ControllerAction::$CREATE) {
			$result = $this -> getModel() -> getOrganisation() -> create();
		}
		
		return $result;
	}
}

?>