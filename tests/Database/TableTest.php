<?php

namespace Zortje\MySQLKeeper\Tests\Database;

use Zortje\MySQLKeeper\Database\Table;

/**
 * Class TableTest
 *
 * @package            Zortje\MySQLKeeper\Tests\Database
 *
 * @coversDefaultClass Zortje\MySQLKeeper\Database\Table
 */
class TableTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::getName
	 */
	public function testGetName() {
		$table = new Table('users', null, new Table\ColumnCollection(), new Table\IndexCollection());

		$this->assertSame('users', $table->getName());
	}

	/**
	 * @covers ::getCollation
	 */
	public function testGetCollation() {
		$table = new Table(null, 'utf8mb4_unicode_ci', new Table\ColumnCollection(), new Table\IndexCollection());

		$this->assertSame('utf8mb4_unicode_ci', $table->getCollation());
	}

	/**
	 * @covers ::getResult
	 */
	public function testTableResult() {
		$column = new Table\Column([
			'Field'     => 'id',
			'Type'      => 'int(10) unsigned',
			'Collation' => '',
			'Null'      => 'NO',
			'Key'       => 'MUL',
			'Default'   => '',
			'Extra'     => 'auto_increment'
		]);

		$columns = new Table\ColumnCollection();
		$columns->add($column);

		$table = new Table(null, null, $columns, new Table\IndexCollection());

		$result = $table->getResult();

		$this->assertSame(1, count($result));
		$this->assertSame('Set as auto_increment but has no primary key', $result['issues'][0]['description']);
	}

	/**
	 * @covers ::getResult
	 */
	public function testResetOfIssues() {
		$column = new Table\Column([
			'Field'     => 'id',
			'Type'      => 'int(10) unsigned',
			'Collation' => '',
			'Null'      => 'NO',
			'Key'       => 'MUL',
			'Default'   => '',
			'Extra'     => 'auto_increment'
		]);

		$columns = new Table\ColumnCollection();
		$columns->add($column);

		/**
		 * Check getResult once and save the result
		 */
		$table = new Table(null, null, $columns, new Table\IndexCollection());

		$result = $table->getResult();

		/**
		 * Check getResult again and assert saved results array is the same size as the current issues
		 */
		$this->assertSameSize($result, $table->getResult());
	}

	/**
	 * @covers ::checkColumns
	 */
	public function testCheckColumns() {
		/**
		 * Column
		 */
		$foo = new Table\Column([
			'Field'     => 'foo',
			'Type'      => 'int(10) unsigned',
			'Collation' => '',
			'Null'      => 'NO',
			'Key'       => 'MUL',
			'Default'   => '',
			'Extra'     => 'auto_increment'
		]);

		$bar = new Table\Column([
			'Field'     => 'bar',
			'Type'      => 'int(10) unsigned',
			'Collation' => '',
			'Null'      => 'NO',
			'Key'       => 'MUL',
			'Default'   => '',
			'Extra'     => 'auto_increment'
		]);

		$columns = new Table\ColumnCollection();
		$columns->add($foo);
		$columns->add($bar);

		/**
		 * Table
		 */
		$table = new Table(null, null, new Table\ColumnCollection(), new Table\IndexCollection());

		$tableResult = $table->checkColumns($columns);

		$expected = [
			[
				'type'        => 'column',
				'field'       => 'foo',
				'description' => 'Set as auto_increment but has no primary key'
			],
			[
				'type'        => 'column',
				'field'       => 'bar',
				'description' => 'Set as auto_increment but has no primary key'
			]
		];

		$this->assertSame($expected, $tableResult);
	}

	/**
	 * @covers ::checkDuplicateIndices
	 */
	public function testCheckIsDuplicate() {
		/**
		 * Indices
		 */
		$indices = new Table\IndexCollection();

		$indices->add(new Table\Index('id', null, ['id']));
		$indices->add(new Table\Index('id2', null, ['id']));

		/**
		 * Table
		 */
		$table = new Table(null, null, new Table\ColumnCollection(), new Table\IndexCollection());

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

	/**
	 * @covers ::checkDuplicateIndices
	 */
	public function testCheckIsDuplicateEmpty() {
		/**
		 * Indices
		 */
		$indices = new Table\IndexCollection();

		$indices->add(new Table\Index('id', null, ['id']));
		$indices->add(new Table\Index('active', null, ['active']));

		/**
		 * Table
		 */
		$table = new Table(null, null, new Table\ColumnCollection(), new Table\IndexCollection());

		$result = $table->checkDuplicateIndices($indices);

		$this->assertSame([], $result);
	}

	/**
	 * @covers ::checkRedundantIndicesOnPrimaryKey
	 */
	public function testCheckRedundantIndicesOnPrimaryKey() {
		/**
		 * Column
		 */
		$column = new Table\Column([
			'Field'     => 'id',
			'Type'      => 'int(10) unsigned',
			'Collation' => '',
			'Null'      => 'NO',
			'Key'       => 'PRI',
			'Default'   => '',
			'Extra'     => 'auto_increment'
		]);

		$columns = new Table\ColumnCollection();
		$columns->add($column);

		/**
		 * Indicies
		 */
		$indices = new Table\IndexCollection();

		$indices->add(new Table\Index('PRIMARY', true, ['id']));
		$indices->add(new Table\Index('unique', true, ['id']));
		$indices->add(new Table\Index('key', false, ['id']));

		/**
		 * Table
		 */
		$table = new Table(null, null, new Table\ColumnCollection(), new Table\IndexCollection());

		$result = $table->checkRedundantIndicesOnPrimaryKey($columns, $indices);

		$expected = [
			[
				'type'        => 'index',
				'key'         => 'unique',
				'description' => 'An unique index on the primary key column is redundant'
			],
			[
				'type'        => 'index',
				'key'         => 'key',
				'description' => 'An key index on the primary key column is redundant'
			]

		];

		$this->assertSame($expected, $result);
	}

	/**
	 * @covers ::checkCollationMismatchBetweenTableAndColumns
	 */
	public function testCheckCollationMismatchBetweenTableAndColumns() {
		$columns = new Table\ColumnCollection();

		$columns->add(new Table\Column([
			'Field'     => 'username',
			'Type'      => null,
			'Collation' => 'utf8_unicode_ci',
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		]));

		$columns->add(new Table\Column([
			'Field'     => 'first_name',
			'Type'      => null,
			'Collation' => 'utf8_unicode_ci',
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		]));

		$columns->add(new Table\Column([
			'Field'     => 'last_name',
			'Type'      => null,
			'Collation' => 'utf8mb4_unicode_ci',
			'Null'      => null,
			'Key'       => null,
			'Default'   => null,
			'Extra'     => null
		]));

		/**
		 * Table
		 */
		$table = new Table(null, 'utf8mb4_unicode_ci', new Table\ColumnCollection(), new Table\IndexCollection());

		$result = $table->checkCollationMismatchBetweenTableAndColumns($columns);

		$expected = [
			[
				'type'        => 'column',
				'key'         => 'username',
				'description' => 'Column is not using same collation as table'
			],
			[
				'type'        => 'column',
				'key'         => 'first_name',
				'description' => 'Column is not using same collation as table'
			]
		];

		$this->assertSame($expected, $result);
	}

	/**
	 * @covers ::__construct
	 */
	public function testConstruct() {
		$columns = new Table\ColumnCollection();

		$indices = new Table\IndexCollection();

		$table = new Table('foo', 'utf8mb4_unicode_ci', $columns, $indices);

		$reflector = new \ReflectionClass($table);

		$name = $reflector->getProperty('name');
		$name->setAccessible(true);
		$this->assertSame('foo', $name->getValue($table), 'Name property');

		$collation = $reflector->getProperty('collation');
		$collation->setAccessible(true);
		$this->assertSame('utf8mb4_unicode_ci', $collation->getValue($table), 'Collation property');
	}

}
