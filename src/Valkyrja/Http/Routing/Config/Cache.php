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
     * @var array
     */
    public array $routes = [];

    /**
     * The static routes.
     *
     * @var array
     */
    public array $static = [];

    /**
     * The dynamic routes.
     *
     * @var array
     */
    public array $dynamic = [];

    /**
     * The named routes.
     *
     * @var array
     */
    public array $named = [];
}
