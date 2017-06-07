<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Controller');
requireClass('lib/mvc/ControllerAction');
requireMVC('Organisation');

requireScript('tools/text');

class ImportController extends Controller {
	
	public static $IDENTIFIER = 'Import';
	
	function __construct(ImportModel $model) {
		$this -> setIdentifier(self::$IDENTIFIER);
		$this -> setModel($model);
	}
	function __clone() {}
	
	public function process(
			ControllerAction $action, array $input, array $files) {
		$result = false;
		
		$type = $input['type'];
		
		//requireMVC($input['type']);
		
		ini_set('auto_detect_line_endings', true);
		$handle = fopen($files['datafile']['tmp_name'],'r');
		
		$objects = array();
		$keys = array();
		$counter = -1;
		
		while (($data = fgetcsv($handle)) !== false) {
			$counter++;
			
			$data = array_map('utf8_encode', $data);
			
			if ($counter == 0) {
				$keys = $data;
				
				continue;
			}
			
			$row = array();
			$size = sizeof($data);
			for ($i = 0; $i < $size; $i++) {
				$row[$keys[$i]] = $data[$i];
			}
			
			switch ($type) {
				case 'organisation':
					$objects[] = $this -> processOrganisation($row); break;
				case 'person':
					$objects[] = $this -> processPerson($row); break;
				case 'customer':
					$objects[] = $this -> processCustomer($row); break;
				case 'country':
					$objects[] = $this -> processCountry($row); break;
				case 'levanto':
					$objects[] = $this -> processLevanto($row); break;
			}
		}
		
		ini_set('auto_detect_line_endings', false);
		
		return $objects;
	}
	
	private function processOrganisation(array $data) {
		requireClass('lib/contact/Organisation');
	}
	
	private function processPerson(array $data) {
		requireClass('lib/contact/Person');
	}
	
	private function processCustomer(array $data) {
		requireClass('lib/contact/Customer');
	}
	
	private function processCountry(array $data) {
		requireClass('lib/contact/Country');
		
		$object = new Country();
		$object -> load($data);
		
		$object -> create();
		
		return $object;
	}
	
	private function processLevanto(array $data) {
		requireClass('lib/contact/Registration');
		
		$object = new Registration();
		$object -> load($data);
		
		$object -> create();
		
		return $object;
	}
}

?>