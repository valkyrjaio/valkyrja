<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Param\Abstract;

use InvalidArgumentException;
use stdClass;
use Valkyrja\Http\Message\Param\Contract\ParamCollectionContract;
use Valkyrja\Tests\Classes\Http\Message\Param\Abstract\ParamCollectionClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ParamCollectionTest extends TestCase
{
    protected ParamCollectionClass $paramData;

    protected function setUp(): void
    {
        $this->paramData = new ParamCollectionClass(['foo' => 'bar', 'baz' => 'qux']);
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(ParamCollectionContract::class, $this->paramData);
    }

    public function testConstructorWithNoParams(): void
    {
        $paramData = new ParamCollectionClass();

        self::assertEmpty($paramData->getAll());
    }

    public function testConstructorWithStringParams(): void
    {
        $paramData = new ParamCollectionClass(['key' => 'value', 'another' => 'test']);

        self::assertSame('value', $paramData->get('key'));
        self::assertSame('test', $paramData->get('another'));
    }

    public function testConstructorWithIntParams(): void
    {
        $paramData = new ParamCollectionClass(['count' => 42, 'total' => 100]);

        self::assertSame(42, $paramData->get('count'));
        self::assertSame(100, $paramData->get('total'));
    }

    public function testConstructorWithFloatParams(): void
    {
        $paramData = new ParamCollectionClass(['price' => 9.99, 'tax' => 0.08]);

        self::assertSame(9.99, $paramData->get('price'));
        self::assertSame(0.08, $paramData->get('tax'));
    }

    public function testConstructorWithBoolParams(): void
    {
        $paramData = new ParamCollectionClass(['active' => true, 'deleted' => false]);

        self::assertTrue($paramData->get('active'));
        self::assertFalse($paramData->get('deleted'));
    }

    public function testConstructorWithMixedScalarParams(): void
    {
        $paramData = new ParamCollectionClass(['name' => 'test', 'count' => 5, 'rate' => 3.14, 'active' => true]);

        self::assertSame('test', $paramData->get('name'));
        self::assertSame(5, $paramData->get('count'));
        self::assertSame(3.14, $paramData->get('rate'));
        self::assertTrue($paramData->get('active'));
    }

    public function testConstructorWithNestedParamData(): void
    {
        $nested    = new ParamCollectionClass(['inner' => 'value']);
        $paramData = new ParamCollectionClass(['nested' => $nested]);

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
        $paramData = new ParamCollectionClass(['a' => 'one', 'b' => 'two', 'c' => 'three']);
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
        $paramData = new ParamCollectionClass(['a' => 'one', 'b' => 'two', 'c' => 'three']);
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
        $nested = new ParamCollectionClass(['inner' => 'value']);
        $new    = $this->paramData->withAdded(['nested' => $nested]);

        self::assertSame($nested, $new->get('nested'));
    }

    public function testWithParamsThrowsForInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->paramData->with(['invalid' => new stdClass()]);
    }

    public function testWithParamsThrowsForArrayParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->paramData->with(['invalid' => ['nested' => 'array']]);
    }

    public function testFromArray(): void
    {
        $data      = ['key' => 'value', 'num' => 42];
        $paramData = ParamCollectionClass::fromArray($data);

        self::assertSame('value', $paramData->get('key'));
        self::assertSame(42, $paramData->get('num'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $data      = ['nested' => ['inner' => 'value']];
        $paramData = ParamCollectionClass::fromArray($data);

        $nested = $paramData->get('nested');

        self::assertInstanceOf(ParamCollectionClass::class, $nested);
        self::assertSame('value', $nested->get('inner'));
    }

    public function testFromArrayThrowsForInvalidData(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ParamCollectionClass::fromArray(['invalid' => new stdClass()]);
    }

    public function testHasParamWithIntKey(): void
    {
        $paramData = new ParamCollectionClass(['first', 'second']);

        self::assertTrue($paramData->has(0));
        self::assertTrue($paramData->has(1));
        self::assertFalse($paramData->has(2));
    }

    public function testGetParamWithIntKey(): void
    {
        $paramData = new ParamCollectionClass([1 => 'first', 2 => 'second']);

        self::assertNull($paramData->get(0));
        self::assertSame('first', $paramData->get(1));
        self::assertSame('second', $paramData->get(2));
        self::assertNull($paramData->get(3));
    }
}
