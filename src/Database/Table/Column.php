<?php

namespace Zortje\MySQLKeeper\Database\Table;

/**
 * Class Column
 *
 * @package Zortje\MySQLKeeper\Database\Table
 */
class Column {

	/**
	 * @var string
	 */
	private $field;

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $null;

	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var null|string
	 */
	private $default;

	/**
	 * @var string
	 */
	private $extra;

	/**
	 * @var array
	 */
	private $result = [];

	/**
	 * @param array $column Column information
	 */
	public function __construct($column) {
		$this->field   = $column['Field'];
		$this->type    = $column['Type'];
		$this->null    = $column['Null'];
		$this->key     = $column['Key'];
		$this->default = $column['Default'];
		$this->extra   = $column['Extra'];
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
		 * auto_increment checks
		 */
		if ($this->extra === 'auto_increment') {
			if ($this->key !== 'PRI') {
				$this->result[] = [
					'type'        => 'column',
					'field'       => $this->field,
					'description' => 'Set as auto_increment but has no primary key'
				];
			}
		}

		return $this->result;
	}
}
