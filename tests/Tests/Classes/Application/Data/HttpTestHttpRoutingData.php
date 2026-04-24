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

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\HttpRoutingData;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Functional\Application\Entry\HttpTest;

readonly class HttpTestHttpRoutingData extends HttpRoutingData
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
