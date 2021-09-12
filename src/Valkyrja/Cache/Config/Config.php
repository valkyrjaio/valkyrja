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

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;

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
        CKP::DEFAULT  => EnvKey::CACHE_DEFAULT,
        CKP::ADAPTERS => EnvKey::CACHE_ADAPTERS,
        CKP::DRIVERS  => EnvKey::CACHE_DRIVERS,
        CKP::STORES   => EnvKey::CACHE_STORES,
    ];

    /**
     * The default store.
     *
     * @var string
     */
    public string $default;

    /**
     * The adapters.
     *
     * @var string[]
     */
    public array $adapters;

    /**
     * The drivers.
     *
     * @var string[]
     */
    public array $drivers;

    /**
     * The cache stores.
     *
     * @var array
     */
    public array $stores;
}
