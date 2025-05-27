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
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Exception\RuntimeException;

use function array_map;
use function is_file;

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
     *
     * @param bool $force    [optional] Whether to force setup
     * @param bool $useCache [optional] Whether to use cache
     *
     * @return void
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
            $this->setupFromCache($config);

            // Then return out of setup
            return;
        }

        $this->setupNotCached($config);
        $this->setupAnnotatedServices($config);
        $this->setupAttributedServices($config);
        $this->requireFilePath($config);
    }

    /**
     * Get a cacheable representation of the container.
     */
    public function getCacheable(): Config
    {
        $this->setup(true, false);

        // Set app config
        $config        = clone $this->config;
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
    protected function setupFromCache(Config $config): void
    {
        $cache = $config->cache ?? null;

        if ($cache === null) {
            $cache         = [];
            $cacheFilePath = $config->cacheFilePath;

            if (is_file($cacheFilePath)) {
                $cache = require $cacheFilePath;
            } else {
                throw new RuntimeException('No cache found');
            }
        }

        $this->aliases          = $cache->aliases;
        $this->deferred         = $cache->deferred;
        $this->deferredCallback = $cache->deferredCallback;
        $this->services         = $cache->services;
        $this->singletons       = $cache->singletons;
        $this->registered       = [];

        // Setup service providers
        $this->setupServiceProviders($config);
    }

    /**
     * Setup not cached.
     */
    protected function setupNotCached(Config $config): void
    {
        $this->aliases          = [];
        $this->registered       = [];
        $this->deferred         = [];
        $this->deferredCallback = [];
        $this->services         = [];
        $this->singletons       = [];

        // Setup service providers
        $this->setupServiceProviders($config);
    }

    /**
     * Get annotated services.
     */
    protected function setupAnnotatedServices(Config $config): void
    {
        /** @var Annotations $containerAnnotations */
        $containerAnnotations = $this->getSingleton(Annotations::class);

        // Get all the annotated services from the list of controllers and iterate through the services
        foreach ($containerAnnotations->getServices(...$config->services) as $service) {
            $class = $service->getClass();
            $id    = $service->getId();

            if ($class !== null && $id !== null && $id !== '') {
                /** @var class-string<Service> $class */
                // Set the service
                $this->bind($id, $class);
            }
        }

        // Get all the annotated services from the list of controllers and iterate through the services
        foreach ($containerAnnotations->getContextServices(...$config->contextServices) as $context) {
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
        foreach ($containerAnnotations->getAliasServices(...$config->aliases) as $alias) {
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
    protected function setupAttributedServices(Config $config): void
    {
    }

    /**
     * Require the file path specified in the config.
     */
    protected function requireFilePath(Config $config): void
    {
        $filePath = $config->filePath;

        if (! is_file($filePath)) {
            throw new InvalidArgumentException('Invalid file path provided');
        }

        require $filePath;
    }

    /**
     * Setup service providers.
     */
    protected function setupServiceProviders(Config $config): void
    {
        array_map(
            [$this, 'register'],
            $config->providers
        );

        // If this is not a dev environment
        if (! $this->debug) {
            return;
        }

        array_map(
            [$this, 'register'],
            $config->devProviders
        );
    }

    /**
     * Get the cache model.
     *
     * @return Cache
     */
    protected function getCacheModel(): Cache
    {
        $config                   = new Cache();
        $config->aliases          = $this->aliases;
        $config->deferred         = $this->deferred;
        $config->deferredCallback = $this->deferredCallback;
        $config->services         = $this->services;
        $config->singletons       = $this->singletons;

        return $config;
    }
}
