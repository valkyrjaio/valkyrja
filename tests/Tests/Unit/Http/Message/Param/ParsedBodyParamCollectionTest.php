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
        $this->paramData = new ParsedBodyParamCollection(['name' => 'John', 'email' => 'john@example.com']);
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(ParsedBodyParamCollectionContract::class, $this->paramData);
    }

    public function testConstructorWithNoParams(): void
    {
        $paramData = new ParsedBodyParamCollection();

        self::assertEmpty($paramData->getAll());
    }

    public function testConstructorWithStringParams(): void
    {
        self::assertSame('John', $this->paramData->get('name'));
        self::assertSame('john@example.com', $this->paramData->get('email'));
    }

    public function testConstructorWithNestedParamData(): void
    {
        $nested    = new ParsedBodyParamCollection(['street' => '123 Main St', 'city' => 'Springfield']);
        $paramData = new ParsedBodyParamCollection(['address' => $nested]);

        $params = $paramData->getAll();

        self::assertInstanceOf(ParsedBodyParamCollection::class, $params['address']);
    }

    public function testGetParamReturnsString(): void
    {
        $result = $this->paramData->get('name');

        self::assertIsString($result);
        self::assertSame('John', $result);
    }

    public function testGetParamReturnsNullForMissing(): void
    {
        self::assertNull($this->paramData->get('nonexistent'));
    }

    public function testHasParam(): void
    {
        self::assertTrue($this->paramData->has('name'));
        self::assertTrue($this->paramData->has('email'));
        self::assertFalse($this->paramData->has('nonexistent'));
    }

    public function testGetParams(): void
    {
        $params = $this->paramData->getAll();

        self::assertCount(2, $params);
        self::assertSame('John', $params['name']);
        self::assertSame('john@example.com', $params['email']);
    }

    public function testOnlyParams(): void
    {
        $only = $this->paramData->getOnly('name');

        self::assertCount(1, $only);
        self::assertSame('John', $only['name']);
        self::assertArrayNotHasKey('email', $only);
    }

    public function testExceptParams(): void
    {
        $except = $this->paramData->getAllExcept('name');

        self::assertCount(1, $except);
        self::assertSame('john@example.com', $except['email']);
        self::assertArrayNotHasKey('name', $except);
    }

    public function testWithParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->with(['username' => 'johnd']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('johnd', $new->get('username'));
        self::assertNull($new->get('name'));
    }

    public function testWithParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->with(['username' => 'johnd']);

        self::assertSame('John', $this->paramData->get('name'));
    }

    public function testWithAddedParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withAdded(['age' => '30']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('John', $new->get('name'));
        self::assertSame('john@example.com', $new->get('email'));
        self::assertSame('30', $new->get('age'));
    }

    public function testWithAddedParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withAdded(['age' => '30']);

        self::assertFalse($this->paramData->has('age'));
    }

    public function testWithParamsThrowsForInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->paramData->with(['invalid' => new stdClass()]);
    }

    public function testFromArray(): void
    {
        $paramData = ParsedBodyParamCollection::fromArray(['field' => 'value']);

        self::assertSame('value', $paramData->get('field'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $paramData = ParsedBodyParamCollection::fromArray(['address' => ['street' => '123 Main St']]);

        $params = $paramData->getAll();

        self::assertInstanceOf(ParsedBodyParamCollection::class, $params['address']);
    }
}
