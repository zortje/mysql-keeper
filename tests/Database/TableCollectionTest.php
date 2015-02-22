<?php

namespace Zortje\MySQLKeeper\Tests\Database\Table;

use Zortje\MySQLKeeper\Database\Table;
use Zortje\MySQLKeeper\Database\TableCollection;

/**
 * Class TableCollectionTest
 *
 * @package            Zortje\MySQLKeeper\Tests\Database\Table
 *
 * @coversDefaultClass Zortje\MySQLKeeper\Database\TableCollection
 */
class TableCollectionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::add
	 */
	public function testAdd() {
		$tables = new TableCollection();
		$this->assertSame(0, count($tables));
		$this->assertSame(0, $tables->count());

		$tables->add(new Table(null, null, new Table\ColumnCollection(), new Table\IndexCollection()));
		$this->assertSame(1, count($tables));
		$this->assertSame(1, $tables->count());

		$tables->add(new Table(null, null, new Table\ColumnCollection(), new Table\IndexCollection()));
		$this->assertSame(2, count($tables));
		$this->assertSame(2, $tables->count());
	}

	/**
	 * @covers ::add
	 *
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Collection may only contain "Zortje\MySQLKeeper\Database\Table" objects, "integer" is not allowed
	 */
	public function testAddException() {
		$tables = new TableCollection();
		$tables->add(42);
	}
}
