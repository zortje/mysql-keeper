<?php

namespace Zortje\MySQLKeeper\Database\Table;

/**
 * Class IndexCollection
 *
 * @package Zortje\MySQLKeeper\Database\Table
 */
class IndexCollection implements \Iterator, \Countable {

	/**
	 * @var Index[] Table indices
	 */
	private $indices = [];

	/**
	 * Add index to collection
	 *
	 * @param Index $index
	 */
	public function add(Index $index) {
		$this->indices[] = $index;
	}

	/**
	 * Get index collection with non primary key indices
	 *
	 * @return IndexCollection
	 */
	public function isNotPrimaryKey() {
		$indices = new IndexCollection();

		foreach ($this->indices as $index) {
			if ($index->isPrimaryKey() === false) {
				$indices->add($index);
			}
		}

		return $indices;
	}

	/**
	 * Return the current index
	 *
	 * @return false|Index Table index
	 */
	public function current() {
		$index = current($this->indices);

		return $index;
	}

	/**
	 * Return the key of the current index
	 *
	 * @return mixed scalar on success, or null on failure.
	 */
	public function key() {
		$key = key($this->indices);

		return $key;
	}

	/**
	 * Move forward to next index
	 */
	public function next() {
		next($this->indices);
	}

	/**
	 * Rewind the collection to the first index
	 */
	public function rewind() {
		if (is_array($this->indices) === true) {
			reset($this->indices);
		}
	}

	/**
	 * Checks if current position is valid
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function valid() {
		$key   = key($this->indices);
		$valid = ($key !== null && $key !== false);

		return $valid;
	}

	/**
	 * Count elements of an object
	 *
	 * @return int Count
	 */
	public function count() {
		$count = count($this->indices);

		return $count;
	}
}
