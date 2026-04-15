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

namespace Valkyrja\Cli\Routing\Factory;

use Valkyrja\Http\Routing\Data\Contract\DynamicRouteContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\DynamicRoute;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Struct\Request\Contract\RequestStructContract;
use Valkyrja\Http\Struct\Response\Contract\ResponseStructContract;

class RouteFactory
{
    public static function fromRoute(RouteContract $route): RouteContract
    {
        if (str_contains($route->getPath(), '{')) {
            $parameters = [];

            if ($route instanceof DynamicRouteContract) {
                $parameters = $route->getParameters();
            }

            return new DynamicRoute(
                path: $route->getPath(),
                name: $route->getName(),
                regex: '',
                parameters: $parameters,
                handler: $route->getHandler(),
                requestMethods: $route->getRequestMethods(),
                routeMatchedMiddleware: $route->getRouteMatchedMiddleware(),
                routeDispatchedMiddleware: $route->getRouteDispatchedMiddleware(),
                throwableCaughtMiddleware: $route->getThrowableCaughtMiddleware(),
                sendingResponseMiddleware: $route->getSendingResponseMiddleware(),
                terminatedMiddleware: $route->getTerminatedMiddleware(),
                requestStruct: static::getRequestStructFromRoute($route),
                responseStruct: static::getResponseStructFromRoute($route),
            );
        }

        return new Route(
            path: $route->getPath(),
            name: $route->getName(),
            handler: $route->getHandler(),
            requestMethods: $route->getRequestMethods(),
            routeMatchedMiddleware: $route->getRouteMatchedMiddleware(),
            routeDispatchedMiddleware: $route->getRouteDispatchedMiddleware(),
            throwableCaughtMiddleware: $route->getThrowableCaughtMiddleware(),
            sendingResponseMiddleware: $route->getSendingResponseMiddleware(),
            terminatedMiddleware: $route->getTerminatedMiddleware(),
            requestStruct: static::getRequestStructFromRoute($route),
            responseStruct: static::getResponseStructFromRoute($route),
        );
    }

    public static function getRequestStructFromRoute(RouteContract $route): RequestStructContract|null
    {
        return $route->hasRequestStruct()
            ? $route->getRequestStruct()
            : null;
    }

    public static function getResponseStructFromRoute(RouteContract $route): ResponseStructContract|null
    {
        return $route->hasResponseStruct()
            ? $route->getResponseStruct()
            : null;
    }
}
