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

namespace Valkyrja\Tests\Unit\Http\Routing\Collector;

use ReflectionException;
use Valkyrja\Http\Routing\Collector\AttributeCollector;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Tests\Classes\Http\Middleware\RequestReceivedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\SendingResponseMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\TerminatedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\ThrowableCaughtMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Routing\Controller\ControllerAttributedClass;
use Valkyrja\Tests\Classes\Http\Routing\Controller\ControllerClass;
use Valkyrja\Tests\Classes\Http\Routing\Controller\InvalidControllerClass;
use Valkyrja\Tests\Classes\Http\Struct\IndexedJsonRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\ResponseStructEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the AttributeCollector service.
 */
class AttributeCollectorTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testGetRoutes(): void
    {
        $routes = new AttributeCollector()->getRoutes(ControllerClass::class);

        self::assertCount(2, $routes);

        $welcomeRoute = $routes[0];

        self::assertSame(ControllerClass::WELCOME_PATH, $welcomeRoute->getPath());
        self::assertSame(ControllerClass::WELCOME_NAME, $welcomeRoute->getName());

        $parametersRoute = $routes[1];

        self::assertSame(ControllerClass::PARAMETERS_PATH, $parametersRoute->getPath());
        self::assertSame(ControllerClass::PARAMETERS_NAME, $parametersRoute->getName());
        self::assertSame('/^\/parameters\/(?<name>[a-zA-Z]+)$/', $parametersRoute->getRegex());
        self::assertSame([RouteDispatchedMiddlewareClass::class], $parametersRoute->getRouteDispatchedMiddleware());
        self::assertSame([RouteMatchedMiddlewareClass::class], $parametersRoute->getRouteMatchedMiddleware());
        self::assertSame([SendingResponseMiddlewareClass::class], $parametersRoute->getSendingResponseMiddleware());
        self::assertSame([TerminatedMiddlewareClass::class], $parametersRoute->getTerminatedMiddleware());
        self::assertSame([ThrowableCaughtMiddlewareClass::class], $parametersRoute->getThrowableCaughtMiddleware());
        self::assertSame(IndexedJsonRequestStructEnum::class, $parametersRoute->getRequestStruct());
        self::assertSame(ResponseStructEnum::class, $parametersRoute->getResponseStruct());
        self::assertCount(1, $parametersRoute->getParameters());
    }

    /**
     * @throws ReflectionException
     */
    public function testGetRoutesWithControllerAttributes(): void
    {
        $routes = new AttributeCollector()->getRoutes(ControllerAttributedClass::class);

        self::assertCount(1, $routes);

        $welcomeRoute = $routes[0];

        self::assertSame('/controller/welcome/path', $welcomeRoute->getPath());
        self::assertSame('controller.' . ControllerAttributedClass::WELCOME_NAME . '.name', $welcomeRoute->getName());
    }

    /**
     * @throws ReflectionException
     */
    public function testInvalidMiddleware(): void
    {
        $middlewareClass = RequestReceivedMiddlewareClass::class;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unsupported middleware class `$middlewareClass`");

        $collector = new AttributeCollector();

        $collector->getRoutes(InvalidControllerClass::class);
    }
}
