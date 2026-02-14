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
use Valkyrja\Http\Message\Param\Contract\CookieParamCollectionContract;
use Valkyrja\Http\Message\Param\CookieParamCollection;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CookieParamCollectionTest extends TestCase
{
    protected CookieParamCollection $paramData;

    protected function setUp(): void
    {
        $this->paramData = new CookieParamCollection(['session' => 'abc123', 'theme' => 'dark']);
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(CookieParamCollectionContract::class, $this->paramData);
    }

    public function testConstructorWithNoParams(): void
    {
        $paramData = new CookieParamCollection();

        self::assertEmpty($paramData->getParams());
    }

    public function testConstructorWithStringParams(): void
    {
        self::assertSame('abc123', $this->paramData->getParam('session'));
        self::assertSame('dark', $this->paramData->getParam('theme'));
    }

    public function testGetParamReturnsString(): void
    {
        $result = $this->paramData->getParam('session');

        self::assertIsString($result);
        self::assertSame('abc123', $result);
    }

    public function testGetParamReturnsNullForMissing(): void
    {
        self::assertNull($this->paramData->getParam('nonexistent'));
    }

    public function testHasParam(): void
    {
        self::assertTrue($this->paramData->hasParam('session'));
        self::assertTrue($this->paramData->hasParam('theme'));
        self::assertFalse($this->paramData->hasParam('nonexistent'));
    }

    public function testGetParams(): void
    {
        $params = $this->paramData->getParams();

        self::assertCount(2, $params);
        self::assertSame('abc123', $params['session']);
        self::assertSame('dark', $params['theme']);
    }

    public function testOnlyParams(): void
    {
        $only = $this->paramData->getOnlyParams('session');

        self::assertCount(1, $only);
        self::assertSame('abc123', $only['session']);
        self::assertArrayNotHasKey('theme', $only);
    }

    public function testExceptParams(): void
    {
        $except = $this->paramData->getAllExcept('session');

        self::assertCount(1, $except);
        self::assertSame('dark', $except['theme']);
        self::assertArrayNotHasKey('session', $except);
    }

    public function testWithParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withParams(['lang' => 'en']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('en', $new->getParam('lang'));
        self::assertNull($new->getParam('session'));
    }

    public function testWithParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withParams(['lang' => 'en']);

        self::assertSame('abc123', $this->paramData->getParam('session'));
    }

    public function testWithAddedParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withAddedParams(['lang' => 'en']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('abc123', $new->getParam('session'));
        self::assertSame('dark', $new->getParam('theme'));
        self::assertSame('en', $new->getParam('lang'));
    }

    public function testWithAddedParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withAddedParams(['lang' => 'en']);

        self::assertFalse($this->paramData->hasParam('lang'));
    }

    public function testWithParamsThrowsForInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->paramData->withParams(['invalid' => new stdClass()]);
    }

    public function testFromArray(): void
    {
        $paramData = CookieParamCollection::fromArray(['token' => 'xyz', 'user' => 'john']);

        self::assertSame('xyz', $paramData->getParam('token'));
        self::assertSame('john', $paramData->getParam('user'));
    }
}
