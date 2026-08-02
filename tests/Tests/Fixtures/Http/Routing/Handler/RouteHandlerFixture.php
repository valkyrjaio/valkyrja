<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Routing\Handler;

use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

/**
 * A route handler matching the signature an HTTP route declares, for tests that
 * need a handler but never dispatch it.
 */
final class RouteHandlerFixture
{
    public static function handle(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return new Response();
    }
}
