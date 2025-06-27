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

namespace Valkyrja\Container;

use Valkyrja\Container\Attribute\Contract\Collector;
use Valkyrja\Container\Config\Cache;
use Valkyrja\Container\Contract\ContextAwareContainer;
use Valkyrja\Container\Contract\Service;
use Valkyrja\Container\Exception\InvalidArgumentException;
use Valkyrja\Container\Support\Provider;

use function array_map;

/**
 * Class CacheableContainer.
 *
 * @author Melech Mizrachi
 */
class CacheableContainer extends Container
{
    /**
     * Has setup already completed? Used to avoid duplicate setup.
     *
     * @var bool
     */
    protected bool $setup = false;

    /**
     * Setup the container.
     */
    public function setup(bool $force = false, bool $useCache = true): void
    {
        // If route's have already been setup, no need to do it again
        if ($this->setup && ! $force) {
            return;
        }

        $this->setup = true;

        $cache = $this->config->cache;

        // If the application should use the routes cache file
        if ($useCache && $cache) {
            $this->setupFromCache($cache);

            // Then return out of setup
            return;
        }

        $this->setupNotCached();
        $this->setupAttributedServices();
    }

    /**
     * Get a cacheable representation of the container.
     */
    public function getCacheable(): Config
    {
        $this->setup(true, false);

        // Set app config
        $config = clone $this->config;

        $config->cache = $this->getCacheModel();
        $providers     = $config->providers;

        // Iterate through all the providers
        foreach ($providers as $key => $provider) {
            /** @var Provider $provider */
            if ($provider::deferred()) {
                unset($providers[$key]);
            }
        }

        $config->providers = $providers;

        return $config;
    }

    /**
     * Setup from cache.
     */
    protected function setupFromCache(Cache $cache): void
    {
        $this->aliases          = $cache->aliases;
        $this->deferred         = $cache->deferred;
        $this->deferredCallback = $cache->deferredCallback;
        $this->services         = $cache->services;
        $this->singletons       = $cache->singletons;
        $this->registered       = [];

        // Setup service providers
        $this->setupServiceProviders();
    }

    /**
     * Setup not cached.
     */
    protected function setupNotCached(): void
    {
        // Setup service providers
        $this->setupServiceProviders();
    }

    /**
     * Get attributed services.
     */
    protected function setupAttributedServices(): void
    {
        $collector = $this->getSingleton(Collector::class);

        foreach ($collector->getServices(...$this->config->services) as $service) {
            $class = $service->dispatch->getClass();

            if (! is_a($class, Service::class, true)) {
                throw new InvalidArgumentException("Class for $class must implement " . Service::class);
            }

            if ($service->isSingleton) {
                $this->bindSingleton($service->serviceId, $class);

                continue;
            }

            $this->bind($service->serviceId, $class);
        }

        if ($this instanceof ContextAwareContainer) {
            foreach ($collector->getContextServices(...$this->config->contextServices) as $service) {
                $class = $service->dispatch->getClass();

                if (! is_a($class, Service::class, true)) {
                    throw new InvalidArgumentException("Class for $class must implement " . Service::class);
                }

                $contextContainer = $this->withContext($service->contextClassName, $service->contextMemberName);

                if ($service->isSingleton) {
                    $contextContainer->bindSingleton($service->serviceId, $class);

                    continue;
                }

                $contextContainer->bind($service->serviceId, $class);
            }

            foreach ($collector->getContextAliases(...$this->config->aliases) as $service) {
                $this->withContext($service->contextClassName, $service->contextMemberName)
                     ->bindAlias($service->dispatch->getClass(), $service->serviceId);
            }
        }

        foreach ($collector->getAliases(...$this->config->aliases) as $service) {
            $this->bindAlias($service->dispatch->getClass(), $service->serviceId);
        }
    }

    /**
     * Setup service providers.
     */
    protected function setupServiceProviders(): void
    {
        array_map(
            [$this, 'register'],
            $this->config->providers
        );
    }

    /**
     * Get the cache model.
     *
     * @return Cache
     */
    protected function getCacheModel(): Cache
    {
        $config = new Cache();

        $config->aliases          = $this->aliases;
        $config->deferred         = $this->deferred;
        $config->deferredCallback = $this->deferredCallback;
        $config->services         = $this->services;
        $config->singletons       = $this->singletons;

        return $config;
    }
}
