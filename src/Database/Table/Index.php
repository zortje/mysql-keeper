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
		$this->keyName  = $keyName;
		$this->$columns = $columns;
	}
}
