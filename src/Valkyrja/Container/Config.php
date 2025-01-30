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

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Container\Config\Cache;
use Valkyrja\Container\Support\Provider;

use function is_array;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envKeys = [
        CKP::ALIASES          => EnvKey::CONTAINER_ALIASES,
        CKP::SERVICES         => EnvKey::CONTAINER_SERVICES,
        CKP::CONTEXT_SERVICES => EnvKey::CONTAINER_CONTEXT_SERVICES,
        CKP::PROVIDERS        => EnvKey::CONTAINER_PROVIDERS,
        CKP::DEV_PROVIDERS    => EnvKey::CONTAINER_DEV_PROVIDERS,
        CKP::USE_ANNOTATIONS  => EnvKey::CONTAINER_USE_ANNOTATIONS,
        CKP::FILE_PATH        => EnvKey::CONTAINER_FILE_PATH,
        CKP::CACHE_FILE_PATH  => EnvKey::CONTAINER_CACHE_FILE_PATH,
        CKP::USE_CACHE        => EnvKey::CONTAINER_USE_CACHE_FILE,
    ];

    /**
     * The annotated service aliases.
     *
     * @var class-string[]
     */
    public array $aliases;

    /**
     * The annotated services.
     *
     * @var class-string[]
     */
    public array $services;

    /**
     * The annotated context services.
     *
     * @var class-string[]
     */
    public array $contextServices;

    /**
     * The command providers.
     *
     * @var class-string<Provider>[]
     */
    public array $providers;

    /**
     * The dev command providers.
     *
     * @var class-string<Provider>[]
     */
    public array $devProviders;

    /**
     * The flag to enable annotations.
     *
     * @var bool
     */
    public bool $useAnnotations;

    /**
     * The cache from a Cacheable::getCacheable().
     *
     * @var Cache|null
     */
    public Cache|null $cache = null;

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
     * Set the cache.
     *
     * @param Cache|array<string, mixed>|null $cache The cache
     *
     * @return void
     */
    protected function setCache(Cache|array|null $cache): void
    {
        if (is_array($cache)) {
            $this->cache = Cache::fromArray($cache);

            return;
        }

        $this->cache = $cache;
    }
}
