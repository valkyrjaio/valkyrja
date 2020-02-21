<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Support;

use PHPUnit\Framework\TestCase;
use Valkyrja\Support\Collection;

use function count;

/**
 * Test the collection support class.
 *
 * @author Melech Mizrachi
 */
class CollectionTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var Collection
     */
    protected Collection $class;

    /**
     * The value to test with.
     *
     * @var array
     */
    protected array $value = ['foo' => 'bar'];

    /**
     * The value to test with.
     *
     * @var array
     */
    protected array $valueAlt = ['bar' => 'foo'];

    /**
     * Setup the test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->class = new Collection($this->value);
    }

    /**
     * Test the constructor with no data.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(Collection::class, new Collection());
    }

    /**
     * Test the constructor with data.
     *
     * @return void
     */
    public function testConstructWithData(): void
    {
        $this->assertInstanceOf(Collection::class, new Collection(['test', 'test2']));
    }

    /**
     * Test getting a value from the collection.
     *
     * @return void
     */
    public function testGet(): void
    {
        $this->assertEquals($this->value['foo'], $this->class->get('foo', false));
    }

    /**
     * Test getting a value from the collection that doesn't exist.
     *
     * @return void
     */
    public function testGetNonExistent(): void
    {
        $this->assertEquals(null, $this->class->get('invalid'));
    }

    /**
     * Test getting a value from the collection that doesn't exist with a default value set.
     *
     * @return void
     */
    public function testGetNonExistentWithDefault(): void
    {
        $this->assertEquals('default', $this->class->get('invalid', 'default'));
    }

    /**
     * Test the has method.
     *
     * @return void
     */
    public function testHas(): void
    {
        $this->assertEquals(true, $this->class->has('foo'));
    }

    /**
     * Test the has method with a non existent key.
     *
     * @return void
     */
    public function testHasNonExistent(): void
    {
        $this->assertEquals(false, $this->class->has('invalid'));
    }

    /**
     * Test the exists method.
     *
     * @return void
     */
    public function testExists(): void
    {
        $this->assertEquals(true, $this->class->exists('bar'));
    }

    /**
     * Test the exists method with a non existent value.
     *
     * @return void
     */
    public function testExistsNonExistent(): void
    {
        $this->assertEquals(false, $this->class->exists('invalid'));
    }

    /**
     * Test the set method.
     *
     * @return void
     */
    public function testSet(): void
    {
        $this->assertInstanceOf(Collection::class, $this->class->set('bar', 'foo'));
    }

    /**
     * Test the has method with the new key set.
     *
     * @return void
     */
    public function testHasNewValue(): void
    {
        $this->class->set('bar', 'foo');

        $this->assertEquals(true, $this->class->has('bar'));
    }

    /**
     * Test the exists method with the new value set.
     *
     * @return void
     */
    public function testExistsNewValue(): void
    {
        $this->class->set('bar', 'foo');

        $this->assertEquals(true, $this->class->exists('foo'));
    }

    /**
     * Test the remove method.
     *
     * @return void
     */
    public function testRemove(): void
    {
        $this->class->set('bar', 'foo');

        $this->assertInstanceOf(Collection::class, $this->class->remove('bar'));
    }

    /**
     * Test the remove method with a non existent key.
     *
     * @return void
     */
    public function testRemoveNonExistent(): void
    {
        $this->assertInstanceOf(Collection::class, $this->class->remove('invalid'));
    }

    /**
     * Test the all method.
     *
     * @return void
     */
    public function testAll(): void
    {
        $this->assertEquals($this->value, $this->class->all());
    }

    /**
     * Test the setAll method.
     *
     * @return void
     */
    public function testSetAll(): void
    {
        $this->assertInstanceOf(Collection::class, $this->class->setAll($this->valueAlt));
    }

    /**
     * Test the keys method.
     *
     * @return void
     */
    public function testKeys(): void
    {
        $this->assertEquals(array_keys($this->value), $this->class->keys());
    }

    /**
     * Test the count method.
     *
     * @return void
     */
    public function testCount(): void
    {
        $this->assertEquals(count($this->value), $this->class->count());
    }

    /**
     * Test the isEmpty method.
     *
     * @return void
     */
    public function testIsEmpty(): void
    {
        $this->assertEquals(empty($this->value), $this->class->isEmpty());
    }

    /**
     * Test the magic __get method.
     *
     * @return void
     */
    public function testMagicGet(): void
    {
        $this->class->setAll($this->valueAlt);

        $this->assertEquals($this->valueAlt['bar'], $this->class->bar);
    }

    /**
     * Test the magic __isset method.
     *
     * @return void
     */
    public function testMagicIsset(): void
    {
        $this->class->setAll($this->valueAlt);

        $this->assertEquals(true, isset($this->class->bar));
    }

    /**
     * Test the magic __set method.
     *
     * @return void
     */
    public function testMagicSet(): void
    {
        $this->class->setAll($this->valueAlt);

        $this->assertEquals('test', $this->class->foo = 'test');
    }

    /**
     * Test the magic __unset method.
     *
     * @return void
     */
    public function testMagicUnset(): void
    {
        unset($this->class->foo);

        $this->assertEquals(false, $this->class->has('foo'));
    }

    /**
     * Test the magic __toString method.
     *
     * @return void
     */
    public function testMagicToString(): void
    {
        $this->class->setAll($this->valueAlt);

        $this->assertEquals(json_encode($this->valueAlt), (string) $this->class);
    }
}
