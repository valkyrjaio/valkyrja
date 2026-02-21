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

namespace Valkyrja\Http\Routing\Provider\Contract;

use Valkyrja\Http\Routing\Data\Contract\RouteContract;

interface ProviderContract
{
    /**
     * Get a list of attributed controller or action classes.
     *
     * @return class-string[]
     */
    public static function getControllerClasses(): array;

    /**
     * Get a list of routes.
     *
     * @return RouteContract[]
     */
    public static function getRoutes(): array;
}
