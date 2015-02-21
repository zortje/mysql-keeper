<?php

namespace Zortje\MySQLKeeper;

use Zortje\MySQLKeeper\Database\TableCollection;
use Zortje\MySQLKeeper\Database\Table;

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
		 *
		 * @var Table $table
		 */
		foreach ($this->tables as $table) {
			$tableResult = $table->getResult();

			if (count($tableResult['issues']) > 0) {
				$result[$table->getName()] = $table->getResult();
			}
		}

		return $result;
	}
}
