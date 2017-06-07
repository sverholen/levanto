<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

class ControllerAction {
	
	const NOTHING	= 0;
	const CREATE	= 1;
	const READ		= 2;
	const UPDATE	= 3;
	const DELETE	= 4;
	
	public static $NOTHING	= null;
	public static $CREATE	= null;
	public static $READ		= null;
	public static $UPDATE	= null;
	public static $DELETE	= null;
	
	private $type = 0;
	
	private function __construct($type = 0) {
		$this -> type = $type;
	}
	
	public static function load($type = '') {
		switch ($type) {
			case 'create':
				if (self::$CREATE == null)
					self::$CREATE = new ControllerAction($type);
				
				$instance = self::$CREATE; break;
			case 'read':
				if (self::$READ == null)
					self::$READ = new ControllerAction($type);
				
				$instance = self::$READ; break;
			case 'update':
				if (self::$UPDATE == null)
					self::$UPDATE = new ControllerAction($type);
				
				$instance = self::$UPDATE; break;
			case 'delete':
				if (self::$DELETE == null)
					self::$DELETE = new ControllerAction($type);
				
				$instance = self::$DELETE; break;
			case 'nothing': default:
				if (self::$NOTHING == null)
					self::$NOTHING = new ControllerAction($type);
				
				$instance = self::$NOTHING; break;
		}
		
		return $instance;
	}
}

?>