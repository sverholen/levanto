<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('lib/contact/Organisation');
requireClass('lib/contact/ContactDetails');
requireMVC('Organisation');

class ImportView extends View {
	
	public function __construct(
			ImportController $controller,
			ImportModel $model) {
		$this -> setController($controller);
		$this -> setModel($model);
	}
	public function __clone() {}
	
	public function output() {
		print HTML::tab();
		print HTML::form('index.php');
		print HTML::tab();
		print HTML::header('Importeer gegevens', 'formheader');
		
		print HTML::line('<select id="type" name="type">');
		print HTML::tab();
		print HTML::option('Organisaties', 'organisation', true);
		print HTML::option('Contactpersonen', 'person');
		print HTML::option('Database klanten', 'customer');
		print HTML::option('ISO-landen', 'country');
		print HTML::option('Levanto-dag', 'levanto');
		print HTML::untab();
		print HTML::line('</select>');
		
		print HTML::fileinput('Data-bestand (CSV)', 'datafile');
		
		print HTML::submitLine('Verzenden', 'submit');
		print HTML::hiddenLine(HTML::$FORM_IDENTIFIER, 'import');
		print HTML::hiddenLine(HTML::$FORM_ACTION, 'create');
		print HTML::untab();
		print HTML::line('</form>');
		print HTML::untab();
	}
}

?>