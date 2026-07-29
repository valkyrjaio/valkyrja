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
