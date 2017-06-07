<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

class DBConnection {
	
	private static $instance = null;
	
	private static $hostname = null;
	private static $database = null;
	private static $username = null;
	private static $password = null;
	
	function __construct() {}
	function __clone() {}
	
	public static function getDB(
			$hostname,
			$database,
			$username,
			$password) {
		if (!isset(self::$instance)) {
			if (!self::$hostname ||
				!self::$database ||
				!self::$username ||
				!self::$password) {
				self::$hostname = $hostname;
				self::$database = $database;
				self::$username = $username;
				self::$password = $password;
			}
			
			$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			
			self::$instance = new PDO(	'mysql:' .
										'host=' . self::$hostname . ';' .
										'dbname=' . self::$database . ';' .
										'charset=utf8mb4',
										self::$username,
										self::$password,
										$pdoOptions);
		}
		
		return self::$instance;
	}
	
	public static function closeDB() {
		if (isset(self::$instance))
			self::$instance = null;
		
		return true;
	}
	
	public static function query(PDOStatement $statement) {
		try {
			return $statement -> execute();
		}
		catch (PDOException $p) {
			print '<h1 class="error">ERROR</h1>' . "\r\n";
			print '<div class="error">' .
					$statement -> queryString . '</div>' . "\r\n";
			print '<div class="error">' .
					$p -> getMessage() . '</div>' . "\r\n";
			
			/*
			print '<div class="error">';
			while ($line = $p -> getTrace()) {print_r($line);
				print '<p>' . $line . '</p>' . "\r\n";
			}
			
			print '</div>' . "\r\n";
			*/
		}
	}
}
?>