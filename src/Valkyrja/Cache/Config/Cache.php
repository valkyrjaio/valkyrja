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

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Cache\Adapter\LogAdapter;
use Valkyrja\Cache\Adapter\NullAdapter;
use Valkyrja\Cache\Config as Model;
use Valkyrja\Cache\Constant\ConfigValue;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;

use function Valkyrja\env;

/**
 * Class Cache.
 */
class Cache extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(?array $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->stores = [
            CKP::REDIS => [
                CKP::ADAPTER => env(EnvKey::CACHE_REDIS_ADAPTER),
                CKP::DRIVER  => env(EnvKey::CACHE_REDIS_DRIVER),
                CKP::HOST    => env(EnvKey::CACHE_REDIS_HOST, ''),
                CKP::PORT    => env(EnvKey::CACHE_REDIS_PORT, ''),
                CKP::PREFIX  => env(EnvKey::CACHE_REDIS_PREFIX),
            ],
            CKP::NULL  => [
                CKP::ADAPTER => env(EnvKey::CACHE_NULL_ADAPTER, NullAdapter::class),
                CKP::DRIVER  => env(EnvKey::CACHE_NULL_DRIVER),
                CKP::PREFIX  => env(EnvKey::CACHE_NULL_PREFIX),
            ],
            CKP::LOG   => [
                CKP::ADAPTER => env(EnvKey::CACHE_LOG_ADAPTER, LogAdapter::class),
                CKP::DRIVER  => env(EnvKey::CACHE_LOG_DRIVER),
                // null will use default as set in log config
                CKP::LOGGER  => env(EnvKey::CACHE_LOG_LOGGER),
                CKP::PREFIX  => env(EnvKey::CACHE_LOG_PREFIX),
            ],
        ];
    }
}
