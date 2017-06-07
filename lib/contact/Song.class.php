<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/db/DBEnabled');

class Songs extends DBEnabled {
	
	private $title = '';
	private $artist = '';
	
	public function __construct($title = '', $artist = '') {
		$this -> setTitle($title);
		$this -> setArtist($artist);
	}
	
	public function setTitle($title = '') {
		$this -> title = $title;
	}
	public function getTitle() {
		return $this -> title;
	}
	
	public function setArtist($artist = '') {
		$this -> artist = $artist;
	}
	public function getArtist() {
		return $this -> artist;
	}
}