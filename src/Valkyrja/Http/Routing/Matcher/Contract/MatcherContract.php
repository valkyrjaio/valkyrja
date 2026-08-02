<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
