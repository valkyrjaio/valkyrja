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

namespace Valkyrja\Http\Routing\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Http\Routing\Model\Contract\Route;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 */
class Cache extends Model
{
    /**
     * The flattened routes.
     *
     * @var array<string, Route>|array<int, array<Route>|array<string, mixed>>
     */
    public array $routes = [];

    /**
     * The static routes.
     *
     * @var array{CONNECT?: array<string, string>, DELETE?: array<string, string>, GET?: array<string, string>, HEAD?: array<string, string>, OPTIONS?: array<string, string>, PATCH?: array<string, string>, POST?: array<string, string>, PUT?: array<string, string>, TRACE?: array<string, string>}
     */
    public array $static = [];

    /**
     * The dynamic routes.
     *
     * @var array{CONNECT?: array<string, string>, DELETE?: array<string, string>, GET?: array<string, string>, HEAD?: array<string, string>, OPTIONS?: array<string, string>, PATCH?: array<string, string>, POST?: array<string, string>, PUT?: array<string, string>, TRACE?: array<string, string>}
     */
    public array $dynamic = [];

    /**
     * The named routes.
     *
     * @var array<string, string>
     */
    public array $named = [];
}
