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

namespace Valkyrja\Http\Routing\Matcher\Contract;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

/**
 * Interface MatcherContract.
 */
interface MatcherContract
{
    /**
     * Match a route by path.
     *
     * @param non-empty-string   $path          The path
     * @param RequestMethod|null $requestMethod [optional] The request method
     *
     * @return RouteContract|null
     *                            The route if found or null when no route is
     *                            found for the path and method combination specified
     */
    public function match(string $path, RequestMethod|null $requestMethod = null): RouteContract|null;

    /**
     * Match a static route by path.
     *
     * @param non-empty-string   $path          The path
     * @param RequestMethod|null $requestMethod [optional] The request method
     *
     * @return RouteContract|null
     *                            The route if found or null when no static route is
     *                            found for the path and method combination specified
     */
    public function matchStatic(string $path, RequestMethod|null $requestMethod = null): RouteContract|null;

    /**
     * Match a dynamic route by path.
     *
     * @param non-empty-string   $path          The path
     * @param RequestMethod|null $requestMethod [optional] The request method
     *
     * @return RouteContract|null
     *                            The route if found or null when no dynamic route is
     *                            found for the path and method combination specified
     */
    public function matchDynamic(string $path, RequestMethod|null $requestMethod = null): RouteContract|null;
}
