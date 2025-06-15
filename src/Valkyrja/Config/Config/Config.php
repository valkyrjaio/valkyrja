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

namespace Valkyrja\Config\Config;

use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Config\Constant\ConfigName;
use Valkyrja\Config\Constant\EnvName;
use Valkyrja\Config\Support\Provider;
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
        ConfigName::PROVIDERS       => EnvName::PROVIDERS,
        ConfigName::CACHE_FILE_PATH => EnvName::CACHE_FILE_PATH,
        ConfigName::USE_CACHE       => EnvName::USE_CACHE,
    ];

    /**
     * @param class-string<Provider>[] $providers
     * @param string                   $cacheFilePath
     * @param bool                     $useCache
     */
    public function __construct(
        public array $providers = [],
        public string $cacheFilePath = '',
        public bool $useCache = false
    ) {
    }

    protected function setPropertiesBeforeSettingFromEnv(string $env): void
    {
        if ($this->cacheFilePath === '') {
            $this->cacheFilePath = Directory::cachePath('config.php');
        }
    }
}
