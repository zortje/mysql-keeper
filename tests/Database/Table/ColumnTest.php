<?php

namespace Zortje\MySQLKeeper\Tests\Database\Table;

use Zortje\MySQLKeeper\Database\Table;
use Zortje\MySQLKeeper\Database\Table\Column;

/**
 * Class ColumnTest
 *
 * @package Zortje\MySQLKeeper\Tests\Database\Table
 */
class ColumnTest extends \PHPUnit_Framework_TestCase {

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

	public function testResetOfIssues() {
		$row = [
			'Field'     => 'id',
			'Type'      => 'int(10) unsigned',
			'Collation' => '',
			'Null'      => 'NO',
			'Key'       => 'MUL',
			'Default'   => '',
			'Extra'     => 'auto_increment'
		];

		/**
		 * Check getResult once and save the result
		 */
		$column = new Column($row);

		$result = $column->getResult();

		/**
		 * Check getResult again and assert saved results array is the same size as the current issues
		 */
		$this->assertSameSize($result, $column->getResult());
	}
}
