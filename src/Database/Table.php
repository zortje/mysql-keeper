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
	 * @var Column[] Table columns
	 */
	private $columns;

	/**
	 * @var array Table indices
	 */
	private $indices;

	/**
	 * @var array
	 */
	private $result = [];

	/**
	 * @param Column[] $columns Table columns
	 * @param Index[]  $indices Table indices
	 */
	public function __construct($columns, $indices) {
		$this->columns = $columns;
		$this->indices = $indices;
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
		 * Go though columns and get result
		 */
		foreach ($this->columns as $column) {
			foreach ($column->getResult() as $result) {
				$this->result[] = $result;
			}
		}

		return $this->result;
	}
}
