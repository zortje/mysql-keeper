<?php

namespace Zortje\MySQLKeeper\Database\Table;

use Zortje\MySQLKeeper\Common\Collection;

/**
 * Class ColumnCollection
 *
 * @package Zortje\MySQLKeeper\Database\Table
 */
class ColumnCollection extends Collection {

	/**
	 * @var Column[] Table columns
	 */
	protected $collection = [];

	/**
	 * Add column to collection
	 *
	 * @param Column $column
	 *
	 * @throws \InvalidArgumentException if incorrect object type is added
	 */
	public function add($column) {
		if (!is_object($column) || get_class($column) !== get_class(new Column(null))) {
			$argumentType = is_object($column) ? get_class($column) : gettype($column);

			throw new \InvalidArgumentException(sprintf('Collection may only contain "%s" objects, "%s" is not allowed', get_class(new Column(null)), $argumentType));
		}

		$this->collection[] = $column;
	}

	/**
	 * Get column collection with primary key columns
	 *
	 * @return ColumnCollection
	 */
	public function isPrimaryKey() {
		$columns = new ColumnCollection();

		foreach ($this->collection as $column) {
			if ($column->isPrimaryKey() === true) {
				$columns->add($column);
			}
		}

		return $columns;
	}
}
