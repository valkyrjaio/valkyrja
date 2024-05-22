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

namespace Valkyrja\Tests\Unit\Routing;

use Exception;
use InvalidArgumentException;
use Valkyrja\Http\Constant\RequestMethod;
use Valkyrja\Routing\Models\Route;
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
     * Test the getPath getter method.
     *
     * @return void
     */
    public function testPath(): void
    {
        $this->getRoute()->setPath($this->stringValue);

        self::assertSame($this->stringValue, $this->getRoute()->getPath());
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
            $this->getRoute()->getMethods()
        );
    }

    /**
     * Test the setRequestMethods setter method.
     *
     * @return void
     */
    public function testSetRequestMethods(): void
    {
        $set = $this->getRoute()->setMethods([RequestMethod::POST]);

        self::assertTrue($set instanceof Route);
    }

    /**
     * Test the setRequestMethods setter method with invalid data.
     *
     * @return void
     */
    public function testSetRequestMethodsInvalid(): void
    {
        try {
            $this->getRoute()->setMethods(['invalid value']);
        } catch (Exception $exception) {
            self::assertSame(InvalidArgumentException::class, $exception::class);
        }
    }

    /**
     * Test the getRequestMethods getter method.
     *
     * @return void
     */
    public function testGetRequestMethods(): void
    {
        $value = [RequestMethod::POST];
        $this->getRoute()->setMethods($value);

        self::assertSame($value, $this->getRoute()->getMethods());
    }

    /**
     * Test the getRegex getter method default value.
     *
     * @return void
     */
    public function testGetRegexDefault(): void
    {
        self::assertNull($this->getRoute()->getRegex());
    }

    /**
     * Test the setRegex setter method.
     *
     * @return void
     */
    public function testSetRegex(): void
    {
        $set = $this->getRoute()->setRegex($this->stringValue);

        self::assertTrue($set instanceof Route);
    }

    /**
     * Test the setRegex setter method using null.
     *
     * @return void
     */
    public function testSetRegexNull(): void
    {
        $set = $this->getRoute()->setRegex(null);

        self::assertTrue($set instanceof Route);
    }

    /**
     * Test the getRegex getter method.
     *
     * @return void
     */
    public function testGetRegex(): void
    {
        $this->getRoute()->setRegex($this->stringValue);

        self::assertSame($this->stringValue, $this->getRoute()->getRegex());
    }

    /**
     * Test the getMiddleware getter method default value.
     *
     * @return void
     */
    public function testGetMiddlewareDefault(): void
    {
        self::assertNull($this->getRoute()->getMiddleware());
    }

    /**
     * Test the setMiddleware setter method.
     *
     * @return void
     */
    public function testSetMiddleware(): void
    {
        $set = $this->getRoute()->setMiddleware([$this->stringValue]);

        self::assertTrue($set instanceof Route);
    }

    /**
     * Test the setMiddleware setter method using null.
     *
     * @return void
     */
    public function testSetMiddlewareNull(): void
    {
        $set = $this->getRoute()->setMiddleware(null);

        self::assertTrue($set instanceof Route);
    }

    /**
     * Test the getMiddleware getter method.
     *
     * @return void
     */
    public function testGetMiddleware(): void
    {
        $this->getRoute()->setMiddleware([$this->stringValue]);

        self::assertSame([$this->stringValue], $this->getRoute()->getMiddleware());
    }

    /**
     * Test the getDynamic getter method default value.
     *
     * @return void
     */
    public function testGetDynamicDefault(): void
    {
        self::assertFalse($this->getRoute()->isDynamic());
    }

    /**
     * Test the setDynamic setter method.
     *
     * @return void
     */
    public function testSetDynamic(): void
    {
        $set = $this->getRoute()->setDynamic(true);

        self::assertTrue($set instanceof Route);
    }

    /**
     * Test the getDynamic getter method.
     *
     * @return void
     */
    public function testGetDynamic(): void
    {
        $this->getRoute()->setDynamic(true);

        self::assertTrue($this->getRoute()->isDynamic());
    }

    /**
     * Test the getSecure getter method default value.
     *
     * @return void
     */
    public function testGetSecureDefault(): void
    {
        self::assertFalse($this->getRoute()->isSecure());
    }

    /**
     * Test the setSecure setter method.
     *
     * @return void
     */
    public function testSetSecure(): void
    {
        $set = $this->getRoute()->setSecure(true);

        self::assertTrue($set instanceof Route);
    }

    /**
     * Test the getSecure getter method.
     *
     * @return void
     */
    public function testGetSecure(): void
    {
        $this->getRoute()->setSecure(true);

        self::assertTrue($this->getRoute()->isSecure());
    }

    /**
     * Get the route model to test with.
     *
     * @return Route
     */
    protected function getRoute(): Route
    {
        return $this->route ?? $this->route = new Route();
    }
}
