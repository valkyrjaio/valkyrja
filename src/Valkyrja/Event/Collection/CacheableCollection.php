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
use Valkyrja\Event\Attribute\Contract\Attributes;
use Valkyrja\Event\Config;
use Valkyrja\Event\Config\Cache;

use function is_file;

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
        // The cacheable config
        $config = $this->config;

        $configUseCache = $config->useCache;

        // If the application should use the routes cache file
        if ($useCache && $configUseCache) {
            $this->setupFromCache();

            // Then return out of setup
            return;
        }

        $this->setupNotCached();
        $this->setupAttributedListeners();
        $this->requireFilePath();
    }

    /**
     * Get a cacheable representation of the collection.
     */
    public function getCacheable(): Cache
    {
        $this->setup(true, false);

        $config            = new Cache();
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
        $this->events = [];
    }

    /**
     * Setup from cache.
     */
    protected function setupFromCache(): void
    {
        $cache = $this->config->cache ?? null;

        if ($cache === null) {
            $cache         = [];
            $cacheFilePath = $this->config->cacheFilePath;

            if (is_file($cacheFilePath)) {
                $cache = require $cacheFilePath;
            }
        }

        $this->events    = $cache['events'] ?? [];
        $this->listeners = $cache['listeners'] ?? [];
    }

    /**
     * Get attributed listeners.
     */
    protected function setupAttributedListeners(): void
    {
        /** @var Attributes $listenerAttributes */
        $listenerAttributes = $this->container->getSingleton(Attributes::class);

        // Get all the annotated listeners from the list of classes
        // Iterate through the listeners
        foreach ($listenerAttributes->getListeners(...$this->config->listenerClasses) as $listener) {
            // Set the route
            $this->addListener($listener);
        }
    }

    /**
     * Require the file path specified in the config.
     */
    protected function requireFilePath(): void
    {
        $filePath = $this->config->filePath;

        if (is_file($filePath)) {
            $collection = $this;

            require $filePath;
        }
    }
}
