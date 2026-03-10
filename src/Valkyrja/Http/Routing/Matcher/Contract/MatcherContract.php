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

interface MatcherContract
{
    /**
     * Match a route by path.
     *
     * @param non-empty-string $path The path
     */
    public function match(string $path, RequestMethod $requestMethod): RouteContract|null;

    /**
     * Match a static route by path.
     *
     * @param non-empty-string $path The path
     */
    public function matchStatic(string $path, RequestMethod $requestMethod): RouteContract|null;

    /**
     * Match a dynamic route by path.
     *
     * @param non-empty-string $path The path
     */
    public function matchDynamic(string $path, RequestMethod $requestMethod): RouteContract|null;
}
