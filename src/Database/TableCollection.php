<?php

namespace Zortje\MySQLKeeper\Database;

use Zortje\MySQLKeeper\Common\Collection;

/**
 * Class TableCollection
 *
 * @package Zortje\MySQLKeeper\Database
 */
class TableCollection extends Collection {

	/**
	 * @var Table[] Database tabel
	 */
	protected $collection = [];

	/**
	 * Add table to collection
	 *
	 * @param Table $table
	 *
	 * @throws \InvalidArgumentException if incorrect object type is added
	 */
	public function add($table) {
		if (!is_object($table) || get_class($table) !== 'Zortje\MySQLKeeper\Database\Table\Table') {
			$argumentType = is_object($table) ? get_class($table) : gettype($table);

			throw new \InvalidArgumentException(sprintf('Collection may only contain "%s" objects, "%s" is not allowed', 'Zortje\MySQLKeeper\Database\Table', $argumentType));
		}

		$this->collection[] = $table;
	}
}
