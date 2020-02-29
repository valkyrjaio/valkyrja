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
     * @var AnnotationConfig
     */
    public AnnotationConfig $annotation;

    /**
     * @var AppConfig
     */
    public AppConfig $app;

    /**
     * @var CacheConfig
     */
    public CacheConfig $cache;

    /**
     * @var ConsoleConfig
     */
    public ConsoleConfig $console;

    /**
     * @var ContainerConfig
     */
    public ContainerConfig $container;

    /**
     * @var CryptConfig
     */
    public CryptConfig $crypt;

    /**
     * @var ORMConfig
     */
    public ORMConfig $orm;

    /**
     * @var EventConfig
     */
    public EventConfig $event;

    /**
     * @var FilesystemConfig
     */
    public FilesystemConfig $filesystem;

    /**
     * @var LoggingConfig
     */
    public LoggingConfig $logging;

    /**
     * @var MailConfig
     */
    public MailConfig $mail;

    /**
     * @var PathConfig
     */
    public PathConfig $path;

    /**
     * @var RoutingConfig
     */
    public RoutingConfig $routing;

    /**
     * @var SessionConfig
     */
    public SessionConfig $session;

    /**
     * @var ViewConfig
     */
    public ViewConfig $view;

    /**
     * @var Provider[]
     */
    public array $providers = [];

    /**
     * @var string
     */
    public string $filePath = '';

    /**
     * @var string
     */
    public string $cacheFilePath = '';

    /**
     * @var bool
     */
    public bool $useCache = false;

    /**
     * Config constructor.
     */
    public function __construct()
    {
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

        $this->providers     = (array) env(EnvKey::CONFIG_PROVIDERS, $this->providers);
        $this->filePath      = (string) env(EnvKey::CONFIG_FILE_PATH, configPath('config.php'));
        $this->cacheFilePath = (string) env(EnvKey::CONFIG_CACHE_FILE_PATH, cachePath('config.php'));
        $this->useCache      = (bool) env(EnvKey::CONFIG_USE_CACHE_FILE, $this->useCache);
    }

    /**
     * Set annotation config.
     *
     * @return void
     */
    protected function setAnnotationConfig(): void
    {
        $this->annotation = new AnnotationConfig();
    }

    /**
     * Set app config.
     *
     * @return void
     */
    protected function setAppConfig(): void
    {
        $this->app = new AppConfig();
    }

    /**
     * Set cache config.
     *
     * @return void
     */
    protected function setCacheConfig(): void
    {
        $this->cache = new CacheConfig();
    }

    /**
     * Set console config.
     *
     * @return void
     */
    protected function setConsoleConfig(): void
    {
        $this->console = new ConsoleConfig();
    }

    /**
     * Set container config.
     *
     * @return void
     */
    protected function setContainerConfig(): void
    {
        $this->container = new ContainerConfig();
    }

    /**
     * Set crypt config.
     *
     * @return void
     */
    protected function setCryptConfig(): void
    {
        $this->crypt = new CryptConfig();
    }

    /**
     * Set ORM config.
     *
     * @return void
     */
    protected function setOrmConfig(): void
    {
        $this->orm = new ORMConfig();
    }

    /**
     * Set event config.
     *
     * @return void
     */
    protected function setEventConfig(): void
    {
        $this->event = new EventConfig();
    }

    /**
     * Set filesystem config.
     *
     * @return void
     */
    protected function setFilesystemConfig(): void
    {
        $this->filesystem = new FilesystemConfig();
    }

    /**
     * Set logging config.
     *
     * @return void
     */
    protected function setLoggingConfig(): void
    {
        $this->logging = new LoggingConfig();
    }

    /**
     * Set mail config.
     *
     * @return void
     */
    protected function setMailConfig(): void
    {
        $this->mail = new MailConfig();
    }

    /**
     * Set path config.
     *
     * @return void
     */
    protected function setPathConfig(): void
    {
        $this->path = new PathConfig();
    }

    /**
     * Set routing config.
     *
     * @return void
     */
    protected function setRoutingConfig(): void
    {
        $this->routing = new RoutingConfig();
    }

    /**
     * Set session config.
     *
     * @return void
     */
    protected function setSessionConfig(): void
    {
        $this->session = new SessionConfig();
    }

    /**
     * Set view config.
     *
     * @return void
     */
    protected function setViewConfig(): void
    {
        $this->view = new ViewConfig();
    }
}
