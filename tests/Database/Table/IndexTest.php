<?php

namespace Zortje\MySQLKeeper\Tests\Database\Table;

use Zortje\MySQLKeeper\Database\Table\Index;

/**
 * Class IndexTest
 *
 * @package Zortje\MySQLKeeper\Tests\Database\Table
 */
class IndexTest extends \PHPUnit_Framework_TestCase {

	public function testGetKeyName() {
		$index = new Index('id', null);

		$this->assertSame('id', $index->getKeyName());
	}

	public function testGetColumns() {
		$index = new Index(null, ['id']);

		$this->assertSame(['id'], $index->getColumns());
	}

	public function testIsDuplicate() {
		$indexAlpha = new Index(null, ['id']);
		$indexBeta  = new Index(null, ['id']);

		$this->assertTrue($indexAlpha->isDuplicate($indexBeta));
		$this->assertTrue($indexBeta->isDuplicate($indexAlpha));
	}

	public function testIsDuplicateNot() {
		$indexAlpha = new Index(null, ['id']);
		$indexBeta  = new Index(null, ['active']);

		$this->assertFalse($indexAlpha->isDuplicate($indexBeta));
		$this->assertFalse($indexBeta->isDuplicate($indexAlpha));
	}

	public function testIsDuplicateMultiple() {
		$indexAlpha = new Index(null, ['id', 'active']);
		$indexBeta  = new Index(null, ['id', 'active']);

		$this->assertTrue($indexAlpha->isDuplicate($indexBeta));
		$this->assertTrue($indexBeta->isDuplicate($indexAlpha));
	}

	public function testIsDuplicateDifferentOrdering() {
		$indexAlpha = new Index(null, ['id', 'active']);
		$indexBeta  = new Index(null, ['active', 'id']);

		$this->assertFalse($indexAlpha->isDuplicate($indexBeta));
		$this->assertFalse($indexBeta->isDuplicate($indexAlpha));
	}
}
