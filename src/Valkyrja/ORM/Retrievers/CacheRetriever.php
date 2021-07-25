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

namespace Valkyrja\ORM\Retrievers;

use Valkyrja\Cache\Cache;
use Valkyrja\ORM\Adapter;

/**
 * Class CacheRetriever
 *
 * @author Melech Mizrachi
 */
class CacheRetriever extends LocalCacheRetriever
{
    /**
     * The cache service
     *
     * @var Cache
     */
    protected Cache $cache;

    /**
     * CacheRetriever constructor.
     *
     * @param Adapter $adapter The adapter
     * @param Cache   $cache   The service
     */
    public function __construct(Adapter $adapter, Cache $cache)
    {
        parent::__construct($adapter);

        $this->cache = $cache;
    }
}
