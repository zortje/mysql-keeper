<?php

namespace Zortje\MySQLKeeper\Tests;

use Zortje\MySQLKeeper\Database;

/**
 * Class DatabaseTest
 *
 * @package            Zortje\MySQLKeeper\Tests
 *
 * @coversDefaultClass Zortje\MySQLKeeper\Database
 */
class DatabaseTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::getTables
	 */
	public function testGetTables() {
		$tables = new Database\TableCollection();
		$tables->add(new Database\Table('foo', null, new Database\Table\ColumnCollection(), new Database\Table\IndexCollection()));

		$database       = new Database($tables);
		$databaseTables = $database->getTables();

		$this->assertSame(1, count($databaseTables));

		foreach ($databaseTables as $i => $table) {
			switch ($i) {
				case 0:
					$this->assertSame('foo', $table->getName());
					break;

				default;
					$this->fail();
					break;
			}
		}
	}
}
