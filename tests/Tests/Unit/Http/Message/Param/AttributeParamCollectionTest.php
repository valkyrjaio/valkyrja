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
use Valkyrja\Http\Message\Param\AttributeParamCollection;
use Valkyrja\Http\Message\Param\Contract\AttributeParamCollectionContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class AttributeParamCollectionTest extends TestCase
{
    protected AttributeParamCollection $attributes;

    protected function setUp(): void
    {
        $this->attributes = new AttributeParamCollection(['name' => 'John', 'age' => 30, 'active' => true]);
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(AttributeParamCollectionContract::class, $this->attributes);
    }

    public function testConstructorWithNoParams(): void
    {
        $paramData = new AttributeParamCollection();

        self::assertEmpty($paramData->getAll());
    }

    public function testConstructorWithStringParams(): void
    {
        self::assertSame('John', $this->attributes->get('name'));
    }

    public function testConstructorWithIntParams(): void
    {
        self::assertSame(30, $this->attributes->get('age'));
    }

    public function testConstructorWithBoolParams(): void
    {
        self::assertTrue($this->attributes->get('active'));
    }

    public function testConstructorWithFloatParams(): void
    {
        $paramData = new AttributeParamCollection(['price' => 9.99]);

        self::assertSame(9.99, $paramData->get('price'));
    }

    public function testConstructorWithNestedParamData(): void
    {
        $nested    = new AttributeParamCollection(['street' => '123 Main St', 'zip' => 12345]);
        $paramData = new AttributeParamCollection(['address' => $nested]);

        self::assertSame($nested, $paramData->get('address'));
    }

    public function testGetParamReturnsNullForMissing(): void
    {
        self::assertNull($this->attributes->get('nonexistent'));
    }

    public function testHasParam(): void
    {
        self::assertTrue($this->attributes->has('name'));
        self::assertTrue($this->attributes->has('age'));
        self::assertTrue($this->attributes->has('active'));
        self::assertFalse($this->attributes->has('nonexistent'));
    }

    public function testGetParams(): void
    {
        $params = $this->attributes->getAll();

        self::assertCount(3, $params);
        self::assertSame('John', $params['name']);
        self::assertSame(30, $params['age']);
        self::assertTrue($params['active']);
    }

    public function testOnlyParams(): void
    {
        $only = $this->attributes->getOnly('name', 'age');

        self::assertCount(2, $only);
        self::assertSame('John', $only['name']);
        self::assertSame(30, $only['age']);
        self::assertArrayNotHasKey('active', $only);
    }

    public function testExceptParams(): void
    {
        $except = $this->attributes->getAllExcept('active');

        self::assertCount(2, $except);
        self::assertSame('John', $except['name']);
        self::assertSame(30, $except['age']);
        self::assertArrayNotHasKey('active', $except);
    }

    public function testWithParamsReturnsNewInstance(): void
    {
        $new = $this->attributes->with(['key' => 'value']);

        self::assertNotSame($this->attributes, $new);
        self::assertSame('value', $new->get('key'));
        self::assertNull($new->get('name'));
    }

    public function testWithParamsDoesNotModifyOriginal(): void
    {
        $this->attributes->with(['key' => 'value']);

        self::assertSame('John', $this->attributes->get('name'));
    }

    public function testWithAddedParamsReturnsNewInstance(): void
    {
        $new = $this->attributes->withAdded(['extra' => 'added']);

        self::assertNotSame($this->attributes, $new);
        self::assertSame('John', $new->get('name'));
        self::assertSame('added', $new->get('extra'));
    }

    public function testWithAddedParamsDoesNotModifyOriginal(): void
    {
        $this->attributes->withAdded(['extra' => 'added']);

        self::assertFalse($this->attributes->has('extra'));
    }

    public function testWithParamsThrowsForInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->attributes->with(['invalid' => new stdClass()]);
    }

    public function testFromArray(): void
    {
        $paramData = AttributeParamCollection::fromArray(['key' => 'value', 'num' => 42]);

        self::assertSame('value', $paramData->get('key'));
        self::assertSame(42, $paramData->get('num'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $paramData = AttributeParamCollection::fromArray(['nested' => ['inner' => 'value']]);

        $nested = $paramData->get('nested');

        self::assertInstanceOf(AttributeParamCollection::class, $nested);
        self::assertSame('value', $nested->get('inner'));
    }
}
