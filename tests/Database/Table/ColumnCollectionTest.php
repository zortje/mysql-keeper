<?php

namespace Zortje\MySQLKeeper\Tests\Database\Table;

use Zortje\MySQLKeeper\Database\Table\Column;
use Zortje\MySQLKeeper\Database\Table\ColumnCollection;

/**
 * Class ColumnCollectionTest
 *
 * @package            Zortje\MySQLKeeper\Tests\Database\Table
 *
 * @coversDefaultClass Zortje\MySQLKeeper\Database\Table\ColumnCollection
 */
class ColumnCollectionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::add
	 */
	public function testAdd() {
		$columns = new ColumnCollection();
		$this->assertSame(0, count($columns));
		$this->assertSame(0, $columns->count());

		$columns->add(new Column(null));
		$this->assertSame(1, count($columns));
		$this->assertSame(1, $columns->count());

		$columns->add(new Column(null));
		$this->assertSame(2, count($columns));
		$this->assertSame(2, $columns->count());
	}

	/**
	 * @covers ::add
	 *
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Collection may only contain "Zortje\MySQLKeeper\Database\Table\Column" objects, "integer" is not allowed
	 */
	public function testAddException() {
		$columns = new ColumnCollection();
		$columns->add(42);
	}

	/**
	 * @covers ::isPrimaryKey
	 */
	public function testIsPrimaryKey() {
		$columns = new ColumnCollection();

		$this->assertSame(0, $columns->isPrimaryKey()->count());

		$columns->add(new Column([
			'Field'     => null,
			'Type'      => null,
			'Collation' => null,
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		]));

		$this->assertSame(0, $columns->isPrimaryKey()->count());

		$columns->add(new Column([
			'Field'     => 'foo',
			'Type'      => null,
			'Collation' => null,
			'Null'      => null,
			'Key'       => 'PRI',
			'Default'   => null,
			'Extra'     => null
		]));

		$this->assertSame(1, $columns->isPrimaryKey()->count());
	}
}
