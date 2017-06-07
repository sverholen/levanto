<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Controller');
requireClass('lib/mvc/ControllerAction');
requireMVC('CustomerInput');

class CustomerInputController extends Controller {
	
	public static $IDENTIFIER = 'CustomerInput';
	
	public function __construct(CustomerInputModel $model) {
		$this -> setIdentifier(self::$IDENTIFIER);
		$this -> setModel($model);
	}
	public function __clone() {}
	
	public function process(
			ControllerAction $action, array $input, array $files) {
		$result = false;
		
		$this -> getModel() -> load($input);
		//print_r($input);print "\r\n\r\n\r\n\r\n\r\n";print_r($this -> getModel());exit;
		
		if ($action == ControllerAction::$CREATE) {
			$result = $this -> getModel() -> getOrganisation() -> create();
			
			if (!$result)
				print 'ERROR';
			
			if (isset($input[Customer::$PREFIX. ContactDetails::$KEY_ENABLE]) &&
				$input[Customer::$PREFIX . ContactDetails::$KEY_ENABLE]) {
				$contactDetails =
				$this -> getModel() -> getOrganisation() -> getContactDetails();
				
				$this -> getModel() -> getCustomer() -> setContactDetails(
					$contactDetails);
			}
			
			if (isset($input[Customer::$PREFIX. Address::$KEY_ENABLE]) &&
				$input[Customer::$PREFIX . Address::$KEY_ENABLE]) {
				$address =
				$this -> getModel() -> getOrganisation() -> getAddress();
				
				$this -> getModel() -> getCustomer() -> setAddress($address);
			}
			
			$result = $this -> getModel() -> getCustomer() -> create();
		}
		
		return $result;
	}
}

?>