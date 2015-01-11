<?php

namespace Zortje\MySQLKeeper\Tests\Database;

use Zortje\MySQLKeeper\Database\Table;

/**
 * Class TableTest
 *
 * @package Zortje\MySQLKeeper\Tests\Database
 */
class TableTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \PDO
	 */
	protected $pdo;

	public function setUp() {
		$this->pdo = new \PDO("mysql:host=127.0.0.1;dbname=myapp_test", 'root', '');
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	public function testTableResult() {
		$this->pdo->query(file_get_contents('/home/travis/build/zortje/mysql-keeper/tests/database/files/users.sql'));

		$table = new Table('users', $this->pdo);

		$result = $table->getResult();

		$this->assertGreaterThan(0, count($result));
		$this->assertTrue(in_array('Set as auto_increment but has no primary key', $result[0]));
	}
}
