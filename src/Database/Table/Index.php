<?php

namespace Zortje\MySQLKeeper\Database\Table;

/**
 * Class Index
 *
 * @package Zortje\MySQLKeeper\Database\Table
 */
class Index {

	/**
	 * @var string
	 */
	private $keyName;

	/**
	 * @var string[]
	 */
	private $columns;

	/**
	 * @param string   $keyName Index key name
	 * @param string[] $columns Index column names
	 */
	public function __construct($keyName, $columns) {
		$this->keyName = $keyName;
		$this->columns = $columns;
	}

	/**
	 * Get Index key name
	 *
	 * @return string Index key name
	 */
	public function getKeyName() {
		return $this->keyName;
	}

	/**
	 * Get Index column names
	 *
	 * @return string[] Index column names
	 */
	public function getColumns() {
		return $this->columns;
	}

	/**
	 * Check if given Index is duplicate of this
	 *
	 * @param Index $index Index
	 *
	 * @return bool TRUE if duplicate, otherwise FALSE
	 */
	public function isDuplicate(Index $index) {
		$duplicate = $this->getColumns() === $index->getColumns();

		return $duplicate;
	}
}
