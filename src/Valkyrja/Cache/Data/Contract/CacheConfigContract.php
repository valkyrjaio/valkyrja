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

namespace Valkyrja\Cache\Data\Contract;

use Valkyrja\Cache\Data\CacheLogConfig;
use Valkyrja\Cache\Data\CacheNullConfig;
use Valkyrja\Cache\Data\CacheRedisConfig;
use Valkyrja\Cache\Manager\Contract\CacheContract;

interface CacheConfigContract
{
    /** @var class-string<CacheContract> */
    public string $defaultCache {
        get;
    }

    public CacheRedisConfig $redisCache {
        get;
    }

    public CacheLogConfig $logCache {
        get;
    }

    public CacheNullConfig $nullCache {
        get;
    }
}
