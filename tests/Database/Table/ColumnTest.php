<?php

namespace Zortje\MySQLKeeper\Tests\Database\Table;

use Zortje\MySQLKeeper\Database\Table;
use Zortje\MySQLKeeper\Database\Table\Column;

/**
 * Class ColumnTest
 *
 * @package            Zortje\MySQLKeeper\Tests\Database\Table
 *
 * @coversDefaultClass Zortje\MySQLKeeper\Database\Table\Column
 */
class ColumnTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::getField
	 */
	public function testGetField() {
		$row = [
			'Field'     => 'id',
			'Type'      => null,
			'Collation' => null,
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		];

		$column = new Column($row);

		$this->assertSame('id', $column->getField());
	}

	/**
	 * @covers ::getField
	 */
	public function testGetFieldNull() {
		$row = [
			'Field'     => null,
			'Type'      => null,
			'Collation' => null,
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		];

		$column = new Column($row);

		$this->assertSame(null, $column->getField());
	}

	/**
	 * @covers ::getField
	 */
	public function testGetFieldEmpty() {
		$row = [
			'Field'     => '',
			'Type'      => null,
			'Collation' => null,
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		];

		$column = new Column($row);

		$this->assertSame('', $column->getField());
	}

	/**
	 * @covers ::getCollation
	 */
	public function testGetCollation() {
		$row = [
			'Field'     => null,
			'Type'      => null,
			'Collation' => 'utf8_unicode_ci',
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		];

		$column = new Column($row);

		$this->assertSame('utf8_unicode_ci', $column->getCollation());
	}

	/**
	 * @covers ::getCollation
	 */
	public function testGetCollationNull() {
		$row = [
			'Field'     => null,
			'Type'      => null,
			'Collation' => null,
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		];

		$column = new Column($row);

		$this->assertSame(null, $column->getCollation());
	}

	/**
	 * @covers ::getCollation
	 */
	public function testGetCollationEmpty() {
		$row = [
			'Field'     => null,
			'Type'      => null,
			'Collation' => '',
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		];

		$column = new Column($row);

		$this->assertSame('', $column->getCollation());
	}

	/**
	 * @covers ::hasCollation
	 */
	public function testHasCollation() {
		$row = [
			'Field'     => null,
			'Type'      => null,
			'Collation' => 'utf8_unicode_ci',
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		];

		$column = new Column($row);

		$this->assertTrue($column->hasCollation());
	}

	/**
	 * @covers ::hasCollation
	 */
	public function testHasCollationNull() {
		$row = [
			'Field'     => null,
			'Type'      => null,
			'Collation' => null,
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		];

		$column = new Column($row);

		$this->assertFalse($column->hasCollation());
	}

	/**
	 * @covers ::hasCollation
	 */
	public function testHasCollationEmpty() {
		$row = [
			'Field'     => null,
			'Type'      => null,
			'Collation' => '',
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		];

		$column = new Column($row);

		$this->assertFalse($column->hasCollation());
	}

	/**
	 * @covers ::isPrimaryKey
	 */
	public function testIsPrimaryKey() {
		$row = [
			'Field'     => 'id',
			'Type'      => 'int(10) unsigned',
			'Collation' => '',
			'Null'      => 'NO',
			'Key'       => 'PRI',
			'Default'   => '',
			'Extra'     => 'auto_increment'
		];

		$column = new Column($row);

		$this->assertTrue($column->isPrimaryKey());
	}

	/**
	 * @covers ::isPrimaryKey
	 */
	public function testIsPrimaryKeyNot() {
		$row = [
			'Field'     => 'modified',
			'Type'      => 'datetime',
			'Collation' => '',
			'Null'      => 'NO',
			'Key'       => '',
			'Default'   => '',
			'Extra'     => ''
		];

		$column = new Column($row);

		$this->assertFalse($column->isPrimaryKey());
	}

	/**
	 * @covers ::isAutoIncrement
	 */
	public function testIsAutoIncrement() {
		$row = [
			'Field'     => 'id',
			'Type'      => 'int(10) unsigned',
			'Collation' => '',
			'Null'      => 'NO',
			'Key'       => 'PRI',
			'Default'   => '',
			'Extra'     => 'auto_increment'
		];

		$column = new Column($row);

		$this->assertTrue($column->isAutoIncrement());
	}

	/**
	 * @covers ::isAutoIncrement
	 */
	public function testIsAutoIncrementNot() {
		$row = [
			'Field'     => 'modified',
			'Type'      => 'datetime',
			'Collation' => '',
			'Null'      => 'NO',
			'Key'       => '',
			'Default'   => '',
			'Extra'     => ''
		];

		$column = new Column($row);

		$this->assertFalse($column->isAutoIncrement());
	}

	/**
	 * @covers ::getResult
	 */
	public function testIncorrectAutoIncrementKey() {
		$row = [
			'Field'     => 'id',
			'Type'      => 'int(10) unsigned',
			'Collation' => '',
			'Null'      => 'NO',
			'Key'       => 'MUL',
			'Default'   => '',
			'Extra'     => 'auto_increment'
		];

		$column = new Column($row);

		$result = $column->getResult();

		$this->assertGreaterThan(0, count($result));
		$this->assertTrue(in_array('Set as auto_increment but has no primary key', $result[0]));
	}

	/**
	 * @covers ::getResult
	 */
	public function testCheckAutoIncrement() {
		/**
		 * Auto increment
		 */
		$row = [
			'Field'     => 'id',
			'Type'      => 'int(10) unsigned',
			'Collation' => '',
			'Null'      => 'NO',
			'Key'       => 'MUL',
			'Default'   => '',
			'Extra'     => 'auto_increment'
		];

		$column = new Column($row);

		$result = $column->getResult();

		$this->assertGreaterThan(0, count($result));
		$this->assertTrue(in_array('Set as auto_increment but has no primary key', $result[0]));

		/**
		 * Regular
		 */
		$row    = [
			'Field'     => 'modified',
			'Type'      => 'datetime',
			'Collation' => '',
			'Null'      => 'NO',
			'Key'       => '',
			'Default'   => '',
			'Extra'     => ''
		];
		$column = new Column($row);

		$result = $column->getResult();

		$this->assertSame(0, count($result));
	}

	// @todo Test checkAutoIncrement

	// @todo tests for ::getResult should determine expected result from checkAutoIncrement($column) and then seeing the actual result from getResult()
}
