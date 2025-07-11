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

namespace Valkyrja\Http\Routing;

use Valkyrja\Http\Routing\Data\Contract\Route;

/**
 * Class Data.
 *
 * @author Melech Mizrachi
 *
 * @psalm-type RequestArray array{CONNECT?: array<string, string>, DELETE?: array<string, string>, GET?: array<string, string>, HEAD?: array<string, string>, OPTIONS?: array<string, string>, PATCH?: array<string, string>, POST?: array<string, string>, PUT?: array<string, string>, TRACE?: array<string, string>}
 *
 * @phpstan-type RequestArray array{CONNECT?: array<string, string>, DELETE?: array<string, string>, GET?: array<string, string>, HEAD?: array<string, string>, OPTIONS?: array<string, string>, PATCH?: array<string, string>, POST?: array<string, string>, PUT?: array<string, string>, TRACE?: array<string, string>}
 */
readonly class Data
{
    /**
     * @param array<string, Route|string> $routes
     * @param RequestArray                $static
     * @param RequestArray                $dynamic
     */
    public function __construct(
        public array $routes = [],
        public array $static = [],
        public array $dynamic = [],
    ) {
    }
}
