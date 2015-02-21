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
	public function add($index) {
		if (get_class($index) !== 'Zortje\MySQLKeeper\Database\Table\Index') {
			throw new \InvalidArgumentException(sprintf('Collection may only contain "Zortje\MySQLKeeper\Database\Table\Index" objects, "%s" is not allowed', get_class($index)));
		}

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
