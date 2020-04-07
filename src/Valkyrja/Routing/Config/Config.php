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

namespace Valkyrja\Routing\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * Array of properties in the model.
     *
     * @var array
     */
    protected static array $modelProperties = [
        CKP::MIDDLEWARE,
        CKP::MIDDLEWARE_GROUPS,
        CKP::CONTROLLERS,
        CKP::USE_TRAILING_SLASH,
        CKP::USE_ABSOLUTE_URLS,
        CKP::USE_ANNOTATIONS,
        CKP::USE_ANNOTATIONS_EXCLUSIVELY,
        CKP::FILE_PATH,
        CKP::CACHE_FILE_PATH,
        CKP::USE_CACHE,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::MIDDLEWARE                  => EnvKey::ROUTING_MIDDLEWARE,
        CKP::MIDDLEWARE_GROUPS           => EnvKey::ROUTING_MIDDLEWARE_GROUPS,
        CKP::CONTROLLERS                 => EnvKey::ROUTING_CONTROLLERS,
        CKP::USE_TRAILING_SLASH          => EnvKey::ROUTING_USE_TRAILING_SLASH,
        CKP::USE_ABSOLUTE_URLS           => EnvKey::ROUTING_USE_ABSOLUTE_URLS,
        CKP::USE_ANNOTATIONS             => EnvKey::ROUTING_USE_ANNOTATIONS,
        CKP::USE_ANNOTATIONS_EXCLUSIVELY => EnvKey::ROUTING_USE_ANNOTATIONS_EXCLUSIVELY,
        CKP::FILE_PATH                   => EnvKey::ROUTING_FILE_PATH,
        CKP::CACHE_FILE_PATH             => EnvKey::ROUTING_CACHE_FILE_PATH,
        CKP::USE_CACHE                   => EnvKey::ROUTING_USE_CACHE_FILE,
    ];

    /**
     * The middleware.
     *
     * @var string[]
     */
    public array $middleware;

    /**
     * The middleware groups.
     *
     * @var string[]
     */
    public array $middlewareGroups;

    /**
     * The annotated controllers.
     *
     * @var string[]
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
     * The flag to use annotations exclusively (forgoing filePath).
     *
     * @var bool
     */
    public bool $useAnnotationsExclusively;

    /**
     * The cache from a Cacheable::getCacheable().
     *
     * @var Model|null
     */
    public ?Model $cache = null;

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
}
