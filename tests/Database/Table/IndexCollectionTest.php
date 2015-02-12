<?php

namespace Zortje\MySQLKeeper\Tests\Database\Table;

use Zortje\MySQLKeeper\Database\Table\Index;
use Zortje\MySQLKeeper\Database\Table\IndexCollection;

/**
 * Class IndexCollectionTest
 *
 * @package Zortje\MySQLKeeper\Tests\Database\Table
 */
class IndexCollectionTest extends \PHPUnit_Framework_TestCase {

	public function testAdd() {
		$indices = new IndexCollection();
		$this->assertSame(0, count($indices));
		$this->assertSame(0, $indices->count());

		$indices->add(new Index(null, null, null));
		$this->assertSame(1, count($indices));
		$this->assertSame(1, $indices->count());

		$indices->add(new Index(null, null, null));
		$this->assertSame(2, count($indices));
		$this->assertSame(2, $indices->count());
	}

	public function testIsNotPrimaryKey() {
		$indices = new IndexCollection();

		$this->assertSame(0, $indices->isNotPrimaryKey()->count());

		$indices->add(new Index('PRIMARY', null, null));

		$this->assertSame(0, $indices->isNotPrimaryKey()->count());

		$indices->add(new Index(null, null, null));

		$this->assertSame(1, $indices->isNotPrimaryKey()->count());
	}
}
