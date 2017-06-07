<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('lib/contact/Organisation');
requireClass('tools/HTML');
requireMVC('Account');

class AccountView extends View {
	
	public function __construct(
			AccountController $controller,
			AccountModel $model) {
		$this -> setController($controller);
		$this -> setModel($model);
	}
	
	public function output() {
		$organisations = Organisation::select();
		
											print HTML::tab();
		print HTML::form('index.php');
											print HTML::tab();
		print HTML::header('Voeg een rekening toe', 'formheader');
		
		$lines = array();
		$rows = array();
		$rows[] = HTML::inputLine('Rekening', Account::$KEY_ACCOUNT);
		$lines[] = HTML::fieldsetrow($rows);
		
		$rows = array();
		$rows[] = HTML::inputLine('Bank', Account::$KEY_BANK);
		$lines[] = HTML::fieldsetrow($rows);
		
		print HTML::fieldset('Rekening', $lines);
		
		$lines = array();
		$rows = array();
		$rows[] = HTML::label(
				Account::$KEY_IBAN, 'BIC, IBAN');
		$rows[] = HTML::input(
				Account::$KEY_BIC, '', '', '', 'BIC');
		$rows[] = HTML::input(
				Account::$KEY_IBAN, '', '', '', 'IBAN');
		$lines[] = HTML::fieldsetrow($rows);
		
		$rows = array();
		$rows[] = HTML::inputLine(
				'Rekeningnummer', Account::$KEY_NUMBER);
		$lines[] = HTML::fieldsetrow($rows);
		
		print HTML::fieldset('Gegevens', $lines);
		
		print HTML::submitLine('Verzenden', 'submit');
		print HTML::hiddenLine(HTML::$FORM_IDENTIFIER, 'person');
		print HTML::hiddenLine(HTML::$FORM_ACTION, 'create');
											print HTML::untab();
		print HTML::line('</form>');
											print HTML::untab();
	}
}

?>