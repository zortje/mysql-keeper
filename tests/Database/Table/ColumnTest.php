<?php

namespace Zortje\MySQLKeeper\Tests\Database\Table;

use Zortje\MySQLKeeper\Database\Table\Column;

/**
 * Class ColumnTest
 *
 * @package Zortje\MySQLKeeper\Tests\Database\Table
 */
class ColumnTest extends \PHPUnit_Framework_TestCase {

	public function testIncorrectAutoIncrementKey() {
		$row = [
			'Field'   => 'id',
			'Type'    => 'int(10) unsigned',
			'Null'    => 'NO',
			'Key'     => 'MUL',
			'Default' => '',
			'Extra'   => 'auto_increment'
		];

		$column = new Column($row);

		$result = $column->getResult();

		$this->assertGreaterThan(0, count($result));
		$this->assertTrue(in_array('Set as auto_increment but has no primary key', $result[0]));
	}
}
