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

use Valkyrja\Annotation\Config\Config as Annotation;
use Valkyrja\Api\Config\Config as Api;
use Valkyrja\Application\Config\Config as App;
use Valkyrja\Auth\Config\Config as Auth;
use Valkyrja\Cache\Config\Config as Cache;
use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\Console\Config\Config as Console;
use Valkyrja\Container\Config\Config as Container;
use Valkyrja\Crypt\Config\Config as Crypt;
use Valkyrja\Event\Config\Config as Event;
use Valkyrja\Filesystem\Config\Config as Filesystem;
use Valkyrja\Log\Config\Config as Log;
use Valkyrja\Mail\Config\Config as Mail;
use Valkyrja\ORM\Config\Config as ORM;
use Valkyrja\Path\Config\Config as Path;
use Valkyrja\Routing\Config\Config as Routing;
use Valkyrja\Session\Config\Config as Session;
use Valkyrja\SMS\Config\Config as SMS;
use Valkyrja\Support\Provider\Provider;
use Valkyrja\Validation\Config\Config as Validation;
use Valkyrja\View\Config\Config as View;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * Array of properties in the model.
     *
     * @var array
     */
    protected static array $modelProperties = [
        CKP::PROVIDERS,
        CKP::CACHE_FILE_PATH,
        CKP::USE_CACHE,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::PROVIDERS       => EnvKey::CONFIG_PROVIDERS,
        CKP::CACHE_FILE_PATH => EnvKey::CONFIG_CACHE_FILE_PATH,
        CKP::USE_CACHE       => EnvKey::CONFIG_USE_CACHE_FILE,
    ];

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
     * The SMS module config.
     *
     * @var SMS
     */
    public SMS $sms;

    /**
     * The validation module config.
     *
     * @var Validation
     */
    public Validation $validation;

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
}
