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

namespace Valkyrja\Cache\Config;

use Valkyrja\Cache\Adapter\RedisAdapter;
use Valkyrja\Cache\Constant\ConfigName;
use Valkyrja\Cache\Constant\EnvName;

/**
 * Class RedisConfiguration.
 *
 * @author Melech Mizrachi
 */
class RedisConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => EnvName::REDIS_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS  => EnvName::REDIS_DRIVER_CLASS,
        ConfigName::HOST          => EnvName::REDIS_HOST,
        ConfigName::PORT          => EnvName::REDIS_PORT,
        ConfigName::PREFIX        => EnvName::REDIS_PREFIX,
    ];

    public function __construct(
        public string $host = '127.0.0.1',
        public int $port = 6379,
        public string $prefix = '',
    ) {
        parent::__construct(
            adapterClass: RedisAdapter::class,
        );
    }
}
