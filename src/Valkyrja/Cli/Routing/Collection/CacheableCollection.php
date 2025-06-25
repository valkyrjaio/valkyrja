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

namespace Valkyrja\Cli\Routing\Collection;

use Valkyrja\Cli\Routing\Attribute\Contract\Collector;
use Valkyrja\Cli\Routing\Config;
use Valkyrja\Cli\Routing\Config\Cache;
use Valkyrja\Cli\Routing\Data\Contract\Command;
use Valkyrja\Cli\Routing\Exception\RuntimeException;
use Valkyrja\Container\Contract\Container;

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

        $config           = new Cache();
        $config->commands = [];

        foreach ($this->commands as $id => $route) {
            $config->commands[$id] = serialize($route);
        }

        return $config;
    }

    /**
     * Setup from cache.
     */
    protected function setupFromCache(Cache $cache): void
    {
        foreach ($cache->commands as $id => $route) {
            $command = unserialize($route, ['allowed_classes' => true]);

            if (! $command instanceof Command) {
                throw new RuntimeException('Invalid command unserialized');
            }

            $this->commands[$id] = $command;
        }
    }

    /**
     * Setup not cache.
     */
    protected function setupNotCached(): void
    {
        $this->setupAttributedControllers();
    }

    /**
     * Get attributed controllers.
     */
    protected function setupAttributedControllers(): void
    {
        /** @var Collector $collector */
        $collector   = $this->container->getSingleton(Collector::class);
        $controllers = $this->config->controllers;

        // Get all the attributes routes from the list of controllers
        $this->add(
            ...$collector->getCommands(...$controllers)
        );
    }
}
