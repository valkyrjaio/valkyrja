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

namespace Valkyrja\Http;

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Config\Config as Model;
use Valkyrja\Http\Middleware\Config as Middleware;
use Valkyrja\Http\Server\Config as Server;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envKeys = [
        'middleware' => EnvKey::HTTP_MIDDLEWARE,
        'server'     => EnvKey::HTTP_SERVER,
    ];

    public Middleware $middleware;

    public Server $server;
}
