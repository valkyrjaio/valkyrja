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

namespace Valkyrja\Tests\Classes\Application\Data;

use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Tests\Functional\Application\Entry\CliTest;

final readonly class CliTestCliRoutingDataClass extends CliRoutingData
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
