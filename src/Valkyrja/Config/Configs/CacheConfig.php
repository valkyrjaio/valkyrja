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
 * Class CacheConfig.
 *
 * @author Melech Mizrachi
 */
class CacheConfig extends Model
{
    public string $default = '';

    /**
     * CacheConfig constructor.
     */
    public function __construct()
    {
        $this->default = env(EnvKey::CACHE_DEFAULT, $this->default);
    }
}
