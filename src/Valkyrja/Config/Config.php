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
use Valkyrja\Config\Configs\App;
use Valkyrja\Config\Configs\Console;
use Valkyrja\Config\Configs\Container;
use Valkyrja\Config\Configs\Crypt;
use Valkyrja\Config\Configs\Database;
use Valkyrja\Config\Configs\Event;
use Valkyrja\Config\Configs\Filesystem;
use Valkyrja\Config\Configs\Logging;
use Valkyrja\Config\Configs\Mail;
use Valkyrja\Config\Configs\Path;
use Valkyrja\Config\Configs\Routing;
use Valkyrja\Config\Configs\Session;
use Valkyrja\Config\Configs\View;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Config as Model;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @var Annotation
     */
    public Annotation $annotation;

    /**
     * @var App
     */
    public App $app;

    /**
     * @var Console
     */
    public Console $console;

    /**
     * @var Container
     */
    public Container $container;

    /**
     * @var Crypt
     */
    public Crypt $crypt;

    /**
     * @var Database
     */
    public Database $database;

    /**
     * @var Event
     */
    public Event $event;

    /**
     * @var Filesystem
     */
    public Filesystem $filesystem;

    /**
     * @var Logging
     */
    public Logging $logging;

    /**
     * @var Mail
     */
    public Mail $mail;

    /**
     * @var Path
     */
    public Path $path;

    /**
     * @var Routing
     */
    public Routing $routing;

    /**
     * @var Session
     */
    public Session $session;

    /**
     * @var View
     */
    public View $view;

    /**
     * @var array
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
        $this->annotation = new Annotation();
        $this->app        = new App();
        $this->console    = new Console();
        $this->container  = new Container();
        $this->crypt      = new Crypt();
        $this->database   = new Database();
        $this->event      = new Event();
        $this->filesystem = new Filesystem();
        $this->logging    = new Logging();
        $this->mail       = new Mail();
        $this->path       = new Path();
        $this->routing    = new Routing();
        $this->session    = new Session();
        $this->view       = new View();

        $this->providers     = (array) env(EnvKey::CONFIG_PROVIDERS, $this->providers);
        $this->filePath      = (string) env(EnvKey::CONFIG_FILE_PATH, configPath('config.php'));
        $this->cacheFilePath = (string) env(EnvKey::CONFIG_CACHE_FILE_PATH, cachePath('config.php'));
        $this->useCache      = (bool) env(EnvKey::CONFIG_USE_CACHE_FILE, $this->useCache);
    }
}
