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

	public function testCreate() {
		$this->pdo->query(file_get_contents('tests/Database/files/users.sql'));

		$table = TableFactory::create('users', $this->pdo);

		$result = $table->getResult();

		$this->assertGreaterThan(0, count($result));
		$this->assertTrue(in_array('Set as auto_increment but has no primary key', $result[0]));
	}
}
