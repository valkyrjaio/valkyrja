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

namespace Valkyrja\Event;

use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Event\Config\Cache;
use Valkyrja\Event\Constant\ConfigName;
use Valkyrja\Event\Constant\EnvName;
use Valkyrja\Support\Directory;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::LISTENER_CLASSES => EnvName::LISTENER_CLASSES,
        ConfigName::FILE_PATH        => EnvName::FILE_PATH,
        ConfigName::CACHE_FILE_PATH  => EnvName::CACHE_FILE_PATH,
        ConfigName::USE_CACHE        => EnvName::USE_CACHE,
    ];

    /**
     * @param class-string[] $listenerClasses
     */
    public function __construct(
        public array $listenerClasses = [],
        public string $filePath = '',
        public string $cacheFilePath = '',
        public bool $useCache = false,
        public Cache|null $cache = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesBeforeSettingFromEnv(string $env): void
    {
        if ($this->filePath === '') {
            $this->filePath = Directory::eventsPath('default.php');
        }

        if ($this->cacheFilePath === '') {
            $this->filePath = Directory::cachePath('events.php');
        }
    }
}
