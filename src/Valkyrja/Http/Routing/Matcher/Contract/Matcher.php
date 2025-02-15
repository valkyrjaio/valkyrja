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
use Valkyrja\Http\Routing\Model\Contract\Route;

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
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return Route|null
     *                    The route if found or null when no route is
     *                    found for the path and method combination specified
     */
    public function match(string $path, ?RequestMethod $method = null): ?Route;

    /**
     * Match a static route by path.
     *
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return Route|null
     *                    The route if found or null when no static route is
     *                    found for the path and method combination specified
     */
    public function matchStatic(string $path, ?RequestMethod $method = null): ?Route;

    /**
     * Match a dynamic route by path.
     *
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return Route|null
     *                    The route if found or null when no dynamic route is
     *                    found for the path and method combination specified
     */
    public function matchDynamic(string $path, ?RequestMethod $method = null): ?Route;
}
