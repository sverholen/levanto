<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/mvc/Widget');
requireClass('tools/HTML');
requireClass('lib/contact/Address');
requireClass('lib/contact/Country');

class AddressWidget implements Widget {
	
	public static function output(
			$idPrefix = '', $addEnableButton = false, $disabled = false) {
		$countries = Country::select();
		
		$lines = array();
		$rows = array();
		
		if ($addEnableButton) {
			$rows[] = HTML::checkboxtoggleline('Zelfde als organisatie',
					$idPrefix . Address::$KEY_ENABLE,
					'checkAddress', $idPrefix, true);
			$lines[] = HTML::fieldsetrow($rows);
		}
		
		$rows = array();
		$rows[] = HTML::label(
				$idPrefix . Address::$KEY_STREET, 'Straat, nummer, bus', '',
				$disabled);
		$rows[] = HTML::input(
				$idPrefix . Address::$KEY_STREET, '', '', '', 'Straat',
				$disabled);
		$rows[] = HTML::input(
				$idPrefix . Address::$KEY_NUMBER, '', '', '', 'Nummer', 
				$disabled);
		$rows[] = HTML::input(
				$idPrefix . Address::$KEY_BOX, '', '', '', 'Bus',
				$disabled);
		$lines[] = HTML::fieldsetrow($rows);
		
		$rows = array();
		$rows[] = HTML::label(
				$idPrefix . Address::$KEY_POSTAL_CODE,
				'Postcode, gemeente, land', '', $disabled);
		$rows[] = HTML::input(
				$idPrefix . Address::$KEY_POSTAL_CODE, '', '', '', 'Postcode',
				$disabled);
		$rows[] = HTML::input(
				$idPrefix . Address::$KEY_CITY, '', '', '', 'Gemeente',
				$disabled);
		$rows[] = HTML::line('<select id="' .
				$idPrefix . Address::$KEY_COUNTRY . '" name="' .
				$idPrefix . Address::$KEY_COUNTRY . '"' .
				($disabled ? ' disabled' : '') . '>');
		HTML::tab();
		
		foreach ($countries as $country) {
			$rows[] = HTML::option(
					$country -> getCountry(),
					$country -> getID(),
					($country -> getCode() == 'be' ? true : false),
					($country -> getCode() == 'be' ? true : false));
		}
		HTML::untab();
		$rows[] = HTML::line('</select>');
		$lines[] = HTML::fieldsetrow($rows);
		
		print HTML::fieldset('Adres', $lines);
	}
}