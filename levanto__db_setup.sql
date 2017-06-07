/*

DATABASE:	levanto
HOSTNAME:	localhost
USERNAME:	levanto
PASSWORD:	levanto

*/

CREATE TABLE IF NOT EXISTS `sessions` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`session_id` CHAR(32) NOT NULL,
	`data` TEXT,
	`last_access` DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
	UNIQUE KEY (`session_id`),
	KEY (`last_access`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `sessions_store` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`session` BIGINT UNSIGNED,
	`session_id` CHAR(32) NOT NULL,
	`data` TEXT,
	`last_access` DATETIME(6) NOT NULL,
	FOREIGN KEY (`session`) REFERENCES `sessions`(`id`),
	UNIQUE KEY (`session_id`, `last_access`)
) ENGINE="InnoDB" CHARSET="UTF8";

/*
CREATE TABLE IF NOT EXISTS `visitors` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`session` BIGINT UNSIGNED,
	`data` TEXT,
	`last_access` DATETIME NOT NULL
	FOREIGN KEY (`session`) REFERENCES `sessions`(`id`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `visits` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`visitor` BIGINT UNSIGNED,
	FOREIGN KEY (`visitor`) REFERENCES `visitors`(`id`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `visit_data` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`visit` BIGINT UNSIGNED,
	`server_key` VARCHAR(255) NOT NULL,
	`server_data` TEXT NOT NULL,
	FOREIGN KEY (`visit`) REFERENCES `visits`(`id`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `users` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`username` VARCHAR(32) NOT NULL DEFAULT '',
	`password` VARCHAR(128) NOT NULL DEFAULT 'htu?W5N47=WNOUU!e91LsN6a6P2JM',
	`email` VARCHAR(255) NOT NULL DEFAULT '',
	UNIQUE KEY (`username`)
) ENGINE="InnoDB" CHARSET="UTF8";
*/

CREATE TABLE IF NOT EXISTS `countries` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`country` VARCHAR(255) NOT NULL,
	`code` CHAR(2) NOT NULL,
	`code_3` CHAR(3) NOT NULL,
	`number` CHAR(3) NOT NULL,
	UNIQUE KEY (`country`),
	UNIQUE KEY (`code`),
	UNIQUE KEY (`code_3`),
	UNIQUE KEY (`number`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `addresses` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`street` VARCHAR(128) NOT NULL DEFAULT '',
	`number` CHAR(12) NOT NULL DEFAULT '',
	`box` CHAR(12) NOT NULL DEFAULT '',
	`postal_code` CHAR(12) NOT NULL DEFAULT '',
	`city` VARCHAR(64) NOT NULL DEFAULT '',
	`country` BIGINT UNSIGNED NOT NULL,
	FOREIGN KEY (`country`) REFERENCES `countries`(`id`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS  `contact_details` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`email` VARCHAR(255) NOT NULL DEFAULT '',
	`phone` VARCHAR(255) NOT NULL DEFAULT '',
	`cell` VARCHAR(255) NOT NULL DEFAULT '',
	`fax` VARCHAR(255) NOT NULL DEFAULT '',
	`website` VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `organisations` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`organisation` VARCHAR(255) NOT NULL DEFAULT '',
	`full_name` VARCHAR(255) NOT NULL DEFAULT '',
	`vat_number` CHAR(32) NOT NULL DEFAULT '',
	`address` BIGINT UNSIGNED NOT NULL,
	`contact_details` BIGINT UNSIGNED NOT NULL,
	FOREIGN KEY (`address`) REFERENCES `addresses`(`id`),
	FOREIGN KEY (`contact_details`) REFERENCES `contact_details`(`id`),
	UNIQUE KEY (`organisation`),
	UNIQUE KEY (`full_name`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `people` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`first_name` VARCHAR(64) NOT NULL DEFAULT '',
	`last_name` VARCHAR(64) NOT NULL DEFAULT '',
	`address` BIGINT UNSIGNED NOT NULL,
	`contact_details` BIGINT UNSIGNED NOT NULL,
	FOREIGN KEY (`address`) REFERENCES `addresses`(`id`),
	FOREIGN KEY (`contact_details`) REFERENCES `contact_details`(`id`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `allergies` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`allergy` VARCHAR(255) NOT NULL DEFAULT '',
	UNIQUE KEY (`allergy`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `people_allergies` (
	`person` BIGINT UNSIGNED,
	`allergy` BIGINT UNSIGNED,
	FOREIGN KEY (`person`) REFERENCES `people`(`id`),
	FOREIGN KEY (`allergy`) REFERENCES `allergies`(`id`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `organisations_people` (
	`organisation` BIGINT UNSIGNED NOT NULL,
	`person` BIGINT UNSIGNED NOT NULL,
	`function` VARCHAR(255) NOT NULL DEFAULT '',
	FOREIGN KEY (`organisation`) REFERENCES `organisations`(`id`),
	FOREIGN KEY (`person`) REFERENCES `people`(`id`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `customer_organisations` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`organisation` VARCHAR(255) NOT NULL DEFAULT '',
	`full_name` VARCHAR(255) NOT NULL DEFAULT '',
	`vat_number` CHAR(32) NOT NULL DEFAULT '',
	`address` BIGINT UNSIGNED NOT NULL,
	`contact_details` BIGINT UNSIGNED NOT NULL,
	FOREIGN KEY (`address`) REFERENCES `addresses`(`id`),
	FOREIGN KEY (`contact_details`) REFERENCES `contact_details`(`id`),
	UNIQUE KEY (`organisation`),
	UNIQUE KEY (`full_name`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `customers` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`first_name` VARCHAR(64) NOT NULL DEFAULT '',
	`last_name` VARCHAR(64) NOT NULL DEFAULT '',
	`function` VARCHAR(64) NOT NULL DEFAULT '',
	`address` BIGINT UNSIGNED NOT NULL,
	`contact_details` BIGINT UNSIGNED NOT NULL,
	`organisation` BIGINT UNSIGNED NOT NULL,
	FOREIGN KEY (`address`) REFERENCES `addresses`(`id`),
	FOREIGN KEY (`contact_details`) REFERENCES `contact_details`(`id`),
	FOREIGN KEY (`organisation`) REFERENCES `customer_organisations`(`id`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `accounts` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`organisation` BIGINT UNSIGNED,
	`account` VARCHAR(255) NOT NULL DEFAULT '',
	`bank` CHAR(64) NOT NULL DEFAULT '',
	`bic` CHAR(11) NOT NULL DEFAULT '',
	`iban` CHAR(32) NOT NULL DEFAULT '',
	`number` CHAR(32) NOT NULL DEFAULT '',
	FOREIGN KEY (`organisation`) REFERENCES `organisations`(`id`),
	UNIQUE KEY (`account`, `bank`, `bic`, `iban`, `number`)
) ENGINE="InnoDB" CHARSET="UTF8";

/*
CREATE TABLE IF NOT EXISTS `organisations_accounts` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`organisation` BIGINT UNSIGNED,
	`account` BIGINT UNSIGNED,
	FOREIGN KEY (`organisation`) REFERENCES `organisations`(`id`),
	FOREIGN KEY (`account`) REFERENCES `accounts`(`id`)
) ENGINE="InnoDB" CHARSET="UTF8";
*/

CREATE TABLE IF NOT EXISTS `current_accounts` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR(255) NOT NULL DEFAULT '',
	`organisation` BIGINT UNSIGNED,
	FOREIGN KEY (`organisation`) REFERENCES `organisations`(`id`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `organisations_current_accounts` (
	`organisation` BIGINT UNSIGNED,
	`current_account` BIGINT UNSIGNED,
	FOREIGN KEY (`organisation`) REFERENCES `organisations`(`id`),
	FOREIGN KEY (`current_account`) REFERENCES `current_accounts`(`id`),
	UNIQUE KEY (`organisation`, `current_account`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `people_current_accounts` (
	`person` BIGINT UNSIGNED NOT NULL,
	`current_account` BIGINT UNSIGNED NOT NULL,
	FOREIGN KEY (`person`) REFERENCES `people`(`id`),
	FOREIGN KEY (`current_account`) REFERENCES `current_accounts`(`id`),
	UNIQUE KEY (`person`, `current_account`)
) ENGINE="InnoDB" CHARSET="UTF8";

/*
CREATE TABLE IF NOT EXISTS `transactions` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`amount` DECIMAL(9,4) NOT NULL DEFAULT 0.0000,
	`
) ENGINE="InnoDB" CHARSET="UTF8";
*/

CREATE TABLE IF NOT EXISTS `departments` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR(255) NOT NULL DEFAULT '',
	`address` BIGINT UNSIGNED,
	`contact_details` BIGINT UNSIGNED,
	FOREIGN KEY (`address`) REFERENCES `addresses`(`id`),
	FOREIGN KEY (`contact_details`) REFERENCES `contact_details`(`id`)
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `songs` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`title` VARCHAR(255) NOT NULL DEFAULT '',
	`artist` VARCHAR(255) NOT NULL DEFAULT '',
	`filename` VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE="InnoDB" CHARSET="UTF8";

CREATE TABLE IF NOT EXISTS `registrations` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`person` BIGINT UNSIGNED,
	`department` BIGINT UNSIGNED,
	`attendance` TINYINT(1) NOT NULL DEFAULT 0,
	`song1` BIGINT UNSIGNED,
	`song2` BIGINT UNSIGNED,
	`song3` BIGINT UNSIGNED,
	`remarks` TEXT NOT NULL DEFAULT '',
	FOREIGN KEY (`person`) REFERENCES `people`(`id`),
	FOREIGN KEY (`department`) REFERENCES `departments`(`id`),
	FOREIGN KEY (`song1`) REFERENCES `songs`(`id`),
	FOREIGN KEY (`song2`) REFERENCES `songs`(`id`),
	FOREIGN KEY (`song3`) REFERENCES `songs`(`id`)
) ENGINE="InnoDB" CHARSET="UTF8";