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

namespace Valkyrja\Tests\Unit\Http\Message\Param;

use InvalidArgumentException;
use stdClass;
use Valkyrja\Http\Message\Param\Contract\ParsedJsonParamCollectionContract;
use Valkyrja\Http\Message\Param\ParsedJsonParamCollection;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ParsedJsonParamCollectionTest extends TestCase
{
    protected ParsedJsonParamCollection $paramData;

    protected function setUp(): void
    {
        $this->paramData = new ParsedJsonParamCollection(['name' => 'John', 'age' => 30, 'active' => true]);
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(ParsedJsonParamCollectionContract::class, $this->paramData);
    }

    public function testConstructorWithNoParams(): void
    {
        $paramData = new ParsedJsonParamCollection();

        self::assertEmpty($paramData->getParams());
    }

    public function testConstructorWithStringParams(): void
    {
        self::assertSame('John', $this->paramData->getParam('name'));
    }

    public function testConstructorWithIntParams(): void
    {
        self::assertSame(30, $this->paramData->getParam('age'));
    }

    public function testConstructorWithBoolParams(): void
    {
        self::assertTrue($this->paramData->getParam('active'));
    }

    public function testConstructorWithFloatParams(): void
    {
        $paramData = new ParsedJsonParamCollection(['price' => 9.99]);

        self::assertSame(9.99, $paramData->getParam('price'));
    }

    public function testConstructorWithNestedParamData(): void
    {
        $nested    = new ParsedJsonParamCollection(['street' => '123 Main St', 'zip' => 12345]);
        $paramData = new ParsedJsonParamCollection(['address' => $nested]);

        self::assertSame($nested, $paramData->getParam('address'));
    }

    public function testGetParamReturnsNullForMissing(): void
    {
        self::assertNull($this->paramData->getParam('nonexistent'));
    }

    public function testHasParam(): void
    {
        self::assertTrue($this->paramData->hasParam('name'));
        self::assertTrue($this->paramData->hasParam('age'));
        self::assertTrue($this->paramData->hasParam('active'));
        self::assertFalse($this->paramData->hasParam('nonexistent'));
    }

    public function testGetParams(): void
    {
        $params = $this->paramData->getParams();

        self::assertCount(3, $params);
        self::assertSame('John', $params['name']);
        self::assertSame(30, $params['age']);
        self::assertTrue($params['active']);
    }

    public function testOnlyParams(): void
    {
        $only = $this->paramData->onlyParams('name', 'age');

        self::assertCount(2, $only);
        self::assertSame('John', $only['name']);
        self::assertSame(30, $only['age']);
        self::assertArrayNotHasKey('active', $only);
    }

    public function testExceptParams(): void
    {
        $except = $this->paramData->exceptParams('active');

        self::assertCount(2, $except);
        self::assertSame('John', $except['name']);
        self::assertSame(30, $except['age']);
        self::assertArrayNotHasKey('active', $except);
    }

    public function testWithParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withParams(['key' => 'value']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('value', $new->getParam('key'));
        self::assertNull($new->getParam('name'));
    }

    public function testWithParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withParams(['key' => 'value']);

        self::assertSame('John', $this->paramData->getParam('name'));
    }

    public function testWithAddedParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withAddedParams(['extra' => 'added']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('John', $new->getParam('name'));
        self::assertSame('added', $new->getParam('extra'));
    }

    public function testWithAddedParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withAddedParams(['extra' => 'added']);

        self::assertFalse($this->paramData->hasParam('extra'));
    }

    public function testWithParamsThrowsForInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->paramData->withParams(['invalid' => new stdClass()]);
    }

    public function testFromArray(): void
    {
        $paramData = ParsedJsonParamCollection::fromArray(['key' => 'value', 'num' => 42]);

        self::assertSame('value', $paramData->getParam('key'));
        self::assertSame(42, $paramData->getParam('num'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $paramData = ParsedJsonParamCollection::fromArray(['nested' => ['inner' => 'value']]);

        $nested = $paramData->getParam('nested');

        self::assertInstanceOf(ParsedJsonParamCollection::class, $nested);
        self::assertSame('value', $nested->getParam('inner'));
    }
}
