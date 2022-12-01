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

namespace Valkyrja\Container\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Model\Enums\CastType;

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
        CKP::SETUP_FACADE     => EnvKey::CONTAINER_SETUP_FACADE,
        CKP::USE_ANNOTATIONS  => EnvKey::CONTAINER_USE_ANNOTATIONS,
        CKP::FILE_PATH        => EnvKey::CONTAINER_FILE_PATH,
        CKP::CACHE_FILE_PATH  => EnvKey::CONTAINER_CACHE_FILE_PATH,
        CKP::USE_CACHE        => EnvKey::CONTAINER_USE_CACHE_FILE,
    ];

    /**
     * @inheritDoc
     */
    protected static array $castings = [
        CKP::CACHE => [CastType::model, Cache::class],
    ];

    /**
     * The annotated service aliases.
     *
     * @var string[]
     */
    public array $aliases;

    /**
     * The annotated services.
     *
     * @var string[]
     */
    public array $services;

    /**
     * The annotated context services.
     *
     * @var string[]
     */
    public array $contextServices;

    /**
     * The command providers.
     *
     * @var Provider[]|string[]
     */
    public array $providers;

    /**
     * The dev command providers.
     *
     * @var Provider[]|string[]
     */
    public array $devProviders;

    /**
     * Whether to setup Facades.
     *
     * @var bool
     */
    public bool $setupFacade;

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
}
