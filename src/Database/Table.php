<?php

namespace Zortje\MySQLKeeper\Database;

use Zortje\MySQLKeeper\Database\Table\Column;

/**
 * Class Table
 *
 * @package Zortje\MySQLKeeper\Database
 */
class Table {

	/**
	 * @var string
	 */
	private $table;

	/**
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * @var array
	 */
	private $result = [];

	/**
	 * @param string $table Table name
	 * @param \PDO   $pdo   Database connection
	 */
	public function __construct($table, \PDO $pdo) {
		$this->table = $table;
		$this->pdo   = $pdo;
	}

	/**
	 * Get result of column
	 *
	 * @return array Result
	 */
	public function getResult() {
		/**
		 * Reset result
		 */
		$this->result = [];

		/**
		 * Show columns for table
		 */
		foreach ($this->pdo->query("SHOW COLUMNS FROM `$this->table`;") as $row) {
			$column = new Column($row);

			foreach ($column->getResult() as $result) {
				$this->result[] = $result;
			}
		}

		return $this->result;
	}
}
