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
	 */
	public function add($column) {
		if (get_class($column) !== 'Zortje\MySQLKeeper\Database\Table\Column') {
			throw new \InvalidArgumentException(sprintf('Collection may only contain "Zortje\MySQLKeeper\Database\Table\Column" objects, "%s" is not allowed', get_class($column)));
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
