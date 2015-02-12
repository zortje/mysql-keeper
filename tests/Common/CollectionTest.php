<?php

namespace Zortje\MySQLKeeper\Tests\Common;

use Zortje\MySQLKeeper\Common\Collection;

/**
 * Class CollectionTest
 *
 * @package Zortje\MySQLKeeper\Tests\Common
 */
class CollectionTest extends \PHPUnit_Framework_TestCase {

	public function testAdd() {
		$collection = new Collection();
		$this->assertSame(0, count($collection));
		$this->assertSame(0, $collection->count());

		$collection->add('foo');
		$this->assertSame(1, count($collection));
		$this->assertSame(1, $collection->count());

		$collection->add('bar');
		$this->assertSame(2, count($collection));
		$this->assertSame(2, $collection->count());
	}

	public function testCurrent() {
		$collection = new Collection();

		$collection->add('foo');
		$collection->add('bar');

		$this->assertSame('foo', $collection->Current());

		$collection->next();
		$this->assertSame('bar', $collection->Current());

		$collection->rewind();
		$this->assertSame('foo', $collection->Current());
	}

	public function testKeyNext() {
		$indices = new Collection();
		$indices->add('foo');
		$indices->add('bar');
		$this->assertSame(0, $indices->key());

		$indices->next();
		$this->assertSame(1, $indices->key());

		$indices->rewind();
		$this->assertSame(0, $indices->key());
	}

	public function testValid() {
		$indices = new Collection();
		$indices->add('foo');
		$indices->add('bar');

		$indices->next();
		$this->assertTrue($indices->valid());

		$indices->next();
		$this->assertFalse($indices->valid());
	}
}
