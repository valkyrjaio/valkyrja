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

namespace Valkyrja\Tests\Unit\Application\Data;

use Valkyrja\Application\Data\Data;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Data service.
 */
class DataTest extends TestCase
{
    public function testDefault(): void
    {
        $data = new Data();

        self::assertEmpty($data->container->aliases);
        self::assertEmpty($data->container->deferred);
        self::assertEmpty($data->container->deferredCallback);
        self::assertEmpty($data->container->services);
        self::assertEmpty($data->container->singletons);
        self::assertEmpty($data->container->providers);

        self::assertEmpty($data->cli->commands);

        self::assertEmpty($data->event->events);
        self::assertEmpty($data->event->listeners);

        self::assertEmpty($data->http->routes);
        self::assertEmpty($data->http->static);
        self::assertEmpty($data->http->dynamic);
    }
}
