<?php

namespace Zortje\MySQLKeeper\Database\Table;

/**
 * Class ColumnCollection
 *
 * @package Zortje\MySQLKeeper\Database\Table
 */
class ColumnCollection implements \Iterator, \Countable {

	/**
	 * @var Column[] Table columns
	 */
	private $columns;

	/**
	 * Add column to collection
	 *
	 * @param Column $column
	 */
	public function add(Column $column) {
		$this->columns[] = $column;
	}

	/**
	 * Get column collection with primary key columns
	 *
	 * @return ColumnCollection
	 */
	public function isPrimaryKey() {
		$columns = new ColumnCollection();

		foreach ($this->columns as $column) {
			if ($column->isPrimaryKey() === true) {
				$columns->add($column);
			}
		}

		return $columns;
	}

	/**
	 * Return the current column
	 *
	 * @return Column Table column
	 */
	public function current() {
		$column = current($this->columns);

		return $column;
	}

	/**
	 * Return the key of the current column
	 *
	 * @return mixed scalar on success, or null on failure.
	 */
	public function key() {
		$key = key($this->columns);

		return $key;
	}

	/**
	 * Move forward to next column
	 */
	public function next() {
		next($this->columns);
	}

	/**
	 * Rewind the collection to the first column
	 */
	public function rewind() {
		if (!empty($this->columns)) {
			reset($this->columns);
		}
	}

	/**
	 * Checks if current position is valid
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function valid() {
		if (!empty($this->columns)) {
			$key   = key($this->columns);
			$valid = ($key !== null && $key !== false);
		} else {
			$valid = false;
		}

		return $valid;
	}

	/**
	 * Count elements of an object
	 *
	 * @return int Count
	 */
	public function count() {
		$count = count($this->columns);

		return $count;
	}
}
