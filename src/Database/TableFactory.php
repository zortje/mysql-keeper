<?php

namespace Zortje\MySQLKeeper\Database;

use Zortje\MySQLKeeper\Database\Table\Column;

/**
 * Class TableFactory
 *
 * @package Zortje\MySQLKeeper\Database
 */
class TableFactory {

	/**
	 * @param string $tableName Table name
	 * @param \PDO   $pdo       Database connection
	 *
	 * @return Table
	 */
	public static function create($tableName, \PDO $pdo) {
		/**
		 * Columns
		 */
		$columns = [];

		foreach ($pdo->query("SHOW COLUMNS FROM `$tableName`;") as $row) {
			$column = new Column($row);

			$columns[] = $column;
		}

		/**
		 * Indices
		 */
		$indices = [];

		/**
		 * Initialization
		 */
		$table = new Table($columns, $indices);

		return $table;
	}
}
