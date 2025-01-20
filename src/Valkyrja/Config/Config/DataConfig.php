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
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Config\DataConfig as ParentConfig;
use Valkyrja\Config\Support\Provider;
use Valkyrja\Console\Config as Console;
use Valkyrja\Container\Config as Container;
use Valkyrja\Crypt\Config as Crypt;
use Valkyrja\Event\Config as Event;
use Valkyrja\Filesystem\Config as Filesystem;
use Valkyrja\Http\Routing\Config as Routing;
use Valkyrja\Jwt\Config as Jwt;
use Valkyrja\Log\Config as Log;
use Valkyrja\Mail\Config as Mail;
use Valkyrja\Notification\Config as Notification;
use Valkyrja\Orm\Config\Config as ORM;
use Valkyrja\Path\Config as Path;
use Valkyrja\Session\Config as Session;
use Valkyrja\Sms\Config as Sms;
use Valkyrja\View\Config as View;

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
    protected static array $envKeys = [
        CKP::PROVIDERS       => EnvKey::CONFIG_PROVIDERS,
        CKP::CACHE_FILE_PATH => EnvKey::CONFIG_CACHE_FILE_PATH,
        CKP::USE_CACHE       => EnvKey::CONFIG_USE_CACHE_FILE,
    ];

    /**
     * A map of property to class.
     *
     * @var array<string, class-string>
     */
    protected static array $map = [];

    /**
     * The annotation module config.
     *
     * @var Annotation
     */
    protected Annotation $annotation;

    /**
     * The api module config.
     *
     * @var Api
     */
    protected Api $api;

    /**
     * The application module config.
     *
     * @var App
     */
    protected App $app;

    /**
     * The asset module config.
     *
     * @var Asset
     */
    protected Asset $asset;

    /**
     * The auth module config.
     *
     * @var Auth
     */
    protected Auth $auth;

    /**
     * The broadcast module config.
     *
     * @var Broadcast
     */
    protected Broadcast $broadcast;

    /**
     * The cache module config.
     *
     * @var Cache
     */
    protected Cache $cache;

    /**
     * The client module config.
     *
     * @var Client
     */
    protected Client $client;

    /**
     * The console module config.
     *
     * @var Console
     */
    protected Console $console;

    /**
     * The container module config.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The crypt module config.
     *
     * @var Crypt
     */
    protected Crypt $crypt;

    /**
     * The event module config.
     *
     * @var Event
     */
    protected Event $event;

    /**
     * The filesystem module config.
     *
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * The Jwt module config.
     *
     * @var Jwt
     */
    protected Jwt $jwt;

    /**
     * The logging module config.
     *
     * @var Log
     */
    protected Log $log;

    /**
     * The mail module config.
     *
     * @var Mail
     */
    protected Mail $mail;

    /**
     * The notification module config.
     *
     * @var Notification
     */
    protected Notification $notification;

    /**
     * The ORM module config.
     *
     * @var ORM
     */
    protected ORM $orm;

    /**
     * The path module config.
     *
     * @var Path
     */
    protected Path $path;

    /**
     * The routing module config.
     *
     * @var Routing
     */
    protected Routing $routing;

    /**
     * The session module config.
     *
     * @var Session
     */
    protected Session $session;

    /**
     * The SMS module config.
     *
     * @var Sms
     */
    protected Sms $sms;

    /**
     * The view module config.
     *
     * @var View
     */
    protected View $view;

    /**
     * Array of config providers.
     *  NOTE: Provider::deferred() is disregarded.
     *
     * @var class-string<Provider>[]
     */
    protected array $providers;

    /**
     * The cache file path.
     *
     * @var string
     */
    protected string $cacheFilePath;

    /**
     * Whether to use cache.
     *
     * @var bool
     */
    protected bool $useCache;

    /**
     * @param array<string, mixed>|null $cached
     */
    public function __construct(
        protected array|null $cached = null
    ) {
    }

    /**
     * Get a property.
     */
    public function __get(string $name): mixed
    {
        if (! isset($this->$name) && $this->cached !== null) {
            $cache = $this->cached[$name];

            match ($name) {
                'annotations' => new Annotation\Annotation($cache),
                'api' => new Api\Api($cache),
                'app' => new App\App($cache),
                'asset' => new Asset\Asset($cache),
                'useCache', 'cacheFilePath', 'providers' => $cache,
                default => new static::$map[$name]($cache),
            };
        }

        return $this->$name ?? null;
    }

    /**
     * Set a property.
     */
    public function __set(string $name, mixed $value): void
    {
        $this->$name = $value;
    }

    /**
     * Determine if a property isset.
     */
    public function __isset(string $name): bool
    {
        return isset($this->$name);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        /** @var string $offset */
        return $this->__isset($this->$offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): mixed
    {
        /** @var string $offset */
        return $this->__get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        /** @var string $offset */
        $this->__set($offset, $value);
    }
}
