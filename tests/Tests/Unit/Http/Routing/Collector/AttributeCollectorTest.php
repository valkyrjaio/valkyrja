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
use Valkyrja\Http\Routing\Data\Contract\DynamicRouteContract;
use Valkyrja\Tests\Classes\Http\Middleware\AllMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\SendingResponseMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\TerminatedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\ThrowableCaughtMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Routing\Controller\ControllerAttributedClass;
use Valkyrja\Tests\Classes\Http\Routing\Controller\ControllerClass;
use Valkyrja\Tests\Classes\Http\Routing\Controller\ControllerWithAllMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Struct\IndexedJsonRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\ResponseStructEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the AttributeCollector service.
 */
final class AttributeCollectorTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testGetRoutes(): void
    {
        $routes = new AttributeCollector()->getRoutes(ControllerClass::class);

        self::assertCount(3, $routes);

        $welcomeRoute = $routes[0];

        self::assertSame(ControllerClass::WELCOME_PATH, $welcomeRoute->getPath());
        self::assertSame(ControllerClass::WELCOME_NAME, $welcomeRoute->getName());
        self::assertSame(ControllerClass::class, $welcomeRoute->getDispatch()->getClass());
        self::assertSame('welcome', $welcomeRoute->getDispatch()->getMethod());

        $parametersRoute = $routes[1];

        self::assertInstanceOf(DynamicRouteContract::class, $parametersRoute);
        self::assertSame(ControllerClass::PARAMETERS_PATH, $parametersRoute->getPath());
        self::assertSame(ControllerClass::PARAMETERS_NAME, $parametersRoute->getName());
        self::assertSame(ControllerClass::class, $parametersRoute->getDispatch()->getClass());
        self::assertSame('parameters', $parametersRoute->getDispatch()->getMethod());
        self::assertSame('/^\/parameters\/(?<name>[a-zA-Z]+)$/', $parametersRoute->getRegex());
        self::assertSame([RouteDispatchedMiddlewareClass::class], $parametersRoute->getRouteDispatchedMiddleware());
        self::assertSame([RouteMatchedMiddlewareClass::class], $parametersRoute->getRouteMatchedMiddleware());
        self::assertSame([SendingResponseMiddlewareClass::class], $parametersRoute->getSendingResponseMiddleware());
        self::assertSame([TerminatedMiddlewareClass::class], $parametersRoute->getTerminatedMiddleware());
        self::assertSame([ThrowableCaughtMiddlewareClass::class], $parametersRoute->getThrowableCaughtMiddleware());
        self::assertSame(IndexedJsonRequestStructEnum::first, $parametersRoute->getRequestStruct());
        self::assertSame(ResponseStructEnum::first, $parametersRoute->getResponseStruct());
        self::assertCount(1, $parametersRoute->getParameters());
        self::assertTrue($parametersRoute->getParameters()[0]->hasCast());
        self::assertSame(ControllerClass::PARAMETERS_PARAMETER_NAME, $parametersRoute->getParameters()[0]->getName());

        $dynamicRoute = $routes[2];

        self::assertInstanceOf(DynamicRouteContract::class, $dynamicRoute);
        self::assertSame(ControllerClass::DYNAMIC_PATH, $dynamicRoute->getPath());
        self::assertSame(ControllerClass::DYNAMIC_NAME, $dynamicRoute->getName());
        self::assertSame(ControllerClass::class, $dynamicRoute->getDispatch()->getClass());
        self::assertSame('dynamic', $dynamicRoute->getDispatch()->getMethod());
        self::assertSame('/^\/dynamic\/(?<foo>[a-zA-Z]+)\/(?<bar>[a-zA-Z]+)$/', $dynamicRoute->getRegex());
        self::assertSame([RouteDispatchedMiddlewareClass::class], $dynamicRoute->getRouteDispatchedMiddleware());
        self::assertSame([RouteMatchedMiddlewareClass::class], $dynamicRoute->getRouteMatchedMiddleware());
        self::assertSame([SendingResponseMiddlewareClass::class], $dynamicRoute->getSendingResponseMiddleware());
        self::assertSame([TerminatedMiddlewareClass::class], $dynamicRoute->getTerminatedMiddleware());
        self::assertSame([ThrowableCaughtMiddlewareClass::class], $dynamicRoute->getThrowableCaughtMiddleware());
        self::assertSame(IndexedJsonRequestStructEnum::first, $dynamicRoute->getRequestStruct());
        self::assertSame(ResponseStructEnum::first, $dynamicRoute->getResponseStruct());
        self::assertCount(2, $dynamicRoute->getParameters());
        self::assertTrue($dynamicRoute->getParameters()[0]->hasCast());
        self::assertTrue($dynamicRoute->getParameters()[1]->hasCast());
        self::assertSame(ControllerClass::DYNAMIC_PARAMETER_NAME, $dynamicRoute->getParameters()[0]->getName());
        self::assertSame(ControllerClass::DYNAMIC_PARAMETER_NAME2, $dynamicRoute->getParameters()[1]->getName());
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
        self::assertSame(ControllerAttributedClass::class, $welcomeRoute->getDispatch()->getClass());
        self::assertSame('welcome', $welcomeRoute->getDispatch()->getMethod());
    }

    /**
     * @throws ReflectionException
     */
    public function testGetRoutesWithSingleMiddlewareThatHasAllTypes(): void
    {
        $routes = new AttributeCollector()->getRoutes(ControllerWithAllMiddlewareClass::class);

        self::assertCount(2, $routes);

        $route = $routes[0];

        self::assertSame(ControllerWithAllMiddlewareClass::WELCOME_PATH, $route->getPath());
        self::assertSame(ControllerWithAllMiddlewareClass::WELCOME_NAME, $route->getName());
        self::assertSame(ControllerWithAllMiddlewareClass::class, $route->getDispatch()->getClass());
        self::assertSame('welcome', $route->getDispatch()->getMethod());
        self::assertSame([AllMiddlewareClass::class], $route->getRouteDispatchedMiddleware());
        self::assertSame([AllMiddlewareClass::class], $route->getRouteMatchedMiddleware());
        self::assertSame([AllMiddlewareClass::class], $route->getSendingResponseMiddleware());
        self::assertSame([AllMiddlewareClass::class], $route->getTerminatedMiddleware());
        self::assertSame([AllMiddlewareClass::class], $route->getThrowableCaughtMiddleware());
        self::assertSame(IndexedJsonRequestStructEnum::first, $route->getRequestStruct());
        self::assertSame(ResponseStructEnum::first, $route->getResponseStruct());

        $dynamicRoute = $routes[1];

        self::assertSame(ControllerWithAllMiddlewareClass::DYNAMIC_PATH, $dynamicRoute->getPath());
        self::assertSame(ControllerWithAllMiddlewareClass::DYNAMIC_NAME, $dynamicRoute->getName());
        self::assertSame(ControllerWithAllMiddlewareClass::class, $dynamicRoute->getDispatch()->getClass());
        self::assertSame('welcomeDynamic', $dynamicRoute->getDispatch()->getMethod());
        self::assertSame([AllMiddlewareClass::class], $dynamicRoute->getRouteDispatchedMiddleware());
        self::assertSame([AllMiddlewareClass::class], $dynamicRoute->getRouteMatchedMiddleware());
        self::assertSame([AllMiddlewareClass::class], $dynamicRoute->getSendingResponseMiddleware());
        self::assertSame([AllMiddlewareClass::class], $dynamicRoute->getTerminatedMiddleware());
        self::assertSame([AllMiddlewareClass::class], $dynamicRoute->getThrowableCaughtMiddleware());
        self::assertSame(IndexedJsonRequestStructEnum::first, $dynamicRoute->getRequestStruct());
        self::assertSame(ResponseStructEnum::first, $dynamicRoute->getResponseStruct());
    }
}
