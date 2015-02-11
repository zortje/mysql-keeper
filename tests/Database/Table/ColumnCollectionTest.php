<?php

namespace Zortje\MySQLKeeper\Tests\Database\Table;

use Zortje\MySQLKeeper\Database\Table\Column;
use Zortje\MySQLKeeper\Database\Table\ColumnCollection;

/**
 * Class ColumnCollectionTest
 *
 * @package Zortje\MySQLKeeper\Tests\Database\Table
 */
class ColumnCollectionTest extends \PHPUnit_Framework_TestCase {

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

	public function testCurrent() {
		$columns = new ColumnCollection();

		$columns->add(new Column([
			'Field'     => 'foo',
			'Type'      => null,
			'Collation' => null,
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		]));

		$columns->add(new Column([
			'Field'     => 'bar',
			'Type'      => null,
			'Collation' => null,
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		]));

		$this->assertSame('foo', $columns->Current()->getField());

		$columns->next();
		$this->assertSame('bar', $columns->Current()->getField());

		$columns->rewind();
		$this->assertSame('foo', $columns->Current()->getField());
	}

	public function testKeyNext() {
		$columns = new ColumnCollection();
		$columns->add(new Column(null));
		$columns->add(new Column(null));
		$this->assertSame(0, $columns->key());

		$columns->next();
		$this->assertSame(1, $columns->key());

		$columns->rewind();
		$this->assertSame(0, $columns->key());
	}

	public function testValid() {
		$columns = new ColumnCollection();
		$columns->add(new Column(null));
		$columns->add(new Column(null));

		$columns->next();
		$this->assertTrue($columns->valid());

		$columns->next();
		$this->assertFalse($columns->valid());
	}
}
