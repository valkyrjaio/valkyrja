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
use Valkyrja\Config\Models\Model;

use function env;

/**
 * Class Crypt
 *
 * @author Melech Mizrachi
 */
class Crypt extends Model
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
     * Crypt constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

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
