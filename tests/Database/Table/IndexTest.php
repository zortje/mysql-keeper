<?php

namespace Zortje\MySQLKeeper\Tests\Database\Table;

use Zortje\MySQLKeeper\Database\Table\Index;

/**
 * Class IndexTest
 *
 * @package Zortje\MySQLKeeper\Tests\Database\Table
 *
 * @coversDefaultClass Zortje\MySQLKeeper\Database\Table\Index
 */
class IndexTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::getKeyName
	 */
	public function testGetKeyName() {
		$index = new Index('id', null, null);

		$this->assertSame('id', $index->getKeyName());
	}

	/**
	 * @covers ::isPrimaryKey
	 */
	public function testIsPrimaryKey() {
		$index = new Index('PRIMARY', null, null);

		$this->assertTrue($index->isPrimaryKey());
	}

	/**
	 * @covers ::isPrimaryKey
	 */
	public function testIsPrimaryKeyNot() {
		$index = new Index('id', null, null);

		$this->assertFalse($index->isPrimaryKey());
	}

	/**
	 * @covers ::isUnique
	 */
	public function testIsUnique() {
		$index = new Index(null, true, null);

		$this->assertTrue($index->isUnique());
	}

	/**
	 * @covers ::isUnique
	 */
	public function testIsUniqueNot() {
		$index = new Index(null, false, null);

		$this->assertFalse($index->isUnique());
	}

	/**
	 * @covers ::getColumns
	 */
	public function testGetColumns() {
		$index = new Index(null, null, ['id']);

		$this->assertSame(['id'], $index->getColumns());
	}

	/**
	 * @covers ::isDuplicate
	 */
	public function testIsDuplicate() {
		$indexAlpha = new Index(null, null, ['id']);
		$indexBeta  = new Index(null, null, ['id']);

		$this->assertTrue($indexAlpha->isDuplicate($indexBeta));
		$this->assertTrue($indexBeta->isDuplicate($indexAlpha));
	}

	/**
	 * @covers ::isDuplicate
	 */
	public function testIsDuplicateNot() {
		$indexAlpha = new Index(null, null, ['id']);
		$indexBeta  = new Index(null, null, ['active']);

		$this->assertFalse($indexAlpha->isDuplicate($indexBeta));
		$this->assertFalse($indexBeta->isDuplicate($indexAlpha));
	}

	/**
	 * @covers ::isDuplicate
	 */
	public function testIsDuplicateMultiple() {
		$indexAlpha = new Index(null, null, ['id', 'active']);
		$indexBeta  = new Index(null, null, ['id', 'active']);

		$this->assertTrue($indexAlpha->isDuplicate($indexBeta));
		$this->assertTrue($indexBeta->isDuplicate($indexAlpha));
	}

	/**
	 * @covers ::isDuplicate
	 */
	public function testIsDuplicateDifferentOrdering() {
		$indexAlpha = new Index(null, null, ['id', 'active']);
		$indexBeta  = new Index(null, null, ['active', 'id']);

		$this->assertFalse($indexAlpha->isDuplicate($indexBeta));
		$this->assertFalse($indexBeta->isDuplicate($indexAlpha));
	}

	/**
	 * @covers ::isDuplicate
	 */
	public function testIsDuplicateUnique() {
		$indexAlpha = new Index('PRIMARY', true, ['id']);
		$indexBeta  = new Index(null, true, ['id']);

		$this->assertFalse($indexAlpha->isDuplicate($indexBeta));
		$this->assertFalse($indexBeta->isDuplicate($indexAlpha));
	}

	/**
	 * @covers ::isColumnsEqual
	 */
	public function testIsColumnsEqual() {
		$index = new Index(null, null, ['id']);

		$this->assertTrue($index->isColumnsEqual(['id']));
	}

	/**
	 * @covers ::isColumnsEqual
	 */
	public function testIsColumnsEqualNot() {
		$index = new Index(null, null, ['id']);

		$this->assertFalse($index->isColumnsEqual(['active']));
	}

	/**
	 * @covers ::isColumnsEqual
	 */
	public function testIsColumnsEqualMultiple() {
		$index = new Index(null, null, ['id', 'active']);

		$this->assertTrue($index->isColumnsEqual(['id', 'active']));
	}

	/**
	 * @covers ::isColumnsEqual
	 */
	public function testIsColumnsEqualDifferentOrdering() {
		$index = new Index(null, null, ['id', 'active']);

		$this->assertFalse($index->isColumnsEqual(['active', 'id']));
	}
}
