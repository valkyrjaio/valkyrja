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

use Valkyrja\Annotation\Config as Annotation;
use Valkyrja\Api\Config as Api;
use Valkyrja\Application\Config as App;
use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Asset\Config as Asset;
use Valkyrja\Auth\Config as Auth;
use Valkyrja\Broadcast\Config as Broadcast;
use Valkyrja\Cache\Config as Cache;
use Valkyrja\Client\Config as Client;
use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Config\Support\Provider;
use Valkyrja\Console\Config as Console;
use Valkyrja\Container\Config as Container;
use Valkyrja\Crypt\Config as Crypt;
use Valkyrja\Event\Config as Event;
use Valkyrja\Filesystem\Config as Filesystem;
use Valkyrja\Jwt\Config as Jwt;
use Valkyrja\Log\Config as Log;
use Valkyrja\Mail\Config as Mail;
use Valkyrja\Notification\Config as Notification;
use Valkyrja\Orm\Config\Config as ORM;
use Valkyrja\Path\Config as Path;
use Valkyrja\Routing\Config\Config as Routing;
use Valkyrja\Session\Config\Config as Session;
use Valkyrja\Sms\Config\Config as Sms;
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
     * @inheritDoc
     *
     * @var array<string, string>
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
     * The asset module config.
     *
     * @var Asset
     */
    public Asset $asset;

    /**
     * The auth module config.
     *
     * @var Auth
     */
    public Auth $auth;

    /**
     * The broadcast module config.
     *
     * @var Broadcast
     */
    public Broadcast $broadcast;

    /**
     * The cache module config.
     *
     * @var Cache
     */
    public Cache $cache;

    /**
     * The client module config.
     *
     * @var Client
     */
    public Client $client;

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
     * The Jwt module config.
     *
     * @var Jwt
     */
    public Jwt $jwt;

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
     * The notification module config.
     *
     * @var Notification
     */
    public Notification $notification;

    /**
     * The ORM module config.
     *
     * @var ORM
     */
    public ORM $orm;

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
     * @var Sms
     */
    public Sms $sms;

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
     * @var class-string<Provider>[]
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
