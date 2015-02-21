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
	 *
	 * @throws \InvalidArgumentException if incorrect object type is added
	 */
	public function add($index) {
		if (!is_object($index) || get_class($index) !== 'Zortje\MySQLKeeper\Database\Table\Index') {
			$argumentType = is_object($index) ? get_class($index) : gettype($index);

			throw new \InvalidArgumentException(sprintf('Collection may only contain "%s" objects, "%s" is not allowed', 'Zortje\MySQLKeeper\Database\Table\Index', $argumentType));
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
