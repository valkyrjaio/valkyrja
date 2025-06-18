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

use Valkyrja\Container\Annotation\Contract\Annotations;
use Valkyrja\Container\Config\Cache;
use Valkyrja\Container\Contract\ContextAwareContainer;
use Valkyrja\Container\Contract\Service;
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
        $this->setupAnnotatedServices();
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
     * Get annotated services.
     */
    protected function setupAnnotatedServices(): void
    {
        /** @var Annotations $containerAnnotations */
        $containerAnnotations = $this->getSingleton(Annotations::class);

        // Get all the annotated services from the list of controllers and iterate through the services
        foreach ($containerAnnotations->getServices(...$this->config->services) as $service) {
            $class = $service->getClass();
            $id    = $service->getId();

            if ($class !== null && $id !== null && $id !== '') {
                /** @var class-string<Service> $class */
                // Set the service
                $this->bind($id, $class);
            }
        }

        // Get all the annotated services from the list of controllers and iterate through the services
        foreach ($containerAnnotations->getContextServices(...$this->config->contextServices) as $context) {
            $class   = $context->getClass();
            $method  = $context->getMethod();
            $id      = $context->getId();
            $service = $context->getService();

            if ($class !== null && $id !== null && $service && $this instanceof ContextAwareContainer) {
                // Set the service
                $this->withContext($class, $method)->bind($id, $service);
            }
        }

        // Get all the annotated services from the list of classes and iterate through the services
        foreach ($containerAnnotations->getAliasServices(...$this->config->aliases) as $alias) {
            $name = $alias->getName();
            $id   = $alias->getId();

            if ($name !== null && $name !== '' && $id !== null && $id !== '') {
                // Set the service
                $this->bindAlias($name, $id);
            }
        }
    }

    /**
     * Get attributed services.
     */
    protected function setupAttributedServices(): void
    {
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

        // If this is not a dev environment
        if (! $this->debug) {
            return;
        }

        array_map(
            [$this, 'register'],
            $this->config->devProviders
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
