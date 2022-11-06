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

use Valkyrja\Cache\Adapters\RedisAdapter;
use Valkyrja\Cache\Drivers\Driver;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\Support\Manager\Config\Config as Model;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     */
    protected static array $envKeys = [
        CKP::DEFAULT => EnvKey::CACHE_DEFAULT,
        CKP::ADAPTER => EnvKey::CACHE_ADAPTER,
        CKP::DRIVER  => EnvKey::CACHE_DRIVER,
        CKP::STORES  => EnvKey::CACHE_STORES,
    ];

    /**
     * @inheritDoc
     */
    public string $default = CKP::REDIS;

    /**
     * @inheritDoc
     */
    public string $adapter = RedisAdapter::class;

    /**
     * @inheritDoc
     */
    public string $driver = Driver::class;

    /**
     * The cache stores.
     *
     * @var array
     */
    public array $stores = [
        CKP::REDIS => [
            CKP::ADAPTER => CKP::REDIS,
            CKP::DRIVER  => null,
            CKP::HOST    => '',
            CKP::PORT    => '',
            CKP::PREFIX  => '',
        ],
        CKP::NULL  => [
            CKP::ADAPTER => CKP::NULL,
            CKP::DRIVER  => null,
            CKP::PREFIX  => '',
        ],
        CKP::LOG   => [
            CKP::ADAPTER => CKP::LOG,
            CKP::DRIVER  => null,
            CKP::LOGGER  => null,
            CKP::PREFIX  => '',
        ],
    ];
}
