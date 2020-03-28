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

namespace Valkyrja\Config\Models;

use function Valkyrja\env;

/**
 * Class Cacheable.
 *
 * @author Melech Mizrachi
 */
class Cacheable extends Annotatable
{
    /**
     * The cache from a Cacheable::getCacheable().
     *
     * @var Model|object|null
     */
    public ?object $cache = null;

    /**
     * The file path.
     *
     * @var string
     */
    public string $filePath;

    /**
     * The cache file path.
     *
     * @var string
     */
    public string $cacheFilePath;

    /**
     * The flag to enable cache.
     *
     * @var bool
     */
    public bool $useCache;

    /**
     * The file path env key.
     *
     * @var string
     */
    protected string $envFilePathKey;

    /**
     * The cache file path env key.
     *
     * @var string
     */
    protected string $envCacheFilePathKey;

    /**
     * The flag to enable cache env key.
     *
     * @var string
     */
    protected string $envUseCacheKey;

    /**
     * Set cacheable config.
     *
     * @return void
     */
    protected function setCacheableConfig(): void
    {
        $this->setFilePath();
        $this->setCacheFilePath();
        $this->setUseCache();
    }

    /**
     * Set the file path.
     *
     * @param string $filePath [optional] The file path
     *
     * @return void
     */
    protected function setFilePath(string $filePath = ''): void
    {
        $this->filePath = (string) env($this->envFilePathKey, $filePath);
    }

    /**
     * Set the cache file path.
     *
     * @param string $cacheFilePath [optional] The cache file path
     *
     * @return void
     */
    protected function setCacheFilePath(string $cacheFilePath = ''): void
    {
        $this->cacheFilePath = (string) env($this->envCacheFilePathKey, $cacheFilePath);
    }

    /**
     * Set the flag to enable cache.
     *
     * @param bool $useCache [optional] The flag to enable cache
     *
     * @return void
     */
    protected function setUseCache(bool $useCache = false): void
    {
        $this->useCache = (bool) env($this->envUseCacheKey, $useCache);
    }

    /**
     * Set the file path env key.
     *
     * @param string $envKey The file path env key
     *
     * @return void
     */
    protected function setFilePathEnvKey(string $envKey): void
    {
        $this->envFilePathKey = $envKey;
    }

    /**
     * Set the cache file path env key.
     *
     * @param string $envKey The cache file path env key
     *
     * @return void
     */
    protected function setCacheFilePathEnvKey(string $envKey): void
    {
        $this->envCacheFilePathKey = $envKey;
    }

    /**
     * Set the use cache flag env key.
     *
     * @param string $envKey The use cache flag env key
     *
     * @return void
     */
    protected function setUseCacheEnvKey(string $envKey): void
    {
        $this->envUseCacheKey = $envKey;
    }
}
