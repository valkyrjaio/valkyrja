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
        ConfigName::ADAPTER_CLASS => 'CACHE_REDIS_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS  => 'CACHE_REDIS_DRIVER_CLASS',
        ConfigName::HOST          => 'CACHE_REDIS_HOST',
        ConfigName::PORT          => 'CACHE_REDIS_PORT',
        ConfigName::PREFIX        => 'CACHE_REDIS_PREFIX',
    ];

    public function __construct(
        public string $host = '',
        public string $port = '',
        public string $prefix = '',
    ) {
        parent::__construct(
            adapterClass: RedisAdapter::class,
        );
    }
}
