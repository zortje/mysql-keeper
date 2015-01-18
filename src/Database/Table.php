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
	 * @param Column[] $columns Table columns
	 * @param Index[]  $indices Table indices
	 */
	public function __construct($columns, $indices) {
		$this->columns = $columns;
		$this->indices = $indices;
	}

	/**
	 * Get result of table
	 *
	 * @return array Result
	 */
	public function getResult() {
		/**
		 * Reset result
		 */
		$result = [];

		/**
		 * Go though columns and get result
		 * Find duplicate indices
		 */
		$result = array_merge($result, $this->checkColumns($this->columns));
		$result = array_merge($result, $this->checkDuplicateIndices($this->indices));
		$result = array_merge($result, $this->checkRedundantIndicesOnPrimaryKey($this->columns, $this->indices));

		return $result;
	}

	/**
	 * Get result of columns
	 *
	 * @param Column[] $columns Table columns
	 *
	 * @return array Result
	 */
	public function checkColumns($columns) {
		$result = [];

		foreach ($columns as $column) {
			$result = array_merge($result, $column->getResult());
		}

		return $result;
	}

	/**
	 * Check for duplicate indices
	 *
	 * @param Index[] $indices Table indices
	 *
	 * @return array Result
	 */
	public function checkDuplicateIndices($indices) {
		$result = [];

		foreach ($indices as $i => $index) {
			foreach ($indices as $j => $indexTwo) {
				/**
				 * Only check index that came before current index
				 */
				if ($j >= $i) {
					break;
				}

				/**
				 * Check if index is duplicate
				 */
				if ($index->isDuplicate($indexTwo) === true) {
					$result[] = [
						'type'        => 'index',
						'key'         => $index->getKeyName(),
						'description' => sprintf('Is duplicate of %s', $indexTwo->getKeyName())
					];

					break;
				}
			}
		}

		return $result;
	}

	/**
	 * Check for redundant indices on primary key column
	 *
	 * @param Column[] $columns Table columns
	 * @param Index[]  $indices Table indices
	 *
	 * @return array Result
	 */
	public function checkRedundantIndicesOnPrimaryKey($columns, $indices) {
		$result = [];

		foreach ($columns as $column) {
			/**
			 * Check primary key column
			 */
			if ($column->isPrimaryKey() === true) {
				foreach ($indices as $index) {
					/**
					 * Skip the primary key index
					 */
					if ($index->isPrimaryKey() === true) {
						continue;
					}

					/**
					 * Check indices with just our primary key column
					 */
					if ($index->getColumns() === [$column->getField()]) {
						/**
						 * Check if index is unique
						 */
						$result[] = [
							'type'        => 'index',
							'key'         => $index->getKeyName(),
							'description' => sprintf('An %s index on the primary key column is redundant', $index->isUnique() === true ? 'unique' : 'key')
						];
					}
				}
			}
		}

		return $result;
	}
}
