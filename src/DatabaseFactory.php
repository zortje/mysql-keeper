<?php

namespace Zortje\MySQLKeeper;

use Zortje\MySQLKeeper\Database\TableCollection;
use Zortje\MySQLKeeper\Database\TableFactory;

/**
 * Class DatabaseFactory
 *
 * @package Zortje\MySQLKeeper
 */
class DatabaseFactory {

	/**
	 * Create database
	 *
	 * @param \PDO $pdo Database connection
	 *
	 * @return Database
	 */
	public static function create(\PDO $pdo) {
		/**
		 * Tables
		 */
		$tables = new TableCollection();

		foreach ($pdo->query('SHOW TABLES;') as $row) {
			$table = TableFactory::create($row[0], $pdo);

			$tables->add($table);
		}

		/**
		 * Initialization
		 */
		$database = new Database($tables);

		return $database;
	}
}
