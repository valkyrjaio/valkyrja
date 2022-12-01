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

namespace Valkyrja\Event\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
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
        CKP::LISTENERS       => EnvKey::EVENT_LISTENERS,
        CKP::USE_ANNOTATIONS => EnvKey::EVENT_USE_ANNOTATIONS,
        CKP::FILE_PATH       => EnvKey::EVENT_FILE_PATH,
        CKP::CACHE_FILE_PATH => EnvKey::EVENT_CACHE_FILE_PATH,
        CKP::USE_CACHE       => EnvKey::EVENT_USE_CACHE_FILE,
    ];

    /**
     * @inheritDoc
     */
    protected static array $castings = [
        CKP::CACHE => [CastType::model, Cache::class],
    ];

    /**
     * The annotated listeners.
     *
     * @var string[]
     */
    public array $listeners;

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
