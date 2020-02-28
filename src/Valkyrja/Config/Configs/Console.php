<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Configs;

use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Annotatable;
use Valkyrja\Config\Models\Cacheable;
use Valkyrja\Config\Models\Config as Model;
use Valkyrja\Console\Enums\Config;

/**
 * Class Console.
 *
 * @author Melech Mizrachi
 */
class Console extends Model
{
    use Annotatable;
    use Cacheable;

    public array $handlers     = [];
    public array $providers    = [];
    public array $devProviders = [];
    public bool  $quiet        = false;

    /**
     * Console constructor.
     */
    public function __construct()
    {
        $this->handlers     = (array) env(EnvKey::CONSOLE_HANDLERS, $this->handlers);
        $this->quiet        = (bool) env(EnvKey::CONSOLE_QUIET, $this->quiet);
        $this->providers    = (array) env(
            EnvKey::CONSOLE_PROVIDERS,
            array_merge(Config::PROVIDERS, $this->providers)
        );
        $this->devProviders = (array) env(
            EnvKey::CONSOLE_DEV_PROVIDERS,
            array_merge(Config::DEV_PROVIDERS, $this->devProviders)
        );

        $this->envUseAnnotationsKey            = EnvKey::CONSOLE_USE_ANNOTATIONS;
        $this->envUseAnnotationsExclusivelyKey = EnvKey::CONSOLE_USE_ANNOTATIONS_EXCLUSIVELY;
        $this->setAnnotationsConfig();

        $this->filePath            = commandsPath('default.php');
        $this->cacheFilePath       = cachePath('commands.php');
        $this->envFilePathKey      = EnvKey::CONSOLE_FILE_PATH;
        $this->envCacheFilePathKey = EnvKey::CONSOLE_CACHE_FILE_PATH;
        $this->envUseCacheKey      = EnvKey::CONSOLE_USE_CACHE_FILE;
        $this->setCacheableConfig();
    }
}
