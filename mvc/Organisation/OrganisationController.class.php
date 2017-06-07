<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Controller');
requireClass('lib/mvc/ControllerAction');
requireMVC('Organisation');

class OrganisationController extends Controller {
	
	public static $IDENTIFIER = 'Organisation';
	
	function __construct(OrganisationModel $model) {
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