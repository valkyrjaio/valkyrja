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

namespace Valkyrja\Http\Routing\Url\Contract;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Data\Contract\Route;

/**
 * Interface Url.
 *
 * @author Melech Mizrachi
 */
interface Url
{
    /**
     * Get a route url by name.
     *
     * @param non-empty-string               $name     The name of the route to get
     * @param array<string, string|int>|null $data     [optional] The route data if dynamic
     * @param bool                           $absolute [optional] Whether this url should be absolute
     *
     * @return string
     */
    public function getUrl(string $name, array|null $data = null, bool|null $absolute = null): string;

    /**
     * Get a route by path.
     *
     * @param non-empty-string   $path   The path
     * @param RequestMethod|null $method [optional] The method type of get
     *
     * @return Route|null
     *                    The route if found or null when no static route is
     *                    found for the path and method combination specified
     */
    public function getRouteByPath(string $path, RequestMethod|null $method = null): Route|null;

    /**
     * Determine if a uri is internal.
     *
     * @param non-empty-string $uri The uri to check
     *
     * @return bool
     */
    public function isInternalUri(string $uri): bool;
}
