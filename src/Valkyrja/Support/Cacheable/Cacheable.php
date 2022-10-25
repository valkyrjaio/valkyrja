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
 * @author Melech Mizrachi
 */
trait Cacheable
{
    /**
     * Has setup already completed? Used to avoid duplicate setup.
     *
     * @var bool
     */
    protected static bool $setup = false;

    /**
     * Set the data from cache.
     *
     * @param bool $force    [optional] Whether to force setup
     * @param bool $useCache [optional] Whether to use cache
     *
     * @return void
     */
    public function setup(bool $force = false, bool $useCache = true): void
    {
        // If route's have already been setup, no need to do it again
        if (self::$setup && ! $force) {
            return;
        }

        self::$setup = true;
        // The cacheable config
        $config = $this->getConfig();

        $this->beforeSetup($config);

        // If the application should use the routes cache file
        if ($useCache && ($config['useCache'])) {
            $this->setupFromCache($config);

            // Then return out of setup
            return;
        }

        $this->setupNotCached($config);
        $this->setupFromAttributes($config);
        $this->setupFromAnnotations($config);
        $this->requireConfig($config);
        $this->afterSetup($config);
    }

    /**
     * Get a cacheable representation of the data.
     *
     * @return Config
     */
    abstract public function getCacheable(): Config;

    /**
     * Get the config.
     *
     * @return Config|array
     */
    abstract protected function getConfig(): Config|array;

    /**
     * Before setup.
     *
     * @param Config|array $config
     *
     * @return void
     */
    abstract protected function beforeSetup(Config|array $config): void;

    /**
     * Setup from cache.
     *
     * @param array $config
     *
     * @return void
     */
    abstract protected function setupFromCache(array $config): void;

    /**
     * Set not cached.
     *
     * @param Config|array $config
     *
     * @return void
     */
    abstract protected function setupNotCached(Config|array $config): void;

    /**
     * Set annotations.
     *
     * @param Config|array $config
     *
     * @return void
     */
    protected function setupFromAnnotations(Config|array $config): void
    {
        // If annotations are enabled and cacheable should use annotations
        if (($config['useAnnotations'] ?? true)) {
            $this->setupAnnotations($config);
        }
    }

    /**
     * Set annotations.
     *
     * @param Config|array $config
     *
     * @return void
     */
    abstract protected function setupAnnotations(Config|array $config): void;

    /**
     * Set attributes.
     *
     * @param Config|array $config
     *
     * @return void
     */
    protected function setupFromAttributes(Config|array $config): void
    {
        // If attributes are enabled and cacheable should use attributes
        if (($config['useAttributes'] ?? true)) {
            $this->setupAttributes($config);
        }
    }

    /**
     * Set attributes.
     *
     * @param Config|array $config
     *
     * @return void
     */
    abstract protected function setupAttributes(Config|array $config): void;

    /**
     * Set annotations.
     *
     * @param Config|array $config
     *
     * @return void
     */
    protected function requireConfig(Config|array $config): void
    {
        require $config['filePath'];
    }

    /**
     * After setup.
     *
     * @param Config|array $config
     *
     * @return void
     */
    abstract protected function afterSetup(Config|array $config): void;
}
