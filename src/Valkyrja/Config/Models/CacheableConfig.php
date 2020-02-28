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

namespace Valkyrja\Config\Models;

/**
 * Class CacheableConfig.
 *
 * @author Melech Mizrachi
 */
class CacheableConfig extends AnnotatableConfig
{
    public array  $cache         = [];
    public string $filePath      = '';
    public string $cacheFilePath = '';
    public bool   $useCache      = false;

    protected string $envFilePathKey      = '';
    protected string $envCacheFilePathKey = '';
    protected string $envUseCacheKey      = '';

    /**
     * Set cacheable config.
     *
     * @return void
     */
    protected function setCacheableConfig(): void
    {
        $this->filePath      = (string) env($this->envFilePathKey, $this->filePath);
        $this->cacheFilePath = (string) env($this->envCacheFilePathKey, $this->cacheFilePath);
        $this->useCache      = (bool) env($this->envUseCacheKey, $this->useCache);
    }
}
