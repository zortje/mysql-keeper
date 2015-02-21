<?php

namespace Zortje\MySQLKeeper\Tests\Database;

use Zortje\MySQLKeeper\Database\TableFactory;

/**
 * Class TableFactoryTest
 *
 * @package Zortje\MySQLKeeper\Tests\Database
 */
class TableFactoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \PDO
	 */
	protected $pdo;

	public function setUp() {
		$this->pdo = new \PDO("mysql:host=127.0.0.1;dbname=myapp_test", 'root', '');
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Table table_not_found was not found
	 */
	public function testCreateTableNotFound() {
		TableFactory::create('table_not_found', $this->pdo);
	}

	public function testCreateUsers() {
		$this->pdo->query(file_get_contents('tests/Database/files/users.sql'));

		$table = TableFactory::create('users', $this->pdo);

		$result = $table->getResult();

		$this->assertSame('users', $table->getName());
		$this->assertSame('utf8_unicode_ci', $table->getCollation());

		$expected = [
			'issues' => [
				[
					'type'        => 'column',
					'field'       => 'id',
					'description' => 'Set as auto_increment but has no primary key'
				],
				[
					'type'        => 'index',
					'key'         => 'id_active2',
					'description' => 'Is duplicate of id_active'
				],
				[
					'type'        => 'column',
					'key'         => 'username',
					'description' => 'Column is not using same collation as table'
				]
			]
		];

		$this->assertSame($expected, $result);
	}

	public function testCreateNodes() {
		$this->pdo->query(file_get_contents('tests/Database/files/nodes.sql'));

		$table = TableFactory::create('nodes', $this->pdo);

		$result = $table->getResult();

		$this->assertSame('nodes', $table->getName());
		$this->assertSame('utf8_unicode_ci', $table->getCollation());

		$expected = [
			'issues' => [
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
			]
		];

		$this->assertSame($expected, $result);
	}
}
