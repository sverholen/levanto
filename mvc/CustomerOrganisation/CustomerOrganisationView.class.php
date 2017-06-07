<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('lib/contact/CustomerOrganisation');
requireMVC('CustomerOrganisation');

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
class CustomerOrganisationView extends View {
	
	public function __construct(
			CustomerOrganisationController $controller,
			CustomerOrganisationModel $model) {
				$this -> setController($controller);
				$this -> setModel($model);
	}
	public function __clone() {}
	
	public function output() {
		$countries = Country::select();
		
		print HTML::tab();
		print HTML::form('index.php');
		print HTML::tab();
		print HTML::header('Voeg een organisatie toe', 'formheader');
		
		$lines = array();
		$rows = array();
		$rows[] = HTML::inputLine(
				'Organisatie', Organisation::$KEY_ORGANISATION);
		$lines[] = HTML::fieldsetrow($rows);
		
		$rows = array();
		$rows[] = HTML::inputLine(
				'Volledige naam', Organisation::$KEY_FULL_NAME);
		$lines[] = HTML::fieldsetrow($rows);
		
		$rows = array();
		$rows[] = HTML::inputLine(
				'BTW-nummer', Organisation::$KEY_VAT_NUMBER);
		$lines[] = HTML::fieldsetrow($rows);
		
		print HTML::fieldset('Organisatie', $lines);
		
		ContactDetailsWidget::output();
		AddressWidget::output();
		
		print HTML::submitLine('Verzenden', 'submit');
		print HTML::hiddenLine(HTML::$FORM_IDENTIFIER, 'customer_organisation');
		print HTML::hiddenLine(HTML::$FORM_ACTION, 'create');
		print HTML::untab();
		print HTML::line('</form>');
		print HTML::untab();
	}
}

?>