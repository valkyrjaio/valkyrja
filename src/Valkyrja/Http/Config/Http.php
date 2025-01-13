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

namespace Valkyrja\Http\Config;

use Valkyrja\Http\Config as Model;
use Valkyrja\Http\Middleware\Config\Middleware;
use Valkyrja\Http\Server\Config\Server;

/**
 * Class Middleware.
 */
class Http extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->middleware = new Middleware($properties['middleware'] ?? null, true);
        $this->server     = new Server($properties['server'] ?? null, true);
    }
}
