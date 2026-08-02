<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Application\Data;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\HttpRoutingData;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Functional\Application\Entry\HttpTest;

final readonly class HttpTestHttpRoutingDataFixture extends HttpRoutingData
{
    public function __construct()
    {
        parent::__construct(
            routes: [
                'version' => static fn (): RouteContract => new Route(
                    path: '/version',
                    name: 'version',
                    handler: [HttpTest::class, 'routeHandler'],
                ),
            ],
            paths: [
                RequestMethod::HEAD->value => ['/version' => 'version'],
                RequestMethod::GET->value  => ['/version' => 'version'],
            ],
        );
    }
}
