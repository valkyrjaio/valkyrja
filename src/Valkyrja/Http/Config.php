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

use Valkyrja\Http\Constant\ConfigName;
use Valkyrja\Http\Constant\EnvName;
use Valkyrja\Http\Middleware\Config as MiddlewareConfig;
use Valkyrja\Http\Routing\Config as RoutingConfig;
use Valkyrja\Support\Config as ParentConfig;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::MIDDLEWARE => EnvName::MIDDLEWARE,
        ConfigName::ROUTING    => EnvName::ROUTING,
    ];

    public function __construct(
        public MiddlewareConfig $middleware = new MiddlewareConfig(),
        public RoutingConfig $routing = new RoutingConfig(),
    ) {
    }

    /**
     * @inheritDoc
     */
    public function setPropertiesFromEnv(string $env): void
    {
        $this->middleware->setPropertiesFromEnv($env);
        $this->routing->setPropertiesFromEnv($env);
    }
}
