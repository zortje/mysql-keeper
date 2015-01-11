<?php

namespace Zortje\MySQLKeeper\Database;

use Zortje\MySQLKeeper\Database\Table\Column;
use Zortje\MySQLKeeper\Database\Table\Index;

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
	 * @var Index[] Table indices
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

		/**
		 * Find duplicate indices
		 */
		// @todo

		return $this->result;
	}
}
