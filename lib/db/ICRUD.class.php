<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

interface ICRUD {
	
	public function create();
	public function read();
	public function update();
	public function delete();
}