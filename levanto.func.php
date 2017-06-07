<?php

/*
 * SET AN APPLICATION KEY
 * 
 * All files and classes should check for this key to ensure that supporting
 * functions and variables are properly set.
 */
define('APPLICATION_KEY',	'$1$L0R0nBSY$16eIu6l8137cSUhL3.8xU1');

if (!defined('BASE_DIR'))
	define('BASE_DIR', realpath(dirname(__FILE__)));

require_once(BASE_DIR . '/tools/tools.inc.php');

requireScript('tools/text');

/*
 * Make sure that the database connection class is loaded and tested before we
 * continue and possibly run into problems later.
 */
requireClass('lib/db/DBConnection');
getDB('localhost', 'levanto', 'levanto', 'levanto');

/*
 * Load the logging mechanism.
 */
requireClass('lib/logging/LogLevel');
requireClass('lib/logging/LoggerManager');
requireClass('lib/logging/FileLogger');
requireClass('lib/logging/MVCLogger');
function getLogLevel() {
	return LogLevel::DEBUG;
}
function getLog() {
	return LoggerManager::getInstance();
}
function getMVCLogger() {
	return MVCLogger::getInstance();
}
$_fileLogger = new FileLogger('', getLogLevel());
getLog() -> addLogger($_fileLogger);
getLog() -> addLogger(getMVCLogger());

/*
 * Load the session handler
 */
requireClass('lib/session/SessionManager');
function getSessionManager() {
	return SessionManager::getSessionManager();
}
function getSession() {
	return getSessionManager() -> getSession();
}
getSession();
session_start();
getSession() -> addPageVisit();

header('Content-Type: text/html; charset=utf-8');

/*
 * Load the page manager
 */
requireClass('lib/mvc/Application');
function getApp() {
	return Application::getInstance();
}
getApp() -> getSkeleton() -> addPostloadJavascript('js/forms.js');
getApp() -> getSkeleton() -> addStylesheet('css/reset.css');
getApp() -> getSkeleton() -> addStylesheet('css/levanto.css');

/**
 * Setup $_GET handlers for the application.
 * 
 * @var array $getHandlers
 */
$getHandlers = array();

$getHandlers[] = array('action'	=>	'');
$getHandlers[] = array('page'	=>	'');

/**
 * 
 * @param array $get
 * @param array $post
 * @param array $files
 */
function processUserInput($get = array(), $post = array(), $files = array()) {
	if (is_array($get) && sizeof($get) > 0) {
		processGet($get);
	}
	
	if ((is_array($post) && sizeof($post) > 0) ||
			(is_array($files) && sizeof($files) > 0)) {
		processPost($post, $files);
	}
}

function processGet($get) {
	/*foreach ($get as $key => $value)
		print '<div>KEY: ' . $key . ' - VALUE: ' . $value . '</div>';*/
}

function processPost($post, $files) {
	if (isset($post[HTML::$FORM_IDENTIFIER]) &&
		isset($post[HTML::$FORM_ACTION])) {
		dispatchForm($post[HTML::$FORM_IDENTIFIER], $post[HTML::$FORM_ACTION],
				$post, $files);
	}
}

function dispatchForm($id, $actionKey, $post, $files) {
	$view = loadMVCView(camelise($id));
	$action = ControllerAction::load($actionKey);
	
	$controller = $view -> getController();
	
	$result = $controller -> process($action, $post, $files);
	
	return $result;
}

processUserInput($_GET, $_POST, $_FILES);

function dispatchPage($get) {
	if (defined('DO_NOT_DISPATCH'))
		return;
	
	$keys = array_keys($get);
	$action = in_array('display', $keys) ? ucwords($get['display']) : '';
	if (in_array('page', $keys)) {
		$page = camelise($get['page']);
		
		$view = loadMVCView($page, $action);
		
		getApp() -> getSkeleton() -> setCurrentView($view);
		getApp() -> getSkeleton() -> output(MenuContainer::load('menu.csv'));
	}
	else {
		getApp() -> getSkeleton() -> output(MenuContainer::load('menu.csv'));
	}
}
dispatchPage($_GET);
session_write_close();