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
use Valkyrja\Http\Message\Param\Contract\ServerParamDataContract;
use Valkyrja\Http\Message\Param\ServerParamData;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ServerParamDataTest extends TestCase
{
    protected ServerParamData $paramData;

    protected function setUp(): void
    {
        $this->paramData = new ServerParamData(method: 'GET', port: 443, secure: true);
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(ServerParamDataContract::class, $this->paramData);
    }

    public function testConstructorWithNoParams(): void
    {
        $paramData = new ServerParamData();

        self::assertEmpty($paramData->getParams());
    }

    public function testConstructorWithStringParams(): void
    {
        self::assertSame('GET', $this->paramData->getParam('method'));
    }

    public function testConstructorWithIntParams(): void
    {
        self::assertSame(443, $this->paramData->getParam('port'));
    }

    public function testConstructorWithBoolParams(): void
    {
        self::assertTrue($this->paramData->getParam('secure'));
    }

    public function testConstructorWithFloatParams(): void
    {
        $paramData = new ServerParamData(version: 1.1);

        self::assertSame(1.1, $paramData->getParam('version'));
    }

    public function testConstructorWithNestedParamData(): void
    {
        $nested    = new ServerParamData(key: 'value');
        $paramData = new ServerParamData(nested: $nested);

        self::assertSame($nested, $paramData->getParam('nested'));
    }

    public function testGetParamReturnsNullForMissing(): void
    {
        self::assertNull($this->paramData->getParam('nonexistent'));
    }

    public function testHasParam(): void
    {
        self::assertTrue($this->paramData->hasParam('method'));
        self::assertTrue($this->paramData->hasParam('port'));
        self::assertTrue($this->paramData->hasParam('secure'));
        self::assertFalse($this->paramData->hasParam('nonexistent'));
    }

    public function testGetParams(): void
    {
        $params = $this->paramData->getParams();

        self::assertCount(3, $params);
        self::assertSame('GET', $params['method']);
        self::assertSame(443, $params['port']);
        self::assertTrue($params['secure']);
    }

    public function testOnlyParams(): void
    {
        $only = $this->paramData->onlyParams('method', 'port');

        self::assertCount(2, $only);
        self::assertSame('GET', $only['method']);
        self::assertSame(443, $only['port']);
        self::assertArrayNotHasKey('secure', $only);
    }

    public function testExceptParams(): void
    {
        $except = $this->paramData->exceptParams('secure');

        self::assertCount(2, $except);
        self::assertSame('GET', $except['method']);
        self::assertSame(443, $except['port']);
        self::assertArrayNotHasKey('secure', $except);
    }

    public function testWithParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withParams(['host' => 'localhost']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('localhost', $new->getParam('host'));
        self::assertNull($new->getParam('method'));
    }

    public function testWithParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withParams(['host' => 'localhost']);

        self::assertSame('GET', $this->paramData->getParam('method'));
    }

    public function testWithAddedParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withAddedParams(host: 'localhost');

        self::assertNotSame($this->paramData, $new);
        self::assertSame('GET', $new->getParam('method'));
        self::assertSame('localhost', $new->getParam('host'));
    }

    public function testWithAddedParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withAddedParams(host: 'localhost');

        self::assertFalse($this->paramData->hasParam('host'));
    }

    public function testWithParamsThrowsForInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->paramData->withParams(['invalid' => new stdClass()]);
    }

    public function testFromArray(): void
    {
        $paramData = $this->paramData->fromArray(['host' => 'localhost', 'port' => 8080]);

        self::assertSame('localhost', $paramData->getParam('host'));
        self::assertSame(8080, $paramData->getParam('port'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $paramData = $this->paramData->fromArray(['nested' => ['inner' => 'value']]);

        $nested = $paramData->getParam('nested');

        self::assertInstanceOf(ServerParamData::class, $nested);
        self::assertSame('value', $nested->getParam('inner'));
    }
}
