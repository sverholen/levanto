<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

class HTML {
	
	private static $instance = null;
	
	private $tabs = 0;
	
	public static $IE_HTML5
	= 'http://html5shiv.googlecode.com/svn/trunk/html5.js';
	
	public static $FORM_IDENTIFIER	= 'formid';
	public static $FORM_ACTION		= 'form_action';
	
	private function __construct() {}
	private function __clone() {}
	
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new HTML();
		}
		
		return self::$instance;
	}
	
	public static function tab() {
		self::$instance -> tabs
		= self::$instance -> tabs + 1;
	}
	
	public static function untab() {
		self::$instance -> tabs
		= self::$instance -> tabs > 0 ? self::$instance -> tabs - 1 : 0;
	}
	
	public static function line($content = '', $addLine = false) {
		return str_repeat("\t", self::$instance -> tabs) .
				$content . ($addLine ? self::line() : '') . "\r\n";
	}
	
	public static function script($src) {
		return self::line(
				'<script type="text/javascript" src="' . $src . '"></script>');
	}
	
	public static function form($action) {
		return self::line(
				'<form action="' . $action . '" method="post" ' .
				'enctype="multipart/form-data">');
	}
	
	public static function header($label, $class = '') {
		return self::line(
				'<h2' . ($class ? ' class="' . $class . '"' : '') . '>' .
				$label .
				'</h2>');
	}
	
	public static function menuitem($label, $page, $display = '') {
		return '<a href="index.php?page=' . $page . 
				(isset($display) && strlen($display) > 0 ?
						'&display=' . $display : '') .
				'" alt="' . $label . '">' . $label . '</a>';
	}
	
	public static function menu($label, array $items,
			$btnClass = 'dropbtn',
			$contentClass = 'dropdown-content') {
		$menu = array();
		
		$menu[] = '<button class="' . $btnClass . '">' . $label . '</button>';
		$menu[] = '<div class="' . $contentClass . '">';
		$menu[] = $items;
		$menu[] = '</div>';
		
		return $menu;
	}
	
	public static function menucontainer(array $menus, $class = 'dropdown') {
		$content = '';
		
		$content .= self::line('<div class="menucontainer">');
		
		HTML::tab();
		
		foreach ($menus as $menu) {
			$content .= self::line('<div class="' . $class . '">');
			
			HTML::tab();
			
			$content .= self::line($menu[0]);
			$content .= self::line($menu[1]);
			
			HTML::tab();
			
			foreach ($menu[2] as $item)
				$content .= self::line($item);
			
			HTML::untab();
			
			$content .= self::line($menu[3]);
			
			HTML::untab();
			
			$content .= self::line('</div>');
		}
		
		HTML::untab();
		
		$content .= self::line('</div>');
		
		return $content;
	}
	
	public static function label($id, $label, $class = '', $disabled = false) {
		return self::line('<label for="' . $id . '"' .
				($class ? ' class="' . $class . '"' : '') .
				'>' . $label . '</label>');
	}
	
	public static function input(
			$id, $name = '', $type = '', $value = '', $hint = '',
			$disabled = false) {
		if (!$name || strlen($name) == 0) $name = $id;
		
		if (!InputType::isValidType($type))
			$type = null;
		
		if (!$type) $type = InputType::$TEXT;
		
		$idAttr = ' id="' . $id . '"';
		$nameAttr = ' name="' . $name . '"';
		$valueAttr = ' value="' . $value . '"';
		$typeAttr = ' type="' . $type . '" ';
		$hint = $hint ? ' placeholder="' . $hint . '" ' : '';
		$disabled = $disabled ? ' disabled' : '';
		
		return self::line('<input' .
				$idAttr . $nameAttr . $valueAttr . $typeAttr .
				$hint . $disabled . '/>');
	}
	
	public static function fieldset($legend, array $lines) {
		$content  = '';
		
		$content .= self::line('<fieldset>');
		self::tab();
		$content .= self::line('<legend>' . $legend . '</legend>');
		
		foreach ($lines as $row)
			if (is_array($row)) 
				foreach ($row as $line)
					$content .= $line;
			else
				$content .= $row;
		
		self::untab();
		$content .= self::line('</fieldset>');
		
		return $content;
	}
	
	public static function fieldsetrow(array $lines, $class = 'formrow') {
		$content = array();
		
		self::tab();
		$content[] = self::line('<div class="' . $class . '">');
		self::tab();
		
		foreach ($lines as $line)
			$content[] = $line;
		
		self::untab();
		$content[] = self::line('</div>');
		self::untab();
		
		return $content;
	}
	
	public static function inputLine(
			$label, $id, $name = '', $type = '', $value = '',
			$hint = '', $disabled = false) {
		if (!$hint) $hint = $label;
		
		self::tab();
		$content = self::label($id, $label, $disabled) .
				self::input($id, $name, $type, $value, $hint, $disabled);
		self::untab();
		
		return $content;
	}
	
	public static function fileinput(
			$label, $id, $name = '', $disabled = false) {
		
			self::tab();
			$content = self::label($id, $label, $disabled) .
					self::input($id, $name, 'file', '', '', $disabled);
			self::untab();
			
			return $content;
	}
	
	public static function submitLine(
			$label, $id, $disabled = false) {
		return self::inputLine('&nbsp;', $id, $id, InputType::$SUBMIT, $label,
				'', $disabled);
	}
	
	public static function hiddenLine($id, $value) {
		return self::input($id, $id, InputType::$HIDDEN, $value);
	}
	
	public static function checkboxtoggleline(
			$label, $id, $script, $prefix = '', $checked = false,
			$name = '', $class = '') {
		if (!$name) $name = $id;
		
		return self::label($id, '&nbsp;') .
				self::line('<input id="' . $id . '" name="' . $name . '" ' .
						' type="' . InputType::$CHECKBOX . '" '.
						' onchange="' . $script .
						'(' . "'" . $id . "','" . $prefix . "'" . ');"' .
						($checked ? ' checked' : '') . '>') .
				self::label($id, $label, $class);
	}
	
	public static function option(
			$label, $value = '', $default = false, $selected = false) {
		if (!$value) $value = $label;
		
		return self::line(
				'<option value="' . $value . '"' .
				($default ? ' default' : '') .
				($selected ? ' selected' : '') . '>' .
				$label . '</option>');
	}
	
	public static function selectLine(
			$label, $id, $name = '', $type = '', $value = '', $hint = '',
			$disabled = false) {
		if (!$hint) $hint = $label;
		
		if ($name) $name = $id;
		
		return self::label($id, $label)	.
				self::line('<select id="' . $id . '" name="' . $name . '"' .
						($disabled ? ' disabled' : '') . '>');
	}
}

abstract class InputType {
	
	public static $BUTTON				= 'button';
	public static $CHECKBOX				= 'checkbox';
	public static $COLOR				= 'color';
	public static $DATE					= 'date';
	public static $DATETIME_LOCAL		= 'datetime-local';
	public static $EMAIL				= 'email';
	public static $FILE					= 'file';
	public static $HIDDEN				= 'hidden';
	public static $IMAGE				= 'image';
	public static $MONTH				= 'month';
	public static $NUMBER				= 'number';
	public static $PASSWORD				= 'password';
	public static $RADIO				= 'radio';
	public static $RANGE				= 'range';
	public static $RESET				= 'reset';
	public static $SEARCH				= 'search';
	public static $SUBMIT				= 'submit';
	public static $TEL					= 'tel';
	public static $TEXT					= 'text';
	public static $TIME					= 'time';
	public static $URL					= 'url';
	public static $WEEK					= 'week';
	
	public static $DEFAULT_TYPE			= 'text';
	
	public static $TYPES;
	
	public static function getTypes() {
		if (!isset($TYPES)) {
			$TYPES = array(
					self::$BUTTON,
					self::$CHECKBOX,
					self::$COLOR,
					self::$DATE,
					self::$DATETIME_LOCAL,
					self::$EMAIL,
					self::$FILE,
					self::$HIDDEN,
					self::$IMAGE,
					self::$MONTH,
					self::$NUMBER,
					self::$PASSWORD,
					self::$RADIO,
					self::$RANGE,
					self::$RESET,
					self::$SEARCH,
					self::$SUBMIT,
					self::$TEL,
					self::$TEXT,
					self::$TIME,
					self::$URL,
					self::$WEEK);
		}
		
		return $TYPES;
	}
	
	public static function isValidType(&$type) {
		if (!$type || strlen($type) == 0)
			return false;
		
		$type = strtolower($type);
		
		return in_array($type, self::getTypes());
	}
}

// Make sure that the singleton is always initialised
HTML::getInstance();