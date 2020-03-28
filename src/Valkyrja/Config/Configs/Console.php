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
use Valkyrja\Config\Models\Cacheable as Model;
use Valkyrja\Console\Enums\Config;
use Valkyrja\Support\Providers\Provider;

use function Valkyrja\cachePath;
use function Valkyrja\commandsPath;
use function Valkyrja\env;

/**
 * Class Console
 *
 * @author Melech Mizrachi
 */
class Console extends Model
{
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
     * Console constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setHandlers();
        $this->setProviders();
        $this->setDevProviders();
        $this->setQuiet();

        $this->setFilePathEnvKey(EnvKey::CONSOLE_FILE_PATH);
        $this->setCacheFilePathEnvKey(EnvKey::CONSOLE_CACHE_FILE_PATH);
        $this->setUseCacheEnvKey(EnvKey::CONSOLE_USE_CACHE_FILE);
        $this->setUseAnnotationsEnvKey(EnvKey::CONSOLE_USE_ANNOTATIONS);
        $this->setUseAnnotationsExclusivelyEnvKey(EnvKey::CONSOLE_USE_ANNOTATIONS_EXCLUSIVELY);

        $this->setFilePath(commandsPath('default.php'));
        $this->setCacheFilePath(cachePath('commands.php'));
        $this->setUseCache();
        $this->setAnnotationsConfig();
    }

    /**
     * Set the annotated command handlers.
     *
     * @param array $handlers [optional] The annotated command handlers
     *
     * @return void
     */
    protected function setHandlers(array $handlers = []): void
    {
        $this->handlers = (array) env(EnvKey::CONSOLE_HANDLERS, $handlers);
    }

    /**
     * Set the command providers.
     *
     * @param array $providers [optional] The command providers
     *
     * @return void
     */
    protected function setProviders(array $providers = Config::PROVIDERS): void
    {
        $this->providers = (array) env(EnvKey::CONSOLE_PROVIDERS, $providers);
    }

    /**
     * Set the dev command providers.
     *
     * @param array $devProviders [optional] The dev command providers
     *
     * @return void
     */
    protected function setDevProviders(array $devProviders = Config::DEV_PROVIDERS): void
    {
        $this->devProviders = (array) env(EnvKey::CONSOLE_DEV_PROVIDERS, $devProviders);
    }

    /**
     * Set the flag to enable quiet console (no output).
     *
     * @param bool $quiet [optional] The quiet flag
     *
     * @return void
     */
    protected function setQuiet(bool $quiet = false): void
    {
        $this->quiet = (bool) env(EnvKey::CONSOLE_QUIET, $quiet);
    }
}
