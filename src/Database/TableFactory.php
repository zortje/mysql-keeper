<?php

namespace Zortje\MySQLKeeper\Database;

use Zortje\MySQLKeeper\Database\Table\Column;
use Zortje\MySQLKeeper\Database\Table\Index;

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
		$indexRows = [];

		foreach ($pdo->query("SHOW INDEX FROM `$tableName`;") as $row) {
			$indexRows[$row['Key_name']]['unique']        = $row['Non_unique'] === '1';
			$indexRows[$row['Key_name']]['columnNames'][] = $row['Column_name'];
		}

		$indices = [];

		foreach ($indexRows as $keyName => $indexArray) {
			$index = new Index($keyName, $indexArray['unique'], $indexArray['columnNames']);

			$indices[] = $index;
		}

		/**
		 * Initialization
		 */
		$table = new Table($columns, $indices);

		return $table;
	}
}
