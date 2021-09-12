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

namespace Valkyrja\Console\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\Console\Support\Provider;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     */
    protected static array $envKeys = [
        CKP::HANDLERS                    => EnvKey::CONSOLE_HANDLERS,
        CKP::PROVIDERS                   => EnvKey::CONSOLE_PROVIDERS,
        CKP::DEV_PROVIDERS               => EnvKey::CONSOLE_DEV_PROVIDERS,
        CKP::QUIET                       => EnvKey::CONSOLE_QUIET,
        CKP::USE_ANNOTATIONS             => EnvKey::CONSOLE_USE_ANNOTATIONS,
        CKP::USE_ANNOTATIONS_EXCLUSIVELY => EnvKey::CONSOLE_USE_ANNOTATIONS_EXCLUSIVELY,
        CKP::FILE_PATH                   => EnvKey::CONSOLE_FILE_PATH,
        CKP::CACHE_FILE_PATH             => EnvKey::CONSOLE_CACHE_FILE_PATH,
        CKP::USE_CACHE                   => EnvKey::CONSOLE_USE_CACHE_FILE,
    ];

    /**
     * The annotated command handlers.
     *
     * @var string[]
     */
    public array $handlers;

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
     * Flag to enable quiet console (no output).
     *
     * @var bool
     */
    public bool $quiet;

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
