<?php

interface Widget {
	
	public static function output(
			$idPrefix = '',
			$addEnableButton = false,
			$disabled = false);
}