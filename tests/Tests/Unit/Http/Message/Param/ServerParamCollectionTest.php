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
use Valkyrja\Http\Message\Param\Contract\ServerParamCollectionContract;
use Valkyrja\Http\Message\Param\ServerParamCollection;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ServerParamCollectionTest extends TestCase
{
    protected ServerParamCollection $paramData;

    protected function setUp(): void
    {
        $this->paramData = new ServerParamCollection(['method' => 'GET', 'port' => 443, 'secure' => true]);
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(ServerParamCollectionContract::class, $this->paramData);
    }

    public function testConstructorWithNoParams(): void
    {
        $paramData = new ServerParamCollection();

        self::assertEmpty($paramData->getAll());
    }

    public function testConstructorWithStringParams(): void
    {
        self::assertSame('GET', $this->paramData->get('method'));
    }

    public function testConstructorWithIntParams(): void
    {
        self::assertSame(443, $this->paramData->get('port'));
    }

    public function testConstructorWithBoolParams(): void
    {
        self::assertTrue($this->paramData->get('secure'));
    }

    public function testConstructorWithFloatParams(): void
    {
        $paramData = new ServerParamCollection(['version' => 1.1]);

        self::assertSame(1.1, $paramData->get('version'));
    }

    public function testConstructorWithNestedParamData(): void
    {
        $nested    = new ServerParamCollection(['key' => 'value']);
        $paramData = new ServerParamCollection(['nested' => $nested]);

        self::assertSame($nested, $paramData->get('nested'));
    }

    public function testGetParamReturnsNullForMissing(): void
    {
        self::assertNull($this->paramData->get('nonexistent'));
    }

    public function testHasParam(): void
    {
        self::assertTrue($this->paramData->has('method'));
        self::assertTrue($this->paramData->has('port'));
        self::assertTrue($this->paramData->has('secure'));
        self::assertFalse($this->paramData->has('nonexistent'));
    }

    public function testGetParams(): void
    {
        $params = $this->paramData->getAll();

        self::assertCount(3, $params);
        self::assertSame('GET', $params['method']);
        self::assertSame(443, $params['port']);
        self::assertTrue($params['secure']);
    }

    public function testOnlyParams(): void
    {
        $only = $this->paramData->getOnly('method', 'port');

        self::assertCount(2, $only);
        self::assertSame('GET', $only['method']);
        self::assertSame(443, $only['port']);
        self::assertArrayNotHasKey('secure', $only);
    }

    public function testExceptParams(): void
    {
        $except = $this->paramData->getAllExcept('secure');

        self::assertCount(2, $except);
        self::assertSame('GET', $except['method']);
        self::assertSame(443, $except['port']);
        self::assertArrayNotHasKey('secure', $except);
    }

    public function testWithParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->with(['host' => 'localhost']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('localhost', $new->get('host'));
        self::assertNull($new->get('method'));
    }

    public function testWithParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->with(['host' => 'localhost']);

        self::assertSame('GET', $this->paramData->get('method'));
    }

    public function testWithAddedParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withAdded(['host' => 'localhost']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('GET', $new->get('method'));
        self::assertSame('localhost', $new->get('host'));
    }

    public function testWithAddedParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withAdded(['host' => 'localhost']);

        self::assertFalse($this->paramData->has('host'));
    }

    public function testWithParamsThrowsForInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->paramData->with(['invalid' => new stdClass()]);
    }

    public function testFromArray(): void
    {
        $paramData = ServerParamCollection::fromArray(['host' => 'localhost', 'port' => 8080]);

        self::assertSame('localhost', $paramData->get('host'));
        self::assertSame(8080, $paramData->get('port'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $paramData = ServerParamCollection::fromArray(['nested' => ['inner' => 'value']]);

        $nested = $paramData->get('nested');

        self::assertInstanceOf(ServerParamCollection::class, $nested);
        self::assertSame('value', $nested->get('inner'));
    }
}
