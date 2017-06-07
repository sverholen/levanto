<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

/**
 * Sets up a database connection.
 * 
 * The parameters are stored as global variables in this script, allowing you
 * to supply them only once (at application initialisation, instead of at
 * every database call).
 * 
 * @param unknown $hostname the hostname of the database instance.
 * @param unknown $database the name of the database to use.
 * @param unknown $username the username.
 * @param unknown $password the password.
 * 
 * @return PDO a database connection.
 */
function getDB(
		$hostname = null,
		$database = null,
		$username = null,
		$password = null) {
	return DBConnection::getDB(
			$hostname, $database, $username, $password);
}

function closeDB() {
	return DBConnection::closeDB();
}

/**
 * Loads a supporting file or class.
 * 
 * This function requires the BASE_DIR constant to be defined to the root
 * of the web application.
 * 
 * @param unknown $file the file to include.
 */
function requireFile($file) {
	try {
		include_once(BASE_DIR . DIRECTORY_SEPARATOR . $file);
	}
	catch (Exception $e) {
		print 'hm';exit;
	}
}

/**
 * Loads a class.
 * 
 * This function appends '.class.php' to the supplied class name, allowing you
 * to do something like
 * requireClass('lib/db/DBConnection') while the actual class file name
 * is lib/DB/DBConnection.class.php.
 * 
 * This function depends on a strict naming convention for the files, classes
 * and scripts in the web application.
 * A class file should have a filename ending in '.class.php'.
 * 
 * The generated name is passed to requireFile, which also requires the
 * BASE_DIR constant to be set to the root path of the web application.
 * 
 * @see requireFile($file)
 * @param unknown $class the class name
 */
function requireClass($class) {
	requireFile($class . '.class.php');
}

/**
 * Loads a script.
 * 
 * This function appends '.func.php' to the supplied script name, allowing you
 * to do something like
 * requireScript('tools/levanto') while the actual script file name
 * is tools/levanto.func.php.
 * 
 * This function depends on a strict naming convention for the files, classes
 * and scripts in the web application.
 * A script contains various functions and should have a
 * filename ending in '.func.php'.
 * 
 * The generated name is passed to requireFile, which also requires the
 * BASE_DIR constant to be set to the root path of the web application.
 * 
 * @see requireFile($file)
 * 
 * @param unknown $script the script to load.
 */
function requireScript($script) {
	requireFile($script . '.func.php');
}

/**
 * Loads a supporting script.
 * 
 * This function appends '.inc.php' to the supplied script name, allowing you
 * to do something like
 * requireSupportingScript('tools/tools') while the actual script file name
 * is tools/tools.inc.php.
 * 
 * This function depends on a strict naming convention for the files, classes
 * and scripts in the web application.
 * A supporting script contains various helper functions and should have a
 * filename ending in '.inc.php'.
 * 
 * The generated name is passed to requireFile, which also requires the
 * BASE_DIR constant to be set to the root path of the web application.
 * 
 * @see requireFile($file)
 * 
 * @param unknown $script the supporting script to load.
 */
function requireHelperScript($script) {
	requireFile($script . '.inc.php');
}

function requireMVC($name, $display = '') {
	requireClass('mvc/' . $name . '/' . $name . 'Controller');
	requireClass('mvc/' . $name . '/' . $name . 'Model');
	requireClass('mvc/' . $name . '/' . $name . $display . 'View');
}

function loadMVCClass($name, $type, $display = '', array $arguments = array()) {
	$classname = $name . $display . $type;
	
	requireClass('mvc/' . $name . '/' . $classname);
	
	if (!$arguments || (is_array($arguments) && sizeof($arguments) == 0))
		return new $classname();
	
	return new $classname(...$arguments);
}

function loadMVCView($name, $display = '') {
	$name = ucwords($name);
	
	requireMVC($name, $display);
	
	$model = loadMVCClass($name, 'Model');
	$controller = loadMVCClass($name, 'Controller', '', array($model));
	
	return loadMVCClass($name, 'View', $display, array($controller, $model));
}

function camelise($name) {
	return implode('', array_map('ucwords', explode('_', $name)));
}

?>