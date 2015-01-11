<?php

namespace Zortje\MySQLKeeper\Tests\Database;

use Zortje\MySQLKeeper\Database\Table;

/**
 * Class TableTest
 *
 * @package Zortje\MySQLKeeper\Tests\Database
 */
class TableTest extends \PHPUnit_Framework_TestCase {

	public function testTableResult() {
		$columns = [
			new Table\Column([
				'Field'   => 'id',
				'Type'    => 'int(10) unsigned',
				'Null'    => 'NO',
				'Key'     => 'MUL',
				'Default' => '',
				'Extra'   => 'auto_increment'
			])
		];

		$table = new Table($columns, []);

		$result = $table->getResult();

		$this->assertGreaterThan(0, count($result));
		$this->assertTrue(in_array('Set as auto_increment but has no primary key', $result[0]));
	}

	public function testResetOfIssues() {
		$columns = [
			new Table\Column([
				'Field'   => 'id',
				'Type'    => 'int(10) unsigned',
				'Null'    => 'NO',
				'Key'     => 'MUL',
				'Default' => '',
				'Extra'   => 'auto_increment'
			])
		];

		/**
		 * Check getResult once and save the result
		 */
		$table = new Table($columns, []);

		$result = $table->getResult();

		/**
		 * Check getResult again and assert saved results array is the same size as the current issues
		 */
		$this->assertSameSize($result, $table->getResult());
	}
}
