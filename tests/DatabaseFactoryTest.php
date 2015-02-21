<?php

namespace Zortje\MySQLKeeper\Tests;

use Zortje\MySQLKeeper\DatabaseFactory;

/**
 * Class DatabaseFactoryTest
 *
 * @package Zortje\MySQLKeeper\Tests
 */
class DatabaseFactoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \PDO
	 */
	protected $pdo;

	public function setUp() {
		$this->pdo = new \PDO("mysql:host=127.0.0.1;dbname=myapp_test", 'root', '');
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	public function testCreateTableNames() {
		$this->pdo->query(file_get_contents('tests/Database/files/nodes.sql'));
		$this->pdo->query(file_get_contents('tests/Database/files/users.sql'));

		$database       = DatabaseFactory::create($this->pdo);
		$databaseTables = $database->getTables();

		$this->assertSame(2, count($databaseTables));

		foreach ($databaseTables as $i => $table) {
			switch ($i) {
				case 0:
					$this->assertSame('nodes', $table->getName());
					break;

				case 1:
					$this->assertSame('users', $table->getName());
					break;

				default;
					$this->fail();
					break;
			}
		}
	}

	public function testDatabaseResults() {
		$this->pdo->query(file_get_contents('tests/Database/files/nodes.sql'));
		$this->pdo->query(file_get_contents('tests/Database/files/users.sql'));

		$database = DatabaseFactory::create($this->pdo);

		$result = $database->getResult();

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
			],
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
			],
		];

		$this->assertSame($expected, $result);
	}
}
