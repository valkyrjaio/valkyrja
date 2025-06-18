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

namespace Valkyrja\Event\Collection;

use Valkyrja\Container\Contract\Container;
use Valkyrja\Event\Attribute\Contract\Collector;
use Valkyrja\Event\Config;
use Valkyrja\Event\Config\Cache;

/**
 * Class CacheableCollection.
 *
 * @author Melech Mizrachi
 */
class CacheableCollection extends Collection
{
    /**
     * Has setup already completed? Used to avoid duplicate setup.
     *
     * @var bool
     */
    protected bool $setup = false;

    /**
     * CacheableCollection constructor.
     */
    public function __construct(
        protected Container $container,
        protected Config $config
    ) {
    }

    /**
     * Setup the collection.
     */
    public function setup(bool $force = false, bool $useCache = true): void
    {
        // If route's have already been setup, no need to do it again
        if ($this->setup && ! $force) {
            return;
        }

        $this->setup = true;

        $cache = $this->config->cache;

        // If the application should use the routes cache
        if ($useCache && $cache !== null) {
            $this->setupFromCache($cache);

            // Then return out of setup
            return;
        }

        $this->setupNotCached();
    }

    /**
     * Get a cacheable representation of the collection.
     */
    public function getCacheable(): Cache
    {
        $this->setup(true, false);

        $config = new Cache();

        $config->events    = $this->events;
        $config->listeners = [];

        foreach ($this->listeners as $id => $listener) {
            $config->listeners[$id] = serialize($listener);
        }

        return $config;
    }

    /**
     * Setup not cached.
     */
    protected function setupNotCached(): void
    {
        $this->setupAttributedListeners();
    }

    /**
     * Setup from cache.
     */
    protected function setupFromCache(Cache $cache): void
    {
        $this->events    = $cache->events;
        $this->listeners = $cache->listeners;
    }

    /**
     * Get attributed listeners.
     */
    protected function setupAttributedListeners(): void
    {
        /** @var Collector $listenerAttributes */
        $listenerAttributes = $this->container->getSingleton(Collector::class);

        // Get all the annotated listeners from the list of classes
        // Iterate through the listeners
        foreach ($listenerAttributes->getListeners(...$this->config->listenerClasses) as $listener) {
            // Set the route
            $this->addListener($listener);
        }
    }
}
