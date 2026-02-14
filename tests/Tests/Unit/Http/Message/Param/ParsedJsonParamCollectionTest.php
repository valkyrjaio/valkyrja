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

        self::assertEmpty($paramData->getAll());
    }

    public function testConstructorWithStringParams(): void
    {
        self::assertSame('John', $this->paramData->get('name'));
    }

    public function testConstructorWithIntParams(): void
    {
        self::assertSame(30, $this->paramData->get('age'));
    }

    public function testConstructorWithBoolParams(): void
    {
        self::assertTrue($this->paramData->get('active'));
    }

    public function testConstructorWithFloatParams(): void
    {
        $paramData = new ParsedJsonParamCollection(['price' => 9.99]);

        self::assertSame(9.99, $paramData->get('price'));
    }

    public function testConstructorWithNestedParamData(): void
    {
        $nested    = new ParsedJsonParamCollection(['street' => '123 Main St', 'zip' => 12345]);
        $paramData = new ParsedJsonParamCollection(['address' => $nested]);

        self::assertSame($nested, $paramData->get('address'));
    }

    public function testGetParamReturnsNullForMissing(): void
    {
        self::assertNull($this->paramData->get('nonexistent'));
    }

    public function testHasParam(): void
    {
        self::assertTrue($this->paramData->has('name'));
        self::assertTrue($this->paramData->has('age'));
        self::assertTrue($this->paramData->has('active'));
        self::assertFalse($this->paramData->has('nonexistent'));
    }

    public function testGetParams(): void
    {
        $params = $this->paramData->getAll();

        self::assertCount(3, $params);
        self::assertSame('John', $params['name']);
        self::assertSame(30, $params['age']);
        self::assertTrue($params['active']);
    }

    public function testOnlyParams(): void
    {
        $only = $this->paramData->getOnly('name', 'age');

        self::assertCount(2, $only);
        self::assertSame('John', $only['name']);
        self::assertSame(30, $only['age']);
        self::assertArrayNotHasKey('active', $only);
    }

    public function testExceptParams(): void
    {
        $except = $this->paramData->getAllExcept('active');

        self::assertCount(2, $except);
        self::assertSame('John', $except['name']);
        self::assertSame(30, $except['age']);
        self::assertArrayNotHasKey('active', $except);
    }

    public function testWithParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->with(['key' => 'value']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('value', $new->get('key'));
        self::assertNull($new->get('name'));
    }

    public function testWithParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->with(['key' => 'value']);

        self::assertSame('John', $this->paramData->get('name'));
    }

    public function testWithAddedParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withAdded(['extra' => 'added']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('John', $new->get('name'));
        self::assertSame('added', $new->get('extra'));
    }

    public function testWithAddedParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withAdded(['extra' => 'added']);

        self::assertFalse($this->paramData->has('extra'));
    }

    public function testWithParamsThrowsForInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->paramData->with(['invalid' => new stdClass()]);
    }

    public function testFromArray(): void
    {
        $paramData = ParsedJsonParamCollection::fromArray(['key' => 'value', 'num' => 42]);

        self::assertSame('value', $paramData->get('key'));
        self::assertSame(42, $paramData->get('num'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $paramData = ParsedJsonParamCollection::fromArray(['nested' => ['inner' => 'value']]);

        $nested = $paramData->get('nested');

        self::assertInstanceOf(ParsedJsonParamCollection::class, $nested);
        self::assertSame('value', $nested->get('inner'));
    }
}
