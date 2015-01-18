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

	public function testCheckColumns() {
		/**
		 * Column
		 */
		$column = new Table\Column([
			'Field'   => 'id',
			'Type'    => 'int(10) unsigned',
			'Null'    => 'NO',
			'Key'     => 'MUL',
			'Default' => '',
			'Extra'   => 'auto_increment'
		]);

		$columnResult = $column->getResult();

		/**
		 * Table
		 */
		$table = new Table(null, null);

		$tableResult = $table->checkColumns([$column]);

		$this->assertSame($columnResult, $tableResult);
	}

	public function testCheckIsDuplicate() {
		/**
		 * Indices
		 */
		$indices = [
			new Table\Index('id', null, ['id']),
			new Table\Index('id2', null, ['id'])
		];

		/**
		 * Table
		 */
		$table = new Table(null, null);

		$result = $table->checkDuplicateIndices($indices);

		$expected = [
			[
				'type'        => 'index',
				'key'         => 'id2',
				'description' => 'Is duplicate of id'
			]
		];

		$this->assertSame($expected, $result);
	}

	public function testCheckIsDuplicateEmpty() {
		/**
		 * Indices
		 */
		$indices = [
			new Table\Index('id', null, ['id']),
			new Table\Index('active', null, ['active'])
		];

		/**
		 * Table
		 */
		$table = new Table(null, null);

		$result = $table->checkDuplicateIndices($indices);

		$this->assertSame([], $result);
	}

	public function testCheckRedundantIndicesOnPrimaryKey() {
		/**
		 * Column
		 */
		$columns = [
			new Table\Column([
				'Field'   => 'id',
				'Type'    => 'int(10) unsigned',
				'Null'    => 'NO',
				'Key'     => 'PRI',
				'Default' => '',
				'Extra'   => 'auto_increment'
			])
		];

		/**
		 * Indicies
		 */
		$indices = [
			new Table\Index('PRIMARY', false, ['id']),
			new Table\Index('id', true, ['id'])
		];

		/**
		 * Table
		 */
		$table = new Table(null, null);

		$result = $table->checkRedundantIndicesOnPrimaryKey($columns, $indices);

		$expected = [
			[
				'type'        => 'index',
				'key'         => 'id',
				'description' => 'An unique index on the primary key column is redundant'
			]
		];

		$this->assertSame($expected, $result);
	}
}
