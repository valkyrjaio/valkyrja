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
use Valkyrja\Http\Exception\InvalidArgumentException;
use Valkyrja\Http\Middleware\Config\Middleware;
use Valkyrja\Http\Server\Config\Server;

use function is_array;

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
        $middleware = $properties['middleware'] ?? null;
        $server     = $properties['server'] ?? null;

        if ($middleware !== null && ! is_array($middleware)) {
            throw new InvalidArgumentException('Expecting middleware config to be an array or not be provided');
        }

        if ($server !== null && ! is_array($server)) {
            throw new InvalidArgumentException('Expecting server config to be a array or not be provided');
        }

        /** @var array<string, mixed> $middleware */
        /** @var array<string, mixed> $server */
        $this->middleware = new Middleware($middleware, true);
        $this->server     = new Server($server, true);
    }
}
