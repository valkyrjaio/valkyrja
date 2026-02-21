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

namespace Valkyrja\Tests\Classes\Http\Routing\Provider;

use Override;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Provider\Provider;

final class RouteProviderClass extends Provider
{
    #[Override]
    public static function getRoutes(): array
    {
        return [
            new Route(
                path: '/from-provider',
                name: 'route-from-provider',
                dispatch: new MethodDispatch(self::class, 'dispatch'),
            ),
        ];
    }
}
