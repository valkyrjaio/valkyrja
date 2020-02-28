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
use Valkyrja\Config\Models\Config as Model;

/**
 * Class Crypt.
 *
 * @author Melech Mizrachi
 */
class Crypt extends Model
{
    public string $key     = 'default_key_phrase';
    public string $keyPath = '';

    /**
     * Crypt constructor.
     */
    public function __construct()
    {
        $this->key     = env(EnvKey::CRYPT_KEY, $this->key);
        $this->keyPath = env(EnvKey::CRYPT_KEY_PATH, $this->keyPath);
    }
}
