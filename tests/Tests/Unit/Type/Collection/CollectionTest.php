<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Collection;

use Override;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Collection\Collection;

use function array_keys;
use function count;
use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * Test the collection support class.
 */
final class CollectionTest extends TestCase
{
    /**
     * The class  to test with.
     *
     * @var Collection
     */
    protected Collection $class;

    /**
     * The value to test with.
     *
     * @var array<string, string>
     */
    protected array $value = ['foo' => 'bar'];

    /**
     * The value to test with.
     *
     * @var array<string, string>
     */
    protected array $valueAlt = ['bar' => 'foo'];

    /**
     * Setup the test.
     */
    #[Override]
    protected function setUp(): void
    {
        $this->class = new Collection($this->value);
    }

    /**
     * Test the constructor with no data.
     */
    public function testConstruct(): void
    {
        self::assertInstanceOf(Collection::class, new Collection());
    }

    /**
     * Test the constructor with data.
     */
    public function testConstructWithData(): void
    {
        self::assertInstanceOf(Collection::class, new Collection(['test', 'test2']));
    }

    /**
     * Test getting a value from the collection.
     */
    public function testGet(): void
    {
        self::assertSame($this->value['foo'], $this->class->get('foo', false));
    }

    /**
     * Test getting a value from the collection that doesn't exist.
     */
    public function testGetNonExistent(): void
    {
        self::assertNull($this->class->get('invalid'));
    }

    /**
     * Test getting a value from the collection that doesn't exist with a default value set.
     */
    public function testGetNonExistentWithDefault(): void
    {
        self::assertSame('default', $this->class->get('invalid', 'default'));
    }

    /**
     * Test the has method.
     */
    public function testHas(): void
    {
        self::assertTrue($this->class->has('foo'));
    }

    /**
     * Test the has method with a non existent key.
     */
    public function testHasNonExistent(): void
    {
        self::assertFalse($this->class->has('invalid'));
    }

    /**
     * Test the exists method.
     */
    public function testExists(): void
    {
        self::assertTrue($this->class->exists('bar'));
    }

    /**
     * Test the exists method with a non existent value.
     */
    public function testExistsNonExistent(): void
    {
        self::assertFalse($this->class->exists('invalid'));
    }

    /**
     * Test the set method.
     */
    public function testSet(): void
    {
        self::assertInstanceOf(Collection::class, $this->class->set('bar', 'foo'));
    }

    /**
     * Test the has method with the new key set.
     */
    public function testHasNewValue(): void
    {
        $this->class->set('bar', 'foo');

        self::assertTrue($this->class->has('bar'));
    }

    /**
     * Test the exists method with the new value set.
     */
    public function testExistsNewValue(): void
    {
        $this->class->set('bar', 'foo');

        self::assertTrue($this->class->exists('foo'));
    }

    /**
     * Test the remove method.
     */
    public function testRemove(): void
    {
        $this->class->set('bar', 'foo');

        self::assertInstanceOf(Collection::class, $this->class->remove('bar'));
    }

    /**
     * Test the remove method with a non existent key.
     */
    public function testRemoveNonExistent(): void
    {
        self::assertInstanceOf(Collection::class, $this->class->remove('invalid'));
    }

    /**
     * Test the all method.
     */
    public function testAll(): void
    {
        self::assertSame($this->value, $this->class->all());
    }

    /**
     * Test the setAll method.
     */
    public function testSetAll(): void
    {
        self::assertInstanceOf(Collection::class, $this->class->setAll($this->valueAlt));
    }

    /**
     * Test the keys method.
     */
    public function testKeys(): void
    {
        self::assertSame(array_keys($this->value), $this->class->keys());
    }

    /**
     * Test the count method.
     */
    public function testCount(): void
    {
        self::assertSame(count($this->value), $this->class->count());
    }

    /**
     * Test the isEmpty method.
     */
    public function testIsEmpty(): void
    {
        self::assertSame(empty($this->value), $this->class->isEmpty());
    }

    /**
     * Test the magic __get method.
     */
    public function testMagicGet(): void
    {
        $this->class->setAll($this->valueAlt);

        self::assertSame($this->valueAlt['bar'], $this->class->get('bar'));
    }

    /**
     * Test the magic __isset method.
     */
    public function testMagicIsset(): void
    {
        $this->class->setAll($this->valueAlt);

        self::assertTrue(isset($this->class->bar));
    }

    /**
     * Test the magic __set method.
     */
    public function testMagicSet(): void
    {
        $this->class->setAll($this->valueAlt);

        /**
         * @psalm-suppress UndefinedThisPropertyAssignment
         *
         * A collection is a dynamic bag: its magic accessors take any name, so there
         * is no property for Psalm to find. The call is the code under test.
         */
        $this->class->__set('foo', 'test');

        self::assertSame('test', $this->class->get('foo'));
    }

    /**
     * Test the magic __unset method.
     */
    public function testMagicUnset(): void
    {
        /**
         * @psalm-suppress UndefinedMagicPropertyFetch
         *
         * A collection is a dynamic bag: its magic accessors take any name, so there
         * is no property for Psalm to find. The call is the code under test.
         */
        unset($this->class->foo);

        self::assertFalse($this->class->has('foo'));
    }

    /**
     * Test the magic __toString method.
     */
    public function testMagicToString(): void
    {
        $this->class->setAll($this->valueAlt);

        self::assertSame(json_encode($this->valueAlt, JSON_THROW_ON_ERROR), (string) $this->class);
    }
}
