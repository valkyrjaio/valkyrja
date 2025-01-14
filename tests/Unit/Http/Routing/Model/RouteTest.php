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

namespace Unit\Http\Routing\Model;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Model\Route;
use Valkyrja\Tests\Classes\Http\Middleware\RequestReceivedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\SendingResponseMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\TerminatedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\ThrowableCaughtMiddlewareClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the route model.
 *
 * @author Melech Mizrachi
 */
class RouteTest extends TestCase
{
    /**
     * The route model.
     *
     * @var Route
     */
    protected Route $route;

    /**
     * The string value to test with.
     *
     * @var string
     */
    protected string $stringValue = 'test';

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->route ??= new Route();
    }

    /**
     * Test the getPath getter method.
     *
     * @return void
     */
    public function testPath(): void
    {
        $this->route->setPath($this->stringValue);

        self::assertSame($this->stringValue, $this->route->getPath());
    }

    /**
     * Test the getRequestMethods getter method default value.
     *
     * @return void
     */
    public function testGetRequestMethodsDefault(): void
    {
        self::assertSame(
            [
                RequestMethod::GET,
                RequestMethod::HEAD,
            ],
            $this->route->getMethods()
        );
    }

    /**
     * Test the setRequestMethods setter method.
     *
     * @return void
     */
    public function testSetRequestMethods(): void
    {
        $set = $this->route->setMethods($methods = [RequestMethod::POST]);

        self::assertSame($methods, $set->getMethods());
    }

    /**
     * Test the getRequestMethods getter method.
     *
     * @return void
     */
    public function testGetRequestMethods(): void
    {
        $value = [RequestMethod::POST];
        $this->route->setMethods($value);

        self::assertSame($value, $this->route->getMethods());
    }

    /**
     * Test the getRegex getter method default value.
     *
     * @return void
     */
    public function testGetRegexDefault(): void
    {
        self::assertNull($this->route->getRegex());
    }

    /**
     * Test the setRegex setter method.
     *
     * @return void
     */
    public function testSetRegex(): void
    {
        $set = $this->route->setRegex($this->stringValue);

        self::assertTrue($set instanceof Route);
    }

    /**
     * Test the setRegex setter method using null.
     *
     * @return void
     */
    public function testSetRegexNull(): void
    {
        $set = $this->route->setRegex(null);

        self::assertTrue($set instanceof Route);
    }

    /**
     * Test the getRegex getter method.
     *
     * @return void
     */
    public function testGetRegex(): void
    {
        $this->route->setRegex($this->stringValue);

        self::assertSame($this->stringValue, $this->route->getRegex());
    }

    /**
     * Test the getMiddleware getter method default value.
     *
     * @return void
     */
    public function testGetMiddlewareDefault(): void
    {
        self::assertEmpty($this->route->getMiddleware());
    }

    /**
     * Test the setMiddleware setter method.
     *
     * @return void
     */
    public function testSetMiddleware(): void
    {
        $set = $this->route->setMiddleware(
            [
                RequestReceivedMiddlewareClass::class,
                RouteDispatchedMiddlewareClass::class,
                ThrowableCaughtMiddlewareClass::class,
                SendingResponseMiddlewareClass::class,
                TerminatedMiddlewareClass::class,
            ]
        );

        self::assertTrue($set instanceof Route);
    }

    /**
     * Test the setMiddleware setter method using null.
     *
     * @return void
     */
    public function testSetMiddlewareNull(): void
    {
        $set = $this->route->setMiddleware(null);

        self::assertTrue($set instanceof Route);
    }

    /**
     * Test the getMiddleware getter method.
     *
     * @return void
     */
    public function testGetMiddleware(): void
    {
        $this->route->setMiddleware(
            $middleware = [
                RouteMatchedMiddlewareClass::class,
                RouteDispatchedMiddlewareClass::class,
                ThrowableCaughtMiddlewareClass::class,
                SendingResponseMiddlewareClass::class,
                TerminatedMiddlewareClass::class,
            ]
        );

        self::assertSame($middleware, $this->route->getMiddleware());
        self::assertSame([RouteMatchedMiddlewareClass::class], $this->route->getMatchedMiddleware());
        self::assertSame([RouteDispatchedMiddlewareClass::class], $this->route->getDispatchedMiddleware());
        self::assertSame([ThrowableCaughtMiddlewareClass::class], $this->route->getExceptionMiddleware());
        self::assertSame([SendingResponseMiddlewareClass::class], $this->route->getSendingMiddleware());
        self::assertSame([TerminatedMiddlewareClass::class], $this->route->getTerminatedMiddleware());
    }

    /**
     * Test the getDynamic getter method default value.
     *
     * @return void
     */
    public function testGetDynamicDefault(): void
    {
        self::assertFalse($this->route->isDynamic());
    }

    /**
     * Test the setDynamic setter method.
     *
     * @return void
     */
    public function testSetDynamic(): void
    {
        $set = $this->route->setDynamic(true);

        self::assertTrue($set instanceof Route);
    }

    /**
     * Test the getDynamic getter method.
     *
     * @return void
     */
    public function testGetDynamic(): void
    {
        $this->route->setDynamic(true);

        self::assertTrue($this->route->isDynamic());
    }

    /**
     * Test the getSecure getter method default value.
     *
     * @return void
     */
    public function testGetSecureDefault(): void
    {
        self::assertFalse($this->route->isSecure());
    }

    /**
     * Test the setSecure setter method.
     *
     * @return void
     */
    public function testSetSecure(): void
    {
        $set = $this->route->setSecure(true);

        self::assertTrue($set instanceof Route);
    }

    /**
     * Test the getSecure getter method.
     *
     * @return void
     */
    public function testGetSecure(): void
    {
        $this->route->setSecure(true);

        self::assertTrue($this->route->isSecure());
    }
}
