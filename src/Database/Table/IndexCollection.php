<?php

namespace Zortje\MySQLKeeper\Database\Table;

use Zortje\MySQLKeeper\Common\Collection;

/**
 * Class IndexCollection
 *
 * @package Zortje\MySQLKeeper\Database\Table
 */
class IndexCollection extends Collection {

	/**
	 * @var Index[] Table indices
	 */
	protected $collection = [];

	/**
	 * Add index to collection
	 *
	 * @param Index $index
	 */
	public function add(Index $index) {
		$this->collection[] = $index;
	}

	/**
	 * Get index collection with non primary key indices
	 *
	 * @return IndexCollection
	 */
	public function isNotPrimaryKey() {
		$indices = new IndexCollection();

		foreach ($this->collection as $index) {
			if ($index->isPrimaryKey() === false) {
				$indices->add($index);
			}
		}

		return $indices;
	}
}
