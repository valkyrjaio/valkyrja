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

use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Tests\Functional\Application\Entry\CliTest;

final readonly class CliTestCliRoutingDataFixture extends CliRoutingData
{
    public function __construct()
    {
        parent::__construct(
            routes: [
                'version' => static fn (): RouteContract => new Route(
                    name: 'version',
                    description: 'test',
                    handler: [CliTest::class, 'routeHandler'],
                ),
            ]
        );
    }
}
