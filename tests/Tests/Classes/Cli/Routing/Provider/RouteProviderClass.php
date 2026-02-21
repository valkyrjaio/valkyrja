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

namespace Valkyrja\Tests\Classes\Cli\Routing\Provider;

use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Provider\Provider;
use Valkyrja\Dispatch\Data\MethodDispatch;

final class RouteProviderClass extends Provider
{
    public static function getRoutes(): array
    {
        return [
            new Route(
                name: 'test-provider',
                description: 'test',
                dispatch: new MethodDispatch(self::class, 'dispatch')
            ),
        ];
    }
}
