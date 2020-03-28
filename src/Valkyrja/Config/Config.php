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

use Valkyrja\Config\Configs\Annotation;
use Valkyrja\Config\Configs\Api;
use Valkyrja\Config\Configs\App;
use Valkyrja\Config\Configs\Auth;
use Valkyrja\Config\Configs\Cache;
use Valkyrja\Config\Configs\Console;
use Valkyrja\Config\Configs\Container;
use Valkyrja\Config\Configs\Crypt;
use Valkyrja\Config\Configs\Event;
use Valkyrja\Config\Configs\Filesystem;
use Valkyrja\Config\Configs\Log;
use Valkyrja\Config\Configs\Mail;
use Valkyrja\Config\Configs\ORM;
use Valkyrja\Config\Configs\Path;
use Valkyrja\Config\Configs\Routing;
use Valkyrja\Config\Configs\Session;
use Valkyrja\Config\Configs\View;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Model;
use Valkyrja\Support\Providers\Provider;

use function env;

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
     * @var Annotation
     */
    public Annotation $annotation;

    /**
     * The api module config.
     *
     * @var Api
     */
    public Api $api;

    /**
     * The application module config.
     *
     * @var App
     */
    public App $app;

    /**
     * The auth module config.
     *
     * @var Auth
     */
    public Auth $auth;

    /**
     * The cache module config.
     *
     * @var Cache
     */
    public Cache $cache;

    /**
     * The console module config.
     *
     * @var Console
     */
    public Console $console;

    /**
     * The container module config.
     *
     * @var Container
     */
    public Container $container;

    /**
     * The crypt module config.
     *
     * @var Crypt
     */
    public Crypt $crypt;

    /**
     * The ORM module config.
     *
     * @var ORM
     */
    public ORM $orm;

    /**
     * The event module config.
     *
     * @var Event
     */
    public Event $event;

    /**
     * The filesystem module config.
     *
     * @var Filesystem
     */
    public Filesystem $filesystem;

    /**
     * The logging module config.
     *
     * @var Log
     */
    public Log $log;

    /**
     * The mail module config.
     *
     * @var Mail
     */
    public Mail $mail;

    /**
     * The path module config.
     *
     * @var Path
     */
    public Path $path;

    /**
     * The routing module config.
     *
     * @var Routing
     */
    public Routing $routing;

    /**
     * The session module config.
     *
     * @var Session
     */
    public Session $session;

    /**
     * The view module config.
     *
     * @var View
     */
    public View $view;

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

        $this->setAnnotation();
        $this->setApi();
        $this->setApp();
        $this->setAuth();
        $this->setCache();
        $this->setConsole();
        $this->setContainer();
        $this->setCrypt();
        $this->setOrm();
        $this->setEvent();
        $this->setFilesystem();
        $this->setLog();
        $this->setMail();
        $this->setPath();
        $this->setRouting();
        $this->setSession();
        $this->setView();

        $this->setProviders();
        $this->setCacheFilePath(cachePath('config.php'));
        $this->setUseCache();
    }

    /**
     * Set the annotation module config.
     *
     * @param Annotation|null $config [optional] The config
     *
     * @return void
     */
    protected function setAnnotation(Annotation $config = null): void
    {
        $this->annotation = $config ?? new Annotation();
    }

    /**
     * Set the api module config.
     *
     * @param Api|null $config [optional] The config
     *
     * @return void
     */
    protected function setApi(Api $config = null): void
    {
        $this->api = $config ?? new Api();
    }

    /**
     * Set the app module config.
     *
     * @param App|null $config [optional] The config
     *
     * @return void
     */
    protected function setApp(App $config = null): void
    {
        $this->app = $config ?? new App();
    }

    /**
     * Set the auth module config.
     *
     * @param Auth|null $config [optional] The config
     *
     * @return void
     */
    protected function setAuth(Auth $config = null): void
    {
        $this->auth = $config ?? new Auth();
    }

    /**
     * Set the cache module config.
     *
     * @param Cache|null $config [optional] The config
     *
     * @return void
     */
    protected function setCache(Cache $config = null): void
    {
        $this->cache = $config ?? new Cache();
    }

    /**
     * Set the console module config.
     *
     * @param Console|null $config [optional] The config
     *
     * @return void
     */
    protected function setConsole(Console $config = null): void
    {
        $this->console = $config ?? new Console();
    }

    /**
     * Set the container module config.
     *
     * @param Container|null $config [optional] The config
     *
     * @return void
     */
    protected function setContainer(Container $config = null): void
    {
        $this->container = $config ?? new Container();
    }

    /**
     * Set the crypt module config.
     *
     * @param Crypt|null $config [optional] The config
     *
     * @return void
     */
    protected function setCrypt(Crypt $config = null): void
    {
        $this->crypt = $config ?? new Crypt();
    }

    /**
     * Set the ORM module config.
     *
     * @param ORM|null $config [optional] The config
     *
     * @return void
     */
    protected function setOrm(ORM $config = null): void
    {
        $this->orm = $config ?? new ORM();
    }

    /**
     * Set the event module config.
     *
     * @param Event|null $config [optional] The config
     *
     * @return void
     */
    protected function setEvent(Event $config = null): void
    {
        $this->event = $config ?? new Event();
    }

    /**
     * Set the filesystem module config.
     *
     * @param Filesystem|null $config [optional] The config
     *
     * @return void
     */
    protected function setFilesystem(Filesystem $config = null): void
    {
        $this->filesystem = $config ?? new Filesystem();
    }

    /**
     * Set the logging module config.
     *
     * @param Log|null $config [optional] The config
     *
     * @return void
     */
    protected function setLog(Log $config = null): void
    {
        $this->log = $config ?? new Log();
    }

    /**
     * Set the mail module config.
     *
     * @param Mail|null $config [optional] The config
     *
     * @return void
     */
    protected function setMail(Mail $config = null): void
    {
        $this->mail = $config ?? new Mail();
    }

    /**
     * Set the path module config.
     *
     * @param Path|null $config [optional] The config
     *
     * @return void
     */
    protected function setPath(Path $config = null): void
    {
        $this->path = $config ?? new Path();
    }

    /**
     * Set the routing module config.
     *
     * @param Routing|null $config [optional] The config
     *
     * @return void
     */
    protected function setRouting(Routing $config = null): void
    {
        $this->routing = $config ?? new Routing();
    }

    /**
     * Set the session module config.
     *
     * @param Session|null $config [optional] The config
     *
     * @return void
     */
    protected function setSession(Session $config = null): void
    {
        $this->session = $config ?? new Session();
    }

    /**
     * Set the view module config.
     *
     * @param View|null $config [optional] The config
     *
     * @return void
     */
    protected function setView(View $config = null): void
    {
        $this->view = $config ?? new View();
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
