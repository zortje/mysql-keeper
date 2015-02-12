<?php

namespace Zortje\MySQLKeeper\Database;

use Zortje\MySQLKeeper\Database\Table\ColumnCollection;
use Zortje\MySQLKeeper\Database\Table\IndexCollection;

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
	 * @var IndexCollection Table indices
	 */
	private $indices;

	/**
	 * @param string           $name      Table name
	 * @param string           $collation Table collation
	 * @param ColumnCollection $columns   Table columns
	 * @param IndexCollection  $indices   Table indices
	 */
	public function __construct($name, $collation, ColumnCollection $columns, IndexCollection $indices) {
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
	 * @param IndexCollection $indices Table indices
	 *
	 * @return array Result
	 */
	public function checkDuplicateIndices(IndexCollection $indices) {
		$result = [];

		$indicesSecondary = clone $indices;

		foreach ($indices as $i => $index) {
			foreach ($indicesSecondary as $j => $indexSecondary) {
				/**
				 * Only check index that came before current index
				 */
				if ($j >= $i) {
					break;
				}

				/**
				 * Check if index is duplicate
				 */
				if ($index->isDuplicate($indexSecondary) === true) {
					$result[] = [
						'type'        => 'index',
						'key'         => $index->getKeyName(),
						'description' => sprintf('Is duplicate of %s', $indexSecondary->getKeyName())
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
	 * @param IndexCollection  $indices Table indices
	 *
	 * @return array Result
	 */
	public function checkRedundantIndicesOnPrimaryKey(ColumnCollection $columns, IndexCollection $indices) {
		$result = [];

		/**
		 * Check primary key columns
		 */
		foreach ($columns->isPrimaryKey() as $column) {
			/**
			 * Check non primary key indices
			 */
			foreach ($indices->isNotPrimaryKey() as $index) {
				/**
				 * Check indices with just our primary key column
				 */
				if ($index->getColumns() === [$column->getField()]) { // @todo Create method to validate this
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
