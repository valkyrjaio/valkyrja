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
use Valkyrja\Http\Message\Param\Contract\ParsedBodyParamCollectionContract;
use Valkyrja\Http\Message\Param\ParsedBodyParamCollection;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ParsedBodyParamCollectionTest extends TestCase
{
    protected ParsedBodyParamCollection $paramData;

    protected function setUp(): void
    {
        $this->paramData = new ParsedBodyParamCollection(name: 'John', email: 'john@example.com');
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(ParsedBodyParamCollectionContract::class, $this->paramData);
    }

    public function testConstructorWithNoParams(): void
    {
        $paramData = new ParsedBodyParamCollection();

        self::assertEmpty($paramData->getParams());
    }

    public function testConstructorWithStringParams(): void
    {
        self::assertSame('John', $this->paramData->getParam('name'));
        self::assertSame('john@example.com', $this->paramData->getParam('email'));
    }

    public function testConstructorWithNestedParamData(): void
    {
        $nested    = new ParsedBodyParamCollection(street: '123 Main St', city: 'Springfield');
        $paramData = new ParsedBodyParamCollection(address: $nested);

        $params = $paramData->getParams();

        self::assertInstanceOf(ParsedBodyParamCollection::class, $params['address']);
    }

    public function testGetParamReturnsString(): void
    {
        $result = $this->paramData->getParam('name');

        self::assertIsString($result);
        self::assertSame('John', $result);
    }

    public function testGetParamReturnsNullForMissing(): void
    {
        self::assertNull($this->paramData->getParam('nonexistent'));
    }

    public function testHasParam(): void
    {
        self::assertTrue($this->paramData->hasParam('name'));
        self::assertTrue($this->paramData->hasParam('email'));
        self::assertFalse($this->paramData->hasParam('nonexistent'));
    }

    public function testGetParams(): void
    {
        $params = $this->paramData->getParams();

        self::assertCount(2, $params);
        self::assertSame('John', $params['name']);
        self::assertSame('john@example.com', $params['email']);
    }

    public function testOnlyParams(): void
    {
        $only = $this->paramData->onlyParams('name');

        self::assertCount(1, $only);
        self::assertSame('John', $only['name']);
        self::assertArrayNotHasKey('email', $only);
    }

    public function testExceptParams(): void
    {
        $except = $this->paramData->exceptParams('name');

        self::assertCount(1, $except);
        self::assertSame('john@example.com', $except['email']);
        self::assertArrayNotHasKey('name', $except);
    }

    public function testWithParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withParams(['username' => 'johnd']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('johnd', $new->getParam('username'));
        self::assertNull($new->getParam('name'));
    }

    public function testWithParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withParams(['username' => 'johnd']);

        self::assertSame('John', $this->paramData->getParam('name'));
    }

    public function testWithAddedParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withAddedParams(age: '30');

        self::assertNotSame($this->paramData, $new);
        self::assertSame('John', $new->getParam('name'));
        self::assertSame('john@example.com', $new->getParam('email'));
        self::assertSame('30', $new->getParam('age'));
    }

    public function testWithAddedParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withAddedParams(age: '30');

        self::assertFalse($this->paramData->hasParam('age'));
    }

    public function testWithParamsThrowsForInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->paramData->withParams(['invalid' => new stdClass()]);
    }

    public function testFromArray(): void
    {
        $paramData = $this->paramData->fromArray(['field' => 'value']);

        self::assertSame('value', $paramData->getParam('field'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $paramData = $this->paramData->fromArray(['address' => ['street' => '123 Main St']]);

        $params = $paramData->getParams();

        self::assertInstanceOf(ParsedBodyParamCollection::class, $params['address']);
    }
}
