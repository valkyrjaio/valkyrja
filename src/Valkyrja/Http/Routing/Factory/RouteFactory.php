<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Routing\Factory;

use Valkyrja\Http\Routing\Data\Contract\DynamicRouteContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\DynamicRoute;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Struct\Request\Contract\RequestStructContract;
use Valkyrja\Http\Struct\Response\Contract\ResponseStructContract;

use function str_contains;

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
                responseSentMiddleware: $route->getResponseSentMiddleware(),
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
            responseSentMiddleware: $route->getResponseSentMiddleware(),
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
