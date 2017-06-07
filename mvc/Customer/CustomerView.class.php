<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/View');
requireClass('tools/HTML');
requireMVC('Customer');
requireClass('lib/contact/CustomerOrganisation');

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

class CustomerView extends View {
	
	public function __construct(
			CustomerController $controller,
			CustomerModel $model) {
				$this -> setController($controller);
				$this -> setModel($model);
	}
	
	public function output() {
		$organisations = CustomerOrganisation::select(true);
		
		HTML::tab();
		print HTML::form('index.php');
		HTML::tab();
		print HTML::header('Voeg een (contact)persoon toe', 'formheader');
		
		$lines = array();
		$rows = array();
		$rows[] = HTML::inputLine('Voornaam', Person::$KEY_FIRST_NAME);
		$rows[] = HTML::inputLine('Achternaam', Person::$KEY_LAST_NAME);
		$lines[] = HTML::fieldsetrow($rows);
		
		print HTML::fieldset('Naam', $lines);
		
		ContactDetailsWidget::output();
		AddressWidget::output();
		
		$lines = array();
		$rows = array();
		$rows[] = HTML::selectline('Organisatie', 'organisation');
		
		foreach ($organisations as $organisation) {
			if ($organisation == null) {
				$rows[] = HTML::option('', '0', true, true);
				
				continue;
			}
			
			$rows[] = HTML::option(
					$organisation -> getOrganisation(),
					$organisation -> getID());
		}
		$rows[] = HTML::line('</select>');
		$lines[] = HTML::fieldsetrow($rows);
		
		$rows = array();
		$rows[] = HTML::inputLine('Functie', 'function');
		$lines[] = HTML::fieldsetrow($rows);
		
		print HTML::fieldset('Organisatie', $lines);
		
		print HTML::submitLine('Verzenden', 'submit');
		print HTML::hiddenLine(HTML::$FORM_IDENTIFIER, 'customer');
		print HTML::hiddenLine(HTML::$FORM_ACTION, 'create');
		print HTML::untab();
		print HTML::line('</form>');
		print HTML::untab();
	}
}

?>