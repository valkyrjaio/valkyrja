<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Support\Cacheables;

use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Config\Models\Cacheable;
use Valkyrja\Config\Models\Model;

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
        $this->setupFromAnnotations($config);

        // If only annotations should be used for routing
        if ($config->useAnnotationsExclusively ?? false) {
            // Return to avoid loading routes file
            return;
        }

        $this->requireConfig($config);
        $this->afterSetup($config);
    }

    /**
     * Get a cacheable representation of the data.
     *
     * @return Model|object
     */
    abstract public function getCacheable(): object;

    /**
     * Get the config.
     *
     * @return Cacheable|array
     */
    abstract protected function getConfig();

    /**
     * Before setup.
     *
     * @param Cacheable|array $config
     *
     * @return void
     */
    protected function beforeSetup($config): void
    {
        // Override as necessary
    }

    /**
     * Setup from cache.
     *
     * @param Cacheable|array $config
     *
     * @return void
     */
    protected function setupFromCache(array $config): void
    {
        // Override as necessary
    }

    /**
     * Set not cached.
     *
     * @param Cacheable|array $config
     *
     * @return void
     */
    protected function setupNotCached($config): void
    {
        // Override as necessary
    }

    /**
     * Set annotations.
     *
     * @param Cacheable|array $config
     *
     * @return void
     */
    protected function setupFromAnnotations($config): void
    {
        // If annotations are enabled and cacheable should use annotations
        if (($config['useAnnotations'] ?? false) && config(ConfigKey::ANNOTATIONS_ENABLED)) {
            $this->setupAnnotations($config);
        }
    }

    /**
     * Set annotations.
     *
     * @param Cacheable|array $config
     *
     * @return void
     */
    protected function requireConfig($config): void
    {
        require $config['filePath'];
    }

    /**
     * Before setup.
     *
     * @param Cacheable|array $config
     *
     * @return void
     */
    protected function afterSetup($config): void
    {
        // Override as necessary
    }

    /**
     * Set annotations.
     *
     * @param Cacheable|array $config
     *
     * @return void
     */
    protected function setupAnnotations($config): void
    {
        // Override as necessary
    }
}
