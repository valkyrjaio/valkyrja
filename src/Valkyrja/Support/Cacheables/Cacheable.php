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
use Valkyrja\Config\Enums\ConfigKeyPart;

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
        $config      = $this->getConfig();

        $this->beforeSetup();

        // If the application should use the routes cache file
        if ($useCache && ($config[ConfigKeyPart::USE_CACHE] ?? false)) {
            $this->setupFromCache();

            // Then return out of setup
            return;
        }

        $this->setupNotCached();
        $this->setupFromAnnotations($config);

        // If only annotations should be used for routing
        if ($config[ConfigKeyPart::USE_ANNOTATIONS_EXCLUSIVELY] ?? false) {
            // Return to avoid loading routes file
            return;
        }

        $this->requireConfig($config);
        $this->afterSetup();
    }

    /**
     * Get a cacheable representation of the data.
     *
     * @return array
     */
    abstract public function getCacheable(): array;

    /**
     * Get the config.
     *
     * @return array
     */
    abstract protected function getConfig(): array;

    /**
     * Before setup.
     *
     * @return void
     */
    protected function beforeSetup(): void
    {
        // Override as necessary
    }

    /**
     * Setup from cache.
     *
     * @return void
     */
    protected function setupFromCache(): void
    {
        // Override as necessary
    }

    /**
     * Set not cached.
     *
     * @return void
     */
    protected function setupNotCached(): void
    {
        // Override as necessary
    }

    /**
     * Set annotations.
     *
     * @param array $config
     *
     * @return void
     */
    protected function setupFromAnnotations(array $config): void
    {
        // If annotations are enabled and cacheable should use annotations
        if (($config[ConfigKeyPart::USE_ANNOTATIONS] ?? false) && (config()[ConfigKey::ANNOTATIONS_ENABLED] ?? false)) {
            $this->setupAnnotations();
        }
    }

    /**
     * Set annotations.
     *
     * @param array $config
     *
     * @return void
     */
    protected function requireConfig(array $config): void
    {
        require $config[ConfigKeyPart::FILE_PATH];
    }

    /**
     * Before setup.
     *
     * @return void
     */
    protected function afterSetup(): void
    {
        // Override as necessary
    }

    /**
     * Set annotations.
     *
     * @return void
     */
    protected function setupAnnotations(): void
    {
        // Override as necessary
    }
}
