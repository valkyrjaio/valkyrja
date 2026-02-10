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
use Valkyrja\Http\Message\Param\Contract\ParamDataContract;
use Valkyrja\Tests\Classes\Http\Message\Param\Abstract\ParamDataClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ParamDataTest extends TestCase
{
    protected ParamDataClass $paramData;

    protected function setUp(): void
    {
        $this->paramData = new ParamDataClass(foo: 'bar', baz: 'qux');
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(ParamDataContract::class, $this->paramData);
    }

    public function testConstructorWithNoParams(): void
    {
        $paramData = new ParamDataClass();

        self::assertEmpty($paramData->getParams());
    }

    public function testConstructorWithStringParams(): void
    {
        $paramData = new ParamDataClass(key: 'value', another: 'test');

        self::assertSame('value', $paramData->getParam('key'));
        self::assertSame('test', $paramData->getParam('another'));
    }

    public function testConstructorWithIntParams(): void
    {
        $paramData = new ParamDataClass(count: 42, total: 100);

        self::assertSame(42, $paramData->getParam('count'));
        self::assertSame(100, $paramData->getParam('total'));
    }

    public function testConstructorWithFloatParams(): void
    {
        $paramData = new ParamDataClass(price: 9.99, tax: 0.08);

        self::assertSame(9.99, $paramData->getParam('price'));
        self::assertSame(0.08, $paramData->getParam('tax'));
    }

    public function testConstructorWithBoolParams(): void
    {
        $paramData = new ParamDataClass(active: true, deleted: false);

        self::assertTrue($paramData->getParam('active'));
        self::assertFalse($paramData->getParam('deleted'));
    }

    public function testConstructorWithMixedScalarParams(): void
    {
        $paramData = new ParamDataClass(name: 'test', count: 5, rate: 3.14, active: true);

        self::assertSame('test', $paramData->getParam('name'));
        self::assertSame(5, $paramData->getParam('count'));
        self::assertSame(3.14, $paramData->getParam('rate'));
        self::assertTrue($paramData->getParam('active'));
    }

    public function testConstructorWithNestedParamData(): void
    {
        $nested    = new ParamDataClass(inner: 'value');
        $paramData = new ParamDataClass(nested: $nested);

        self::assertSame($nested, $paramData->getParam('nested'));
    }

    public function testHasParamReturnsTrue(): void
    {
        self::assertTrue($this->paramData->hasParam('foo'));
        self::assertTrue($this->paramData->hasParam('baz'));
    }

    public function testHasParamReturnsFalse(): void
    {
        self::assertFalse($this->paramData->hasParam('nonexistent'));
    }

    public function testGetParamReturnsValue(): void
    {
        self::assertSame('bar', $this->paramData->getParam('foo'));
        self::assertSame('qux', $this->paramData->getParam('baz'));
    }

    public function testGetParamReturnsNullForMissing(): void
    {
        self::assertNull($this->paramData->getParam('nonexistent'));
    }

    public function testGetParams(): void
    {
        $params = $this->paramData->getParams();

        self::assertCount(2, $params);
        self::assertSame('bar', $params['foo']);
        self::assertSame('qux', $params['baz']);
    }

    public function testOnlyParams(): void
    {
        $paramData = new ParamDataClass(a: 'one', b: 'two', c: 'three');
        $only      = $paramData->onlyParams('a', 'c');

        self::assertCount(2, $only);
        self::assertSame('one', $only['a']);
        self::assertSame('three', $only['c']);
        self::assertArrayNotHasKey('b', $only);
    }

    public function testOnlyParamsWithNonexistentNames(): void
    {
        $only = $this->paramData->onlyParams('nonexistent');

        self::assertEmpty($only);
    }

    public function testExceptParams(): void
    {
        $paramData = new ParamDataClass(a: 'one', b: 'two', c: 'three');
        $except    = $paramData->exceptParams('b');

        self::assertCount(2, $except);
        self::assertSame('one', $except['a']);
        self::assertSame('three', $except['c']);
        self::assertArrayNotHasKey('b', $except);
    }

    public function testExceptParamsWithNonexistentNames(): void
    {
        $except = $this->paramData->exceptParams('nonexistent');

        self::assertCount(2, $except);
        self::assertSame('bar', $except['foo']);
        self::assertSame('qux', $except['baz']);
    }

    public function testWithParamsReturnsNewInstance(): void
    {
        $newParams = ['new' => 'value'];
        $new       = $this->paramData->withParams($newParams);

        self::assertNotSame($this->paramData, $new);
        self::assertSame($newParams, $new->getParams());
        self::assertSame('bar', $this->paramData->getParam('foo'));
    }

    public function testWithParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withParams(['new' => 'value']);

        self::assertSame('bar', $this->paramData->getParam('foo'));
        self::assertSame('qux', $this->paramData->getParam('baz'));
    }

    public function testWithAddedParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withAddedParams(extra: 'added');

        self::assertNotSame($this->paramData, $new);
        self::assertSame('bar', $new->getParam('foo'));
        self::assertSame('qux', $new->getParam('baz'));
        self::assertSame('added', $new->getParam('extra'));
    }

    public function testWithAddedParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withAddedParams(extra: 'added');

        self::assertFalse($this->paramData->hasParam('extra'));
    }

    public function testWithAddedParamsWithNestedParamData(): void
    {
        $nested = new ParamDataClass(inner: 'value');
        $new    = $this->paramData->withAddedParams(nested: $nested);

        self::assertSame($nested, $new->getParam('nested'));
    }

    public function testWithParamsThrowsForInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @phpstan-ignore-next-line */
        $this->paramData->withParams(['invalid' => new \stdClass()]);
    }

    public function testWithParamsThrowsForArrayParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @phpstan-ignore-next-line */
        $this->paramData->withParams(['invalid' => ['nested' => 'array']]);
    }

    public function testFromArray(): void
    {
        $data      = ['key' => 'value', 'num' => 42];
        $paramData = $this->paramData->fromArray($data);

        self::assertSame('value', $paramData->getParam('key'));
        self::assertSame(42, $paramData->getParam('num'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $data      = ['nested' => ['inner' => 'value']];
        $paramData = $this->paramData->fromArray($data);

        $nested = $paramData->getParam('nested');

        self::assertInstanceOf(ParamDataClass::class, $nested);
        self::assertSame('value', $nested->getParam('inner'));
    }

    public function testFromArrayThrowsForInvalidData(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @phpstan-ignore-next-line */
        $this->paramData->fromArray(['invalid' => new \stdClass()]);
    }

    public function testHasParamWithIntKey(): void
    {
        $paramData = new ParamDataClass('first', 'second');

        self::assertTrue($paramData->hasParam(0));
        self::assertTrue($paramData->hasParam(1));
        self::assertFalse($paramData->hasParam(2));
    }

    public function testGetParamWithIntKey(): void
    {
        $paramData = new ParamDataClass('first', 'second');

        self::assertSame('first', $paramData->getParam(0));
        self::assertSame('second', $paramData->getParam(1));
        self::assertNull($paramData->getParam(2));
    }
}