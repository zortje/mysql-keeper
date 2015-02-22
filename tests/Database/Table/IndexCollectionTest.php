<?php

namespace Zortje\MySQLKeeper\Tests\Database\Table;

use Zortje\MySQLKeeper\Database\Table\Index;
use Zortje\MySQLKeeper\Database\Table\IndexCollection;

/**
 * Class IndexCollectionTest
 *
 * @package            Zortje\MySQLKeeper\Tests\Database\Table
 *
 * @coversDefaultClass Zortje\MySQLKeeper\Database\Table\IndexCollection
 */
class IndexCollectionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::add
	 */
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

	/**
	 * @covers ::add
	 *
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Collection may only contain "Zortje\MySQLKeeper\Database\Table\Index" objects, "integer" is not allowed
	 */
	public function testAddException() {
		$indices = new IndexCollection();
		$indices->add(42);
	}

	/**
	 * @covers ::isNotPrimaryKey
	 */
	public function testIsNotPrimaryKey() {
		$indices = new IndexCollection();

		$this->assertSame(0, $indices->isNotPrimaryKey()->count());

		$indices->add(new Index('PRIMARY', null, null));

		$this->assertSame(0, $indices->isNotPrimaryKey()->count());

		$indices->add(new Index(null, null, null));

		$this->assertSame(1, $indices->isNotPrimaryKey()->count());
	}
}
