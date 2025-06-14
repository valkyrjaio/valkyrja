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

use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Http\Routing\Config\Cache;
use Valkyrja\Http\Routing\Constant\ConfigName;
use Valkyrja\Http\Routing\Constant\EnvName;
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
        ConfigName::CONTROLLERS     => EnvName::CONTROLLERS,
        ConfigName::FILE_PATH       => EnvName::FILE_PATH,
        ConfigName::CACHE_FILE_PATH => EnvName::CACHE_FILE_PATH,
        ConfigName::USE_CACHE       => EnvName::USE_CACHE,
    ];

    /**
     * @param class-string[] $controllers A list of attributed controller classes
     */
    public function __construct(
        public array $controllers = [],
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
            $this->filePath = Directory::routesPath('default.php');
        }

        if ($this->cacheFilePath === '') {
            $this->cacheFilePath = Directory::cachePath('routes.php');
        }
    }
}
