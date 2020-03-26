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

namespace Valkyrja\Config;

use Valkyrja\Config\Configs\AnnotationConfig;
use Valkyrja\Config\Configs\AppConfig;
use Valkyrja\Config\Configs\CacheConfig;
use Valkyrja\Config\Configs\ConsoleConfig;
use Valkyrja\Config\Configs\ContainerConfig;
use Valkyrja\Config\Configs\CryptConfig;
use Valkyrja\Config\Configs\EventConfig;
use Valkyrja\Config\Configs\FilesystemConfig;
use Valkyrja\Config\Configs\LoggingConfig;
use Valkyrja\Config\Configs\MailConfig;
use Valkyrja\Config\Configs\ORMConfig;
use Valkyrja\Config\Configs\PathConfig;
use Valkyrja\Config\Configs\RoutingConfig;
use Valkyrja\Config\Configs\SessionConfig;
use Valkyrja\Config\Configs\ViewConfig;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\ConfigModel as Model;
use Valkyrja\Support\Providers\Provider;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * The annotation module config.
     *
     * @var AnnotationConfig
     */
    public AnnotationConfig $annotation;

    /**
     * The application module config.
     *
     * @var AppConfig
     */
    public AppConfig $app;

    /**
     * The cache module config.
     *
     * @var CacheConfig
     */
    public CacheConfig $cache;

    /**
     * The console module config.
     *
     * @var ConsoleConfig
     */
    public ConsoleConfig $console;

    /**
     * The container module config.
     *
     * @var ContainerConfig
     */
    public ContainerConfig $container;

    /**
     * The crypt module config.
     *
     * @var CryptConfig
     */
    public CryptConfig $crypt;

    /**
     * The ORM module config.
     *
     * @var ORMConfig
     */
    public ORMConfig $orm;

    /**
     * The event module config.
     *
     * @var EventConfig
     */
    public EventConfig $event;

    /**
     * The filesystem module config.
     *
     * @var FilesystemConfig
     */
    public FilesystemConfig $filesystem;

    /**
     * The logging module config.
     *
     * @var LoggingConfig
     */
    public LoggingConfig $logging;

    /**
     * The mail module config.
     *
     * @var MailConfig
     */
    public MailConfig $mail;

    /**
     * The path module config.
     *
     * @var PathConfig
     */
    public PathConfig $path;

    /**
     * The routing module config.
     *
     * @var RoutingConfig
     */
    public RoutingConfig $routing;

    /**
     * The session module config.
     *
     * @var SessionConfig
     */
    public SessionConfig $session;

    /**
     * The view module config.
     *
     * @var ViewConfig
     */
    public ViewConfig $view;

    /**
     * Array of config providers.
     *  NOTE: Provider::deferred() is disregarded.
     *
     * @var Provider[]|string[]
     */
    public array $providers;

    /**
     * The cache file path.
     *
     * @var string
     */
    public string $cacheFilePath;

    /**
     * Whether to use cache.
     *
     * @var bool
     */
    public bool $useCache;

    /**
     * Config constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setAnnotationConfig();
        $this->setAppConfig();
        $this->setCacheConfig();
        $this->setConsoleConfig();
        $this->setContainerConfig();
        $this->setCryptConfig();
        $this->setOrmConfig();
        $this->setEventConfig();
        $this->setFilesystemConfig();
        $this->setLoggingConfig();
        $this->setMailConfig();
        $this->setPathConfig();
        $this->setRoutingConfig();
        $this->setSessionConfig();
        $this->setViewConfig();

        $this->setProviders();
        $this->setCacheFilePath(cachePath('config.php'));
        $this->setUseCache();
    }

    /**
     * Set the annotation module config.
     *
     * @param AnnotationConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setAnnotationConfig(AnnotationConfig $config = null): void
    {
        $this->annotation = $config ?? new AnnotationConfig();
    }

    /**
     * Set the app module config.
     *
     * @param AppConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setAppConfig(AppConfig $config = null): void
    {
        $this->app = $config ?? new AppConfig();
    }

    /**
     * Set the cache module config.
     *
     * @param CacheConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setCacheConfig(CacheConfig $config = null): void
    {
        $this->cache = $config ?? new CacheConfig();
    }

    /**
     * Set the console module config.
     *
     * @param ConsoleConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setConsoleConfig(ConsoleConfig $config = null): void
    {
        $this->console = $config ?? new ConsoleConfig();
    }

    /**
     * Set the container module config.
     *
     * @param ContainerConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setContainerConfig(ContainerConfig $config = null): void
    {
        $this->container = $config ?? new ContainerConfig();
    }

    /**
     * Set the crypt module config.
     *
     * @param CryptConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setCryptConfig(CryptConfig $config = null): void
    {
        $this->crypt = $config ?? new CryptConfig();
    }

    /**
     * Set the ORM module config.
     *
     * @param ORMConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setOrmConfig(ORMConfig $config = null): void
    {
        $this->orm = $config ?? new ORMConfig();
    }

    /**
     * Set the event module config.
     *
     * @param EventConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setEventConfig(EventConfig $config = null): void
    {
        $this->event = $config ?? new EventConfig();
    }

    /**
     * Set the filesystem module config.
     *
     * @param FilesystemConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setFilesystemConfig(FilesystemConfig $config = null): void
    {
        $this->filesystem = $config ?? new FilesystemConfig();
    }

    /**
     * Set the logging module config.
     *
     * @param LoggingConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setLoggingConfig(LoggingConfig $config = null): void
    {
        $this->logging = $config ?? new LoggingConfig();
    }

    /**
     * Set the mail module config.
     *
     * @param MailConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setMailConfig(MailConfig $config = null): void
    {
        $this->mail = $config ?? new MailConfig();
    }

    /**
     * Set the path module config.
     *
     * @param PathConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setPathConfig(PathConfig $config = null): void
    {
        $this->path = $config ?? new PathConfig();
    }

    /**
     * Set the routing module config.
     *
     * @param RoutingConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setRoutingConfig(RoutingConfig $config = null): void
    {
        $this->routing = $config ?? new RoutingConfig();
    }

    /**
     * Set the session module config.
     *
     * @param SessionConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setSessionConfig(SessionConfig $config = null): void
    {
        $this->session = $config ?? new SessionConfig();
    }

    /**
     * Set the view module config.
     *
     * @param ViewConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setViewConfig(ViewConfig $config = null): void
    {
        $this->view = $config ?? new ViewConfig();
    }

    /**
     * Set the config providers.
     *
     * @param array $providers [optional] The providers
     *
     * @return void
     */
    protected function setProviders(array $providers = []): void
    {
        $this->providers = (array) env(EnvKey::CONFIG_PROVIDERS, $providers);
    }

    /**
     * Set the cache file path.
     *
     * @param string $cacheFilePath [optional] The cache file path
     *
     * @return void
     */
    protected function setCacheFilePath(string $cacheFilePath = ''): void
    {
        $this->cacheFilePath = (string) env(EnvKey::CONFIG_CACHE_FILE_PATH, $cacheFilePath);
    }

    /**
     * Set whether to use cache or not.
     *
     * @param bool $useCache [optional] The flag
     *
     * @return void
     */
    protected function setUseCache(bool $useCache = false): void
    {
        $this->useCache = (bool) env(EnvKey::CONFIG_USE_CACHE_FILE, $useCache);
    }
}
