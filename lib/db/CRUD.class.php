<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

interface CRUD {
	
	public static function create();
	/*
	public static function read();
	public static function update();
	public static function delete();
	*/
}