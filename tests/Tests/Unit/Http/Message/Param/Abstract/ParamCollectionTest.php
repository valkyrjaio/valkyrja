<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Param\Abstract;

use InvalidArgumentException;
use Override;
use stdClass;
use Valkyrja\Http\Message\Param\Contract\ParamCollectionContract;
use Valkyrja\Tests\Fixtures\Http\Message\Param\Abstract\ParamCollectionFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ParamCollectionTest extends TestCase
{
    protected ParamCollectionFixture $paramData;

    #[Override]
    protected function setUp(): void
    {
        $this->paramData = new ParamCollectionFixture(['foo' => 'bar', 'baz' => 'qux']);
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(ParamCollectionContract::class, $this->paramData);
    }

    public function testConstructorWithNoParams(): void
    {
        $paramData = new ParamCollectionFixture();

        self::assertEmpty($paramData->getAll());
    }

    public function testConstructorWithStringParams(): void
    {
        $paramData = new ParamCollectionFixture(['key' => 'value', 'another' => 'test']);

        self::assertSame('value', $paramData->get('key'));
        self::assertSame('test', $paramData->get('another'));
    }

    public function testConstructorWithIntParams(): void
    {
        $paramData = new ParamCollectionFixture(['count' => 42, 'total' => 100]);

        self::assertSame(42, $paramData->get('count'));
        self::assertSame(100, $paramData->get('total'));
    }

    public function testConstructorWithFloatParams(): void
    {
        $paramData = new ParamCollectionFixture(['price' => 9.99, 'tax' => 0.08]);

        self::assertSame(9.99, $paramData->get('price'));
        self::assertSame(0.08, $paramData->get('tax'));
    }

    public function testConstructorWithBoolParams(): void
    {
        $paramData = new ParamCollectionFixture(['active' => true, 'deleted' => false]);

        self::assertTrue($paramData->get('active'));
        self::assertFalse($paramData->get('deleted'));
    }

    public function testConstructorWithMixedScalarParams(): void
    {
        $paramData = new ParamCollectionFixture(['name' => 'test', 'count' => 5, 'rate' => 3.14, 'active' => true]);

        self::assertSame('test', $paramData->get('name'));
        self::assertSame(5, $paramData->get('count'));
        self::assertSame(3.14, $paramData->get('rate'));
        self::assertTrue($paramData->get('active'));
    }

    public function testConstructorWithNestedParamData(): void
    {
        $nested    = new ParamCollectionFixture(['inner' => 'value']);
        $paramData = new ParamCollectionFixture(['nested' => $nested]);

        self::assertSame($nested, $paramData->get('nested'));
    }

    public function testHasParamReturnsTrue(): void
    {
        self::assertTrue($this->paramData->has('foo'));
        self::assertTrue($this->paramData->has('baz'));
    }

    public function testHasParamReturnsFalse(): void
    {
        self::assertFalse($this->paramData->has('nonexistent'));
    }

    public function testGetParamReturnsValue(): void
    {
        self::assertSame('bar', $this->paramData->get('foo'));
        self::assertSame('qux', $this->paramData->get('baz'));
    }

    public function testGetParamReturnsNullForMissing(): void
    {
        self::assertNull($this->paramData->get('nonexistent'));
    }

    public function testGetParams(): void
    {
        $params = $this->paramData->getAll();

        self::assertCount(2, $params);
        self::assertSame('bar', $params['foo']);
        self::assertSame('qux', $params['baz']);
    }

    public function testOnlyParams(): void
    {
        $paramData = new ParamCollectionFixture(['a' => 'one', 'b' => 'two', 'c' => 'three']);
        $only      = $paramData->getOnly('a', 'c');

        self::assertCount(2, $only);
        self::assertSame('one', $only['a']);
        self::assertSame('three', $only['c']);
        self::assertArrayNotHasKey('b', $only);
    }

    public function testOnlyParamsWithNonexistentNames(): void
    {
        $only = $this->paramData->getOnly('nonexistent');

        self::assertEmpty($only);
    }

    public function testExceptParams(): void
    {
        $paramData = new ParamCollectionFixture(['a' => 'one', 'b' => 'two', 'c' => 'three']);
        $except    = $paramData->getAllExcept('b');

        self::assertCount(2, $except);
        self::assertSame('one', $except['a']);
        self::assertSame('three', $except['c']);
        self::assertArrayNotHasKey('b', $except);
    }

    public function testExceptParamsWithNonexistentNames(): void
    {
        $except = $this->paramData->getAllExcept('nonexistent');

        self::assertCount(2, $except);
        self::assertSame('bar', $except['foo']);
        self::assertSame('qux', $except['baz']);
    }

    public function testWithParamsReturnsNewInstance(): void
    {
        $newParams = ['new' => 'value'];
        $new       = $this->paramData->with($newParams);

        self::assertNotSame($this->paramData, $new);
        self::assertSame($newParams, $new->getAll());
        self::assertSame('bar', $this->paramData->get('foo'));
    }

    public function testWithParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->with(['new' => 'value']);

        self::assertSame('bar', $this->paramData->get('foo'));
        self::assertSame('qux', $this->paramData->get('baz'));
    }

    public function testWithAddedParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withAdded(['extra' => 'added']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('bar', $new->get('foo'));
        self::assertSame('qux', $new->get('baz'));
        self::assertSame('added', $new->get('extra'));
    }

    public function testWithAddedParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withAdded(['extra' => 'added']);

        self::assertFalse($this->paramData->has('extra'));
    }

    public function testWithAddedParamsWithNestedParamData(): void
    {
        $nested = new ParamCollectionFixture(['inner' => 'value']);
        $new    = $this->paramData->withAdded(['nested' => $nested]);

        self::assertSame($nested, $new->get('nested'));
    }

    public function testWithParamsThrowsForInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /**
         * @psalm-suppress InvalidArgument The test gives invalid input on purpose to reach the guard.
         *
         * @phpstan-ignore argument.type (The test gives invalid input on purpose to reach the guard.)
         */
        $this->paramData->with(['invalid' => new stdClass()]);
    }

    public function testWithParamsThrowsForArrayParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /**
         * @psalm-suppress InvalidArgument The test gives invalid input on purpose to reach the guard.
         *
         * @phpstan-ignore argument.type (The test gives invalid input on purpose to reach the guard.)
         */
        $this->paramData->with(['invalid' => ['nested' => 'array']]);
    }

    public function testFromArray(): void
    {
        $data      = ['key' => 'value', 'num' => 42];
        $paramData = ParamCollectionFixture::fromArray($data);

        self::assertSame('value', $paramData->get('key'));
        self::assertSame(42, $paramData->get('num'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $data      = ['nested' => ['inner' => 'value']];
        $paramData = ParamCollectionFixture::fromArray($data);

        $nested = $paramData->get('nested');

        self::assertInstanceOf(ParamCollectionFixture::class, $nested);
        self::assertSame('value', $nested->get('inner'));
    }

    public function testFromArrayThrowsForInvalidData(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ParamCollectionFixture::fromArray(['invalid' => new stdClass()]);
    }

    public function testHasParamWithIntKey(): void
    {
        $paramData = new ParamCollectionFixture(['first', 'second']);

        self::assertTrue($paramData->has(0));
        self::assertTrue($paramData->has(1));
        self::assertFalse($paramData->has(2));
    }

    public function testGetParamWithIntKey(): void
    {
        $paramData = new ParamCollectionFixture([1 => 'first', 2 => 'second']);

        self::assertNull($paramData->get(0));
        self::assertSame('first', $paramData->get(1));
        self::assertSame('second', $paramData->get(2));
        self::assertNull($paramData->get(3));
    }
}
