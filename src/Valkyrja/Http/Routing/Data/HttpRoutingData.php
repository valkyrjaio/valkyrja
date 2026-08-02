<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Routing\Data;

use Closure;
use Valkyrja\Http\Routing\Data\Contract\DynamicRouteContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

/**
 * @psalm-type RequestArray array{CONNECT?: array<string, string>, DELETE?: array<string, string>, GET?: array<string, string>, HEAD?: array<string, string>, OPTIONS?: array<string, string>, PATCH?: array<string, string>, POST?: array<string, string>, PUT?: array<string, string>, TRACE?: array<string, string>}
 *
 * @phpstan-type RequestArray array{CONNECT?: array<string, string>, DELETE?: array<string, string>, GET?: array<string, string>, HEAD?: array<string, string>, OPTIONS?: array<string, string>, PATCH?: array<string, string>, POST?: array<string, string>, PUT?: array<string, string>, TRACE?: array<string, string>}
 */
readonly class HttpRoutingData
{
    /**
     * @param array<string, Closure():(RouteContract|DynamicRouteContract)> $routes       The routes
     * @param RequestArray                                                  $paths        The static paths list
     * @param RequestArray                                                  $regexes      The regex list
     * @param RequestArray                                                  $dynamicPaths The dynamic paths list
     */
    public function __construct(
        public array $routes = [],
        public array $paths = [],
        public array $dynamicPaths = [],
        public array $regexes = [],
    ) {
    }
}
