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
		$index = new Index('id', null, null);

		$this->assertSame('id', $index->getKeyName());
	}

	public function testIsPrimaryKey() {
		$index = new Index('PRIMARY', null, null);

		$this->assertTrue($index->isPrimaryKey());
	}

	public function testIsPrimaryKeyNot() {
		$index = new Index('id', null, null);

		$this->assertFalse($index->isPrimaryKey());
	}

	public function testIsUnique() {
		$index = new Index(null, true, null);

		$this->assertTrue($index->isUnique());
	}

	public function testIsUniqueNot() {
		$index = new Index(null, false, null);

		$this->assertFalse($index->isUnique());
	}

	public function testGetColumns() {
		$index = new Index(null, null, ['id']);

		$this->assertSame(['id'], $index->getColumns());
	}

	public function testIsDuplicate() {
		$indexAlpha = new Index(null, null, ['id']);
		$indexBeta  = new Index(null, null, ['id']);

		$this->assertTrue($indexAlpha->isDuplicate($indexBeta));
		$this->assertTrue($indexBeta->isDuplicate($indexAlpha));
	}

	public function testIsDuplicateNot() {
		$indexAlpha = new Index(null, null, ['id']);
		$indexBeta  = new Index(null, null, ['active']);

		$this->assertFalse($indexAlpha->isDuplicate($indexBeta));
		$this->assertFalse($indexBeta->isDuplicate($indexAlpha));
	}

	public function testIsDuplicateMultiple() {
		$indexAlpha = new Index(null, null, ['id', 'active']);
		$indexBeta  = new Index(null, null, ['id', 'active']);

		$this->assertTrue($indexAlpha->isDuplicate($indexBeta));
		$this->assertTrue($indexBeta->isDuplicate($indexAlpha));
	}

	public function testIsDuplicateDifferentOrdering() {
		$indexAlpha = new Index(null, null, ['id', 'active']);
		$indexBeta  = new Index(null, null, ['active', 'id']);

		$this->assertFalse($indexAlpha->isDuplicate($indexBeta));
		$this->assertFalse($indexBeta->isDuplicate($indexAlpha));
	}

	public function testIsDuplicateUnique() {
		$indexAlpha = new Index('PRIMARY', true, ['id']);
		$indexBeta  = new Index(null, true, ['id']);

		$this->assertFalse($indexAlpha->isDuplicate($indexBeta));
		$this->assertFalse($indexBeta->isDuplicate($indexAlpha));
	}
}
