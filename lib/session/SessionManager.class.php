<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

requireClass('lib/session/Session');

class SessionManager implements SessionHandlerInterface {
	
	private function __construct() {}
	private function __clone() {}
	
	private static $instance = null;
	private static $session = null;
	
	private $id = 0;
	
	private static $selectStatement = null;
	private static $insertStatement = null;
	private static $insertStoreStatement = null;
	private static $deleteStatement = null;
	private static $gcStatement = null;
	
	/**
	 * 
	 * @return unknown
	 */
	public static function getSessionManager() {
		if (!isset(self::$instance)) {
			self::$instance = new SessionManager();
			self::$instance -> setSaveHandler();
			
			self::$session = new Session();
		}
		
		return self::$instance;
	}
	
	public function setSaveHandler() {
		session_set_save_handler(
				array(&$this, "open"),
				array(&$this, "close"),
				array(&$this, "read"),
				array(&$this, "write"),
				array(&$this, "destroy"),
				array(&$this, "gc"));
	}
	
	public function getSession() {
		return self::$session;
	}
	
	private function setID($id) {
		$this -> id = $id;
	}
	private function getID() {
		return $this -> id;
	}
	private function hasID() {
		return $this -> getID() &&
				is_int($this -> getID()) &&
				$this -> getID() > 0;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see SessionHandlerInterface::open()
	 */
	public function open($savePath, $sessionName) {
		getDB();
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see SessionHandlerInterface::close()
	 */
	public function close() {
		closeDB();
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see SessionHandlerInterface::read()
	 */
	public function read($sessionID) {
		if (!isset(self::$selectStatement)) {
			self::$selectStatement = getDB() -> prepare(
				'SELECT `data` FROM `sessions` WHERE ' .
				'`session_id` = ?');
		}
		/*
		self::$selectStatement -> bindValue(
				":sessionID", $sessionID, PDO::PARAM_STR);
		*/
		self::$selectStatement -> execute([$sessionID]);
		
		$result = self::$selectStatement -> fetch(PDO::FETCH_ASSOC);
		
		if ($result)
			return $result['data'];
		
		return '';
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see SessionHandlerInterface::write()
	 */
	public function write($sessionID, $sessionData) {
		if (!isset(self::$insertStatement)) {
			self::$insertStatement = getDB() -> prepare(
					'INSERT INTO `sessions` ' .
					'(`session_id`, `data`, `last_access`) ' .
					'VALUES (? , ? , NOW(6)) ' .
					'ON DUPLICATE KEY UPDATE ' .
					'`data` = ?, ' .
					'`last_access` = NOW(6)');
		}
		
		$result = self::$insertStatement -> execute(
				[$sessionID, $sessionData, $sessionData]);
		
		if ($result)
			$this -> setID(getDB() -> lastInsertId());
		
		//$this -> writeStore($this -> getID(), $sessionID);
		
		return $result;
	}
	
	private function writeStore($insertID, $sessionID) {
		if (!isset(self::$insertStoreStatement)) {
			self::$insertStoreStatement = getDB() -> prepare(
					'INSERT INTO `sessions_store` ' .
					'(`session`, `session_id`, `data`, `last_access`) ' .
					'VALUES (:session, :sessionID, :data, :lastAccess)');
		}
		$select = getDB() -> prepare('SELECT ' .
				'`id`, `session_id`, `data`, `last_access` ' .
				'FROM `sessions` WHERE ' .
				(is_int($insertID) && $insertID > 0 ?
				'`id` = ' . $insertID :
				'`session_id` = ' . '"' . $sessionID . '"'));
		
		$select -> execute();
		$result = $select -> fetch(PDO::FETCH_ASSOC);
		
		self::$insertStoreStatement -> bindValue(
				':session', $result['id'], PDO::PARAM_INT);
		self::$insertStoreStatement -> bindValue(
				':sessionID', $result['session_id'], PDO::PARAM_STR);
		self::$insertStoreStatement -> bindValue(
				':data', $result['data']);
		self::$insertStoreStatement -> bindValue(
				':lastAccess', $result['last_access']);
		
		return self::$insertStoreStatement -> execute();
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see SessionHandlerInterface::destroy()
	 */
	public function destroy($sessionID) {
		if (!isset(self::$deleteStatement)) {
			self::$deleteStatement = getDB() -> prepare(
					'DELETE FROM `sessions` WHERE ' .
					'`session_id` = ?');
		}
		
		return self::$deleteStatement -> execute([$sessionID]);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see SessionHandlerInterface::gc()
	 */
	public function gc($maxLifeTime) {
		if (!isset(self::$gcStatement)) {
			self::$gcStatement = getDB() -> prepare(
					'DELETE FROM `sessions` WHERE ' .
					'`last_access` < DATE_SUB(NOW(6), INTERVAL ' .
					'? microseconds)');
		}
		
		return self::$gcStatement -> execute([intval($maxLifeTime)]);
	}
}