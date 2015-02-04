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
		reset($this->columns);
	}

	/**
	 * Checks if current position is valid
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function valid() {
		$key = key($this->columns);
		$var = ($key !== null && $key !== false);

		return $var;
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
