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

namespace Valkyrja\Support\Cacheable;

use Valkyrja\Config\Config;

/**
 * Trait Cacheable.
 *
 * @author   Melech Mizrachi
 *
 * @template Config
 * @template Cache
 */
trait Cacheable
{
    /**
     * Has setup already completed? Used to avoid duplicate setup.
     *
     * @var bool
     */
    protected bool $setup = false;

    /**
     * Setup the service.
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
        $config = $this->getConfig();

        $this->beforeSetup($config);

        // If the application should use the routes cache file
        if ($useCache && $config['useCache']) {
            /** @var Config|array $config */
            $this->setupFromCache($config);

            // Then return out of setup
            return;
        }

        $this->setupNotCached($config);
        $this->setupFromAttributes($config);
        $this->setupFromAnnotations($config);
        $this->requireFilePath($config);
        $this->afterSetup($config);
    }

    /**
     * Get a cacheable representation of the data.
     *
     * @return Cache
     */
    abstract public function getCacheable(): Config;

    /**
     * Set annotations.
     *
     * @param Config|array{useAnnotations: ?bool} $config
     *
     * @return void
     */
    protected function setupFromAnnotations(Config|array $config): void
    {
        $useAnnotations = (bool) ($config['useAnnotations'] ?? true);

        // If annotations are enabled and cacheable should use annotations
        if ($useAnnotations) {
            /** @var Config|array $config */
            $this->setupAnnotations($config);
        }
    }

    /**
     * Set attributes.
     *
     * @param Config|array{useAttributes: ?bool} $config The config
     *
     * @return void
     */
    protected function setupFromAttributes(Config|array $config): void
    {
        $useAttributes = (bool) ($config['useAttributes'] ?? true);

        // If attributes are enabled and cacheable should use attributes
        if ($useAttributes) {
            /** @var Config|array $config */
            $this->setupAttributes($config);
        }
    }

    /**
     * Require the file path specified in the config.
     *
     * @param Config|array $config The config
     *
     * @return void
     */
    protected function requireFilePath(Config|array $config): void
    {
        require $config['filePath'];
    }

    /**
     * Get the config.
     *
     * @return Config|array
     */
    abstract protected function getConfig(): Config|array;

    /**
     * Before setup.
     *
     * @param Config|array $config The config
     *
     * @return void
     */
    abstract protected function beforeSetup(Config|array $config): void;

    /**
     * Setup from cache.
     *
     * @param Config|array $config The config
     *
     * @return void
     */
    abstract protected function setupFromCache(Config|array $config): void;

    /**
     * Set not cached.
     *
     * @param Config|array $config The config
     *
     * @return void
     */
    abstract protected function setupNotCached(Config|array $config): void;

    /**
     * Set annotations.
     *
     * @param Config|array $config The config
     *
     * @return void
     */
    abstract protected function setupAnnotations(Config|array $config): void;

    /**
     * Set attributes.
     *
     * @param Config|array $config The config
     *
     * @return void
     */
    abstract protected function setupAttributes(Config|array $config): void;

    /**
     * After setup.
     *
     * @param Config|array $config The config
     *
     * @return void
     */
    abstract protected function afterSetup(Config|array $config): void;
}
