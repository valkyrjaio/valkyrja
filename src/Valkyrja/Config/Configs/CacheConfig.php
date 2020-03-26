<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Configs;

use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\ConfigModel as Model;

/**
 * Class CacheConfig.
 *
 * @author Melech Mizrachi
 */
class CacheConfig extends Model
{
    /**
     * The default store.
     *
     * @var string
     */
    public string $default;

    /**
     * The cache stores.
     *
     * @var array
     */
    public array $stores;

    /**
     * CacheConfig constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setDefault();
        $this->setStores();
    }

    /**
     * Set the default store.
     *
     * @param string $default [optional] The default store
     *
     * @return void
     */
    protected function setDefault(string $default = ConfigKeyPart::REDIS): void
    {
        $this->default = (string) env(EnvKey::CACHE_DEFAULT, $default);
    }

    /**
     * Set the cache stores.
     *
     * @param array $stores [optional] The cache stores
     *
     * @return void
     */
    protected function setStores(array $stores = []): void
    {
        $this->stores = (array) env(EnvKey::CACHE_STORES, $stores);
    }
}
