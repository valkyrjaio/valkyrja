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

namespace Valkyrja\Routing;

/**
 * Interface Matcher.
 *
 * @author Melech Mizrachi
 */
interface Matcher
{
    /**
     * Match a route by path.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return Route|null
     *                    The route if found or null when no route is
     *                    found for the path and method combination specified
     */
    public function match(string $path, string|null $method = null): Route|null;

    /**
     * Match a static route by path.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return Route|null
     *                    The route if found or null when no static route is
     *                    found for the path and method combination specified
     */
    public function matchStatic(string $path, string|null $method = null): Route|null;

    /**
     * Match a dynamic route by path.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return Route|null
     *                    The route if found or null when no dynamic route is
     *                    found for the path and method combination specified
     */
    public function matchDynamic(string $path, string|null $method = null): Route|null;
}
