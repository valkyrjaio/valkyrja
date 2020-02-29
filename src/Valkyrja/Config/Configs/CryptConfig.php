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

use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\ConfigModel as Model;

/**
 * Class CryptConfig.
 *
 * @author Melech Mizrachi
 */
class CryptConfig extends Model
{
    /**
     * The key.
     *
     * @var string
     */
    public string $key;

    /**
     * The optional key path (for a key on disk).
     *
     * @var string|null
     */
    public ?string $keyPath = null;

    /**
     * CryptConfig constructor.
     */
    public function __construct()
    {
        $this->setKey();
        $this->setKeyPath();
    }

    /**
     * Set the key.
     *
     * @param string $key [optional] The key
     *
     * @return void
     */
    protected function setKey(string $key = 'default_key_phrase'): void
    {
        $this->key = (string) env(EnvKey::CRYPT_KEY, $key);
    }

    /**
     * The optional key path (for a key on disk).
     *
     * @param string|null $keyPath [optional] The optional key path
     *
     * @return void
     */
    protected function setKeyPath(string $keyPath = null): void
    {
        $this->keyPath = env(EnvKey::CRYPT_KEY_PATH, $keyPath);
    }
}