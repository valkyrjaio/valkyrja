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

namespace Valkyrja\Http\Routing;

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Http\Routing\Config\Cache;

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
        CKP::MIDDLEWARE         => EnvKey::ROUTING_MIDDLEWARE,
        CKP::MIDDLEWARE_GROUPS  => EnvKey::ROUTING_MIDDLEWARE_GROUPS,
        CKP::CONTROLLERS        => EnvKey::ROUTING_CONTROLLERS,
        CKP::USE_TRAILING_SLASH => EnvKey::ROUTING_USE_TRAILING_SLASH,
        CKP::USE_ABSOLUTE_URLS  => EnvKey::ROUTING_USE_ABSOLUTE_URLS,
        CKP::USE_ANNOTATIONS    => EnvKey::ROUTING_USE_ANNOTATIONS,
        CKP::FILE_PATH          => EnvKey::ROUTING_FILE_PATH,
        CKP::CACHE_FILE_PATH    => EnvKey::ROUTING_CACHE_FILE_PATH,
        CKP::USE_CACHE          => EnvKey::ROUTING_USE_CACHE_FILE,
    ];

    /**
     * The middleware.
     *
     * @var class-string[]
     */
    public array $middleware;

    /**
     * The middleware groups.
     *
     * @var class-string[][]
     */
    public array $middlewareGroups;

    /**
     * The annotated controllers.
     *
     * @var class-string[]
     */
    public array $controllers;

    /**
     * The flag to enable trailing slashes for all urls.
     *
     * @var bool
     */
    public bool $useTrailingSlash;

    /**
     * The flag to enable absolute urls.
     *
     * @var bool
     */
    public bool $useAbsoluteUrls;

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
    public ?Cache $cache = null;

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
