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

use Valkyrja\Config\DataConfig as ParentConfig;
use Valkyrja\Http\Routing\Constant\ConfigName;
use Valkyrja\Http\Routing\Constant\EnvName;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class DataConfig extends ParentConfig
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
     * @param class-string[] $controllers
     */
    public function __construct(
        public array $controllers = [],
        public string $filePath = '',
        public string $cacheFilePath = '',
        public bool $useCache = false
    ) {
    }
}
