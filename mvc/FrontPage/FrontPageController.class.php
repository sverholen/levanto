<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Controller');
requireClass('lib/mvc/ControllerAction');

class FrontPageController extends Controller {
	
	public static $IDENTIFIER = 'FrontPage';
	
	public function __construct(FrontPageModel $model) {
		$this -> setIdentifier(self::$IDENTIFIER);
		$this -> setModel($model);
	}
	public function __clone() {}
	
	public function process(
			ControllerAction $action, array $input, array $files) {
		
	}
}