<?php

namespace Zortje\MySQLKeeper;

use Zortje\MySQLKeeper\Database\TableCollection;

/**
 * Class Database
 *
 * @package Zortje\MySQLKeeper
 */
class Database {

	/**
	 * @var TableCollection Database tables
	 */
	private $tables;

	/**
	 * @param TableCollection $tables Database tabels
	 */
	public function __construct(TableCollection $tables) {
		$this->tables = $tables;
	}

	/**
	 * Get database tables
	 *
	 * @return TableCollection Database tables
	 */
	public function getTables() {
		return $this->tables;
	}

	/**
	 * Get result for database
	 *
	 * @return array Result
	 */
	public function getResult() {
		$result = [];

		/**
		 * Go though tables to get results
		 */
		foreach ($this->tables as $table) {
			$result = array_merge($result, $table->getResult());
		}

		return $result;
	}
}
