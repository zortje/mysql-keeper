<?php

namespace Zortje\MySQLKeeper\Database;

use Zortje\MySQLKeeper\Database\Table\ColumnCollection;
use Zortje\MySQLKeeper\Database\Table\Index;

/**
 * Class Table
 *
 * @package Zortje\MySQLKeeper\Database
 */
class Table {

	/**
	 * @var string Table name
	 */
	private $name;

	/**
	 * @var string Table collation
	 */
	private $collation;

	/**
	 * @var ColumnCollection Table columns
	 */
	private $columns;

	/**
	 * @var Index[] Table indices
	 */
	private $indices;

	/**
	 * @param string           $name      Table name
	 * @param string           $collation Table collation
	 * @param ColumnCollection $columns   Table columns
	 * @param Index[]          $indices   Table indices
	 */
	public function __construct($name, $collation, ColumnCollection $columns = null, $indices) {
		$this->name      = $name;
		$this->collation = $collation;
		$this->columns   = $columns;
		$this->indices   = $indices;
	}

	/**
	 * Get Table name
	 *
	 * @return string Table name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get Table collation
	 *
	 * @return string Table collation
	 */
	public function getCollation() {
		return $this->collation;
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
		 * Check redundant indices on primary key
		 * Check collation mismatch between table and columns
		 */
		$result = array_merge($result, $this->checkColumns($this->columns));
		$result = array_merge($result, $this->checkDuplicateIndices($this->indices));
		$result = array_merge($result, $this->checkRedundantIndicesOnPrimaryKey($this->columns, $this->indices));
		$result = array_merge($result, $this->checkCollationMismatchBetweenTableAndColumns($this->columns));

		return $result;
	}

	/**
	 * Get result of columns
	 *
	 * @param ColumnCollection $columns Table columns
	 *
	 * @return array Result
	 */
	public function checkColumns(ColumnCollection $columns) {
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
	 * @param ColumnCollection $columns Table columns
	 * @param Index[]          $indices Table indices
	 *
	 * @return array Result
	 */
	public function checkRedundantIndicesOnPrimaryKey(ColumnCollection $columns, $indices) {
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

	/**
	 * @param ColumnCollection $columns Table columns
	 *
	 * @return array Result
	 */
	public function checkCollationMismatchBetweenTableAndColumns(ColumnCollection $columns) {
		$result = [];

		foreach ($columns as $column) {
			if ($column->hasCollation() === true) {
				if ($column->getCollation() !== $this->getCollation()) {
					$result[] = [
						'type'        => 'column',
						'key'         => $column->getField(),
						'description' => 'Column is not using same collation as table'
					];
				}
			}
		}

		return $result;
	}
}
