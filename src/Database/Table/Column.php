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
	private $collation;

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
	 * @param array $column Column information
	 */
	public function __construct($column) {
		$this->field     = $column['Field'];
		$this->type      = $column['Type'];
		$this->collation = $column['Collation'];
		$this->null      = $column['Null'];
		$this->key       = $column['Key'];
		$this->default   = $column['Default'];
		$this->extra     = $column['Extra'];
	}

	/**
	 * Get Column field
	 *
	 * @return string Column field
	 */
	public function getField() {
		return $this->field;
	}

	/**
	 * Get Column collation
	 *
	 * @return string Column collation
	 */
	public function getCollation() {
		return $this->collation;
	}

	/**
	 * Get has Column collation
	 *
	 * @return bool TRUE if Column has collation, otherwise FALSE
	 */
	public function hasCollation() {
		$hasCollation = strlen($this->getCollation()) > 0;

		return $hasCollation;
	}

	/**
	 * Is Column primary key
	 *
	 * @return bool TRUE if primary key, otherwise FALSE
	 */
	public function isPrimaryKey() {
		$isPrimaryKey = $this->key === 'PRI';

		return $isPrimaryKey;
	}

	/**
	 * Is Column auto increment
	 *
	 * @return bool TRUE if auto increment, otherwise FALSE
	 */
	public function isAutoIncrement() {
		$isAutoIncrement = $this->extra === 'auto_increment';

		return $isAutoIncrement;
	}

	/**
	 * Get result for column
	 *
	 * @return array Result
	 */
	public function getResult() {
		$result = [];

		/**
		 * auto_increment checks
		 */
		$result = array_merge($result, $this->checkAutoIncrement($this));

		return $result;
	}

	/**
	 * Check auto increment column
	 *
	 * @param Column $column Table column
	 *
	 * @return array Result
	 */
	public function checkAutoIncrement(Column $column) {
		$result = [];

		if ($column->isAutoIncrement() === true && $column->isPrimaryKey() === false) {
			$result[] = [
				'type'        => 'column',
				'field'       => $column->getField(),
				'description' => 'Set as auto_increment but has no primary key'
			];
		}

		return $result;
	}
}
