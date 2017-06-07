<?php

define('BASE_DIR', realpath(dirname(__FILE__) . '/..'));
define('DO_NOT_DISPATCH', 'OK');

require_once('../levanto.func.php');

requireClass('lib/sql/Table');

$testTable1 = new Table('test1');

$testTable1 -> parsePrimaryKey('id');
$testTable1 -> parseColumn('tinyint', ColumnType::getTinyint());
$testTable1 -> parseColumn('int', ColumnType::getInt());
$testTable1 -> parseColumn('bigint', ColumnType::getBigint());
$testTable1 -> parseColumn('char', ColumnType::getChar());
$testTable1 -> parseColumn('varchar', ColumnType::getVarchar());
$testTable1 -> parseColumn('text', ColumnType::getText());
$testTable1 -> parseColumn('date', ColumnType::getDate());
$testTable1 -> parseColumn('datetime', ColumnType::getDatetime());

$testTable2 = new Table('test2');

$testTable2 -> parsePrimaryKey('id');
$testTable2 -> parseForeignKey('table1_id_foreign', $testTable1);

print '<div>' . $testTable1 . '</div>' . "\r\n";
print '<div>' . $testTable2 . '</div>' . "\r\n";

requireClass('lib/accounting/Account');

$account = new Account();
?>