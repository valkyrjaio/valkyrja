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

use Valkyrja\Config\Config;
use Valkyrja\Container\Annotation\Contract\Annotations;
use Valkyrja\Container\Config as ContainerConfig;
use Valkyrja\Container\Config\Cache;
use Valkyrja\Container\Contract\ContextAwareContainer;
use Valkyrja\Container\Contract\Service;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Support\Cacheable\Cacheable;

use function array_map;
use function is_file;

/**
 * Class CacheableContainer.
 *
 * @author Melech Mizrachi
 *
 * @psalm-import-type ConfigAsArray from ContainerConfig
 *
 * @phpstan-import-type ConfigAsArray from ContainerConfig
 */
class CacheableContainer extends Container
{
    /**
     * @use Cacheable<ContainerConfig, ConfigAsArray, ContainerConfig>
     */
    use Cacheable;

    /**
     * @inheritDoc
     *
     * @return ContainerConfig
     */
    public function getCacheable(): Config
    {
        $this->setup(true, false);

        // Set app config
        $config        = new ContainerConfig((array) $this->config);
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
     * @inheritDoc
     *
     * @return ContainerConfig|ConfigAsArray
     */
    protected function getConfig(): Config|array
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     *
     * @param ContainerConfig|ConfigAsArray $config
     */
    protected function beforeSetup(Config|array $config): void
    {
    }

    /**
     * @inheritDoc
     *
     * @param ContainerConfig|ConfigAsArray $config
     */
    protected function setupFromCache(Config|array $config): void
    {
        $cache = $config['cache'] ?? null;

        if ($cache === null) {
            $cache         = [];
            $cacheFilePath = $config['cacheFilePath'];

            if (is_file($cacheFilePath)) {
                $cache = require $cacheFilePath;
            }
        }

        $this->aliases          = $cache['aliases'] ?? [];
        $this->deferred         = $cache['deferred'] ?? [];
        $this->deferredCallback = $cache['deferredCallback'] ?? [];
        $this->services         = $cache['services'] ?? [];
        $this->singletons       = $cache['singletons'] ?? [];
        $this->registered       = [];

        // Setup service providers
        $this->setupServiceProviders($config);
    }

    /**
     * @inheritDoc
     *
     * @param ContainerConfig|ConfigAsArray $config
     */
    protected function setupNotCached(Config|array $config): void
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
     * @inheritDoc
     *
     * @param ContainerConfig|ConfigAsArray $config
     */
    protected function setupAnnotations(Config|array $config): void
    {
        /** @var Annotations $containerAnnotations */
        $containerAnnotations = $this->getSingleton(Annotations::class);

        // Get all the annotated services from the list of controllers and iterate through the services
        foreach ($containerAnnotations->getServices(...$config['services']) as $service) {
            $class = $service->getClass();
            $id    = $service->getId();

            if ($class !== null && $id !== null && $id !== '') {
                /** @var class-string<Service> $class */
                // Set the service
                $this->bind($id, $class);
            }
        }

        // Get all the annotated services from the list of controllers and iterate through the services
        foreach ($containerAnnotations->getContextServices(...$config['contextServices']) as $context) {
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
        foreach ($containerAnnotations->getAliasServices(...$config['aliases']) as $alias) {
            $name = $alias->getName();
            $id   = $alias->getId();

            if ($name !== null && $name !== '' && $id !== null && $id !== '') {
                // Set the service
                $this->bindAlias($name, $id);
            }
        }
    }

    /**
     * @inheritDoc
     *
     * @param ContainerConfig|ConfigAsArray $config
     */
    protected function setupAttributes(Config|array $config): void
    {
    }

    /**
     * Setup service providers.
     *
     * @param ContainerConfig|ConfigAsArray $config
     *
     * @return void
     */
    protected function setupServiceProviders(Config|array $config): void
    {
        array_map(
            [$this, 'register'],
            $config['providers']
        );

        // If this is not a dev environment
        if (! $this->debug) {
            return;
        }

        array_map(
            [$this, 'register'],
            $config['devProviders']
        );
    }

    /**
     * @inheritDoc
     *
     * @param ContainerConfig|ConfigAsArray $config
     */
    protected function afterSetup(Config|array $config): void
    {
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
