<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('lib/contact/Customer');
requireClass('lib/contact/CustomerOrganisation');
requireMVC('CustomerInput');

requireClass('mvc/widgets/ContactDetailsWidget');
requireClass('mvc/widgets/AddressWidget');

/*
 * Naam bedrijf/organisatie (NL)
 * Voornaam
 * Naam
 * E-mail
 * Functie
 * Straat (NL)
 * Nummer
 * Postcode
 * Gemeente (NL)
 * Land
 * Telefoon
 * Fax
 * GSM
 * E-mail
 * Website
 */
class CustomerInputView extends View {
	
	public function __construct(
			CustomerInputController $controller,
			CustomerInputModel $model) {
				$this -> setController($controller);
				$this -> setModel($model);
	}
	public function __clone() {}
	
	public function output() {
		print HTML::tab();
		print HTML::form('index.php');
		print HTML::tab();
		print HTML::header('Voeg een contactpersoon toe', 'formheader');
		
		$lines = array();
		$rows = array();
		$rows[] = HTML::inputLine(
				'Organisatie',
				CustomerOrganisation::$PREFIX . Organisation::$KEY_ORGANISATION);
		$lines[] = HTML::fieldsetrow($rows);
		
		$rows = array();
		$rows[] = HTML::inputLine(
				'Volledige naam',
				CustomerOrganisation::$PREFIX . Organisation::$KEY_FULL_NAME);
		$lines[] = HTML::fieldsetrow($rows);
		
		$rows = array();
		$rows[] = HTML::inputLine(
				'BTW-nummer',
				CustomerOrganisation::$PREFIX . Organisation::$KEY_VAT_NUMBER);
		$lines[] = HTML::fieldsetrow($rows);
		
		print HTML::fieldset('Organisatie', $lines);
		
		ContactDetailsWidget::output(CustomerOrganisation::$PREFIX);
		AddressWidget::output(CustomerOrganisation::$PREFIX);
		
		$lines = array();
		$rows = array();
		$rows[] = HTML::label(
				Customer::$PREFIX . Customer::$KEY_FIRST_NAME,
				'Voornaam, achternaam');
		$rows[] = HTML::input(
				Customer::$PREFIX . Customer::$KEY_FIRST_NAME,
				'', '', '', 'Voornaam');
		$rows[] = HTML::input(
				Customer::$PREFIX . Customer::$KEY_LAST_NAME,
				'', '', '', 'Achternaam');
		$lines[] = HTML::fieldsetrow($rows);
		
		$rows = array();
		$rows[] = HTML::inputLine(
				'Functie', Customer::$PREFIX . Customer::$KEY_FUNCTION);
		$lines[] = HTML::fieldsetrow($rows);
		
		print HTML::fieldset('Contactpersoon', $lines);
		
		ContactDetailsWidget::output(Customer::$PREFIX, true, true);
		AddressWidget::output(Customer::$PREFIX, true, true);
		
		print HTML::submitLine('Verzenden', 'submit');
		print HTML::hiddenLine(HTML::$FORM_IDENTIFIER, 'customer_input');
		print HTML::hiddenLine(HTML::$FORM_ACTION, 'create');
		print HTML::untab();
		print HTML::line('</form>');
		print HTML::untab();
	}
}

?>