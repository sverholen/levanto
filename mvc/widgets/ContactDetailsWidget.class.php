<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Widget');
requireClass('tools/HTML');
requireClass('lib/contact/ContactDetails');

class ContactDetailsWidget implements Widget {
	
	private function __construct() {}
	private function __clone() {}
	
	public static function output(
			$idPrefix = '', $addEnableButton = false, $disabled = false) {
		$lines = array();
		$rows = array();
		
		if ($addEnableButton) {
			$rows[] = HTML::checkboxtoggleline('Zelfde als organisatie',
					$idPrefix . ContactDetails::$KEY_ENABLE,
					'checkContactDetails', $idPrefix, true);
			$lines[] = HTML::fieldsetrow($rows);
		}
		
		$rows = array();
		$rows[] = HTML::inputLine(
				'E-mail', $idPrefix . ContactDetails::$KEY_EMAIL, '', '', 
				'', '', $disabled);
		$lines[] = HTML::fieldsetrow($rows);
		
		$rows = array();
		$rows[] = HTML::label(
				$idPrefix . ContactDetails::$KEY_PHONE, 'Telefoon, fax',
				'', $disabled);
		$rows[] = HTML::input(
				$idPrefix . ContactDetails::$KEY_PHONE, '', '', '', 'Telefoon',
				$disabled);
		$rows[] = HTML::input(
				$idPrefix . ContactDetails::$KEY_FAX, '', '', '', 'Fax',
				$disabled);
		$lines[] = HTML::fieldsetrow($rows);
		
		$rows = array();
		$rows[] = HTML::inputLine(
				'GSM', $idPrefix . ContactDetails::$KEY_CELL, '', '', '', '',
				$disabled);
		$lines[] = HTML::fieldsetrow($rows);
		
		$rows = array();
		$rows[] = HTML::inputLine(
				'Website', $idPrefix . ContactDetails::$KEY_WEBSITE, '', '',
				'', '', $disabled);
		$lines[] = HTML::fieldsetrow($rows);
		
		print HTML::fieldset('Contact', $lines);
	}
}