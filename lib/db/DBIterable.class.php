<?php

/* APPLICATION CHECK **********************************************************/
defined('APPLICATION_KEY') or die('Invalid application');

interface DBIterable {
	
	/**
	 *
	 * @param string $keyAlias The alias of the key (`id` AS `country`.`id`)
	 * @param string $tableAlias The table alias (FROM `table` AS `t`)
	 * @param string $includeForeignKeys Include the keys of the foreign table
	 * in the output array (set to false for output intended for INSERT
	 * statements and to true for output intended for SELECT statements).
	 * @param string $includeID Include the table id in the output array (set
	 * to false for output intended for INSERT statements and to true for
	 * output intended for SELECT statements).
	 */
	/*
	 public static function listKeys(
	 $keyAlias = '',
	 $tableAlias = '',
	 $includeForeignKeys = false,
	 $includeID = false);
	 */
	/**
	 *
	 */
	public static function getTable();
	
	/*
	 public static function insertKeys();
	 public static function selectKeys();
	 
	 public static function getIDAlias();
	 public static function getTableAlias();
	 */
	
	
	public static function load(array $pdoAssociativeArray = array());
}

?>