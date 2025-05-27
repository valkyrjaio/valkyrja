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

use ArrayAccess;
use Valkyrja\Annotation\Config as Annotation;
use Valkyrja\Api\Config as Api;
use Valkyrja\Application\DataConfig as App;
use Valkyrja\Asset\Config as Asset;
use Valkyrja\Auth\Config as Auth;
use Valkyrja\Broadcast\Config as Broadcast;
use Valkyrja\Cache\Config as Cache;
use Valkyrja\Client\Config as Client;
use Valkyrja\Config\Config\DataConfig as ConfigConfig;
use Valkyrja\Config\DataConfig;
use Valkyrja\Config\Exception\InvalidArgumentException;
use Valkyrja\Config\Exception\RuntimeException;
use Valkyrja\Console\DataConfig as Console;
use Valkyrja\Container\Config as Container;
use Valkyrja\Crypt\Config as Crypt;
use Valkyrja\Event\DataConfig as Event;
use Valkyrja\Filesystem\Config as Filesystem;
use Valkyrja\Http\Middleware\DataConfig as Middleware;
use Valkyrja\Http\Routing\DataConfig as Routing;
use Valkyrja\Http\Server\DataConfig as Server;
use Valkyrja\Jwt\Config as Jwt;
use Valkyrja\Log\Config as Log;
use Valkyrja\Mail\Config as Mail;
use Valkyrja\Notification\DataConfig as Notification;
use Valkyrja\Orm\DataConfig as Orm;
use Valkyrja\Path\DataConfig as Path;
use Valkyrja\Session\Config as Session;
use Valkyrja\Sms\Config as Sms;
use Valkyrja\View\Config as View;

use function is_string;
use function unserialize;

/**
 * Class ValkyrjaConfig.
 *
 * @author Melech Mizrachi
 *
 * @implements ArrayAccess<string, DataConfig>
 *
 * @property Annotation   $annotation
 * @property Api          $api
 * @property App          $app
 * @property Asset        $asset
 * @property Auth         $auth
 * @property Broadcast    $broadcast
 * @property Cache        $cache
 * @property Client       $client
 * @property ConfigConfig $config
 * @property Console      $console
 * @property Container    $container
 * @property Crypt        $crypt
 * @property Event        $event
 * @property Filesystem   $filesystem
 * @property Middleware   $httpMiddleware
 * @property Routing      $httpRouting
 * @property Server       $httpServer
 * @property Jwt          $jwt
 * @property Log          $log
 * @property Mail         $mail
 * @property Notification $notification
 * @property Orm          $orm
 * @property Path         $path
 * @property Session      $session
 * @property Sms          $sms
 * @property View         $view
 */
class ValkyrjaDataConfig implements ArrayAccess
{
    /**
     * A map of property to class.
     *
     * @var array<string, class-string>
     */
    protected static array $map = [];

    /**
     * The annotation component config.
     *
     * @var Annotation
     */
    protected Annotation $annotation;

    /**
     * The api component config.
     *
     * @var Api
     */
    protected Api $api;

    /**
     * The application component config.
     *
     * @var App
     */
    protected App $app;

    /**
     * The asset component config.
     *
     * @var Asset
     */
    protected Asset $asset;

    /**
     * The auth component config.
     *
     * @var Auth
     */
    protected Auth $auth;

    /**
     * The broadcast component config.
     *
     * @var Broadcast
     */
    protected Broadcast $broadcast;

    /**
     * The cache component config.
     *
     * @var Cache
     */
    protected Cache $cache;

    /**
     * The client component config.
     *
     * @var Client
     */
    protected Client $client;

    /**
     * The client component config.
     *
     * @var ConfigConfig
     */
    protected ConfigConfig $config;

    /**
     * The console component config.
     *
     * @var Console
     */
    protected Console $console;

    /**
     * The container component config.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The crypt component config.
     *
     * @var Crypt
     */
    protected Crypt $crypt;

    /**
     * The event component config.
     *
     * @var Event
     */
    protected Event $event;

    /**
     * The filesystem component config.
     *
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * The http middleware component config.
     *
     * @var Middleware
     */
    protected Middleware $httpMiddleware;

    /**
     * The http routing component config.
     *
     * @var Routing
     */
    protected Routing $httpRouting;

    /**
     * The http server component config.
     *
     * @var Server
     */
    protected Server $httpServer;

    /**
     * The Jwt component config.
     *
     * @var Jwt
     */
    protected Jwt $jwt;

    /**
     * The logging component config.
     *
     * @var Log
     */
    protected Log $log;

    /**
     * The mail component config.
     *
     * @var Mail
     */
    protected Mail $mail;

    /**
     * The notification component config.
     *
     * @var Notification
     */
    protected Notification $notification;

    /**
     * The ORM component config.
     *
     * @var Orm
     */
    protected Orm $orm;

    /**
     * The path component config.
     *
     * @var Path
     */
    protected Path $path;

    /**
     * The session component config.
     *
     * @var Session
     */
    protected Session $session;

    /**
     * The SMS component config.
     *
     * @var Sms
     */
    protected Sms $sms;

    /**
     * The view component config.
     *
     * @var View
     */
    protected View $view;

    /**
     * @param array<string, string>|null $cached The cached config
     * @param class-string|null          $env    The env class
     */
    public function __construct(
        protected array|null $cached = null,
        protected string|null $env = null
    ) {
        if ($env === null && $cached === null) {
            throw new InvalidArgumentException('One of env or cached is required');
        }

        if ($cached === null) {
            $this->setConfigFromEnv($env);
        }
    }

    /**
     * Get a config from a serialized string version of itself.
     */
    public static function fromSerializesString(string $cached): static
    {
        return unserialize(
            $cached,
            [
                'allowed_classes' => [static::class],
            ]
        );
    }

    /**
     * Get a property.
     */
    public function __get(string $name): DataConfig|null
    {
        if (! isset($this->$name) && $this->cached !== null) {
            $cache = $this->cached[$name];

            // Allow all classes, and filter for only Config classes down below since allowed_classes cannot be
            // a class that others extend off of, and we don't want to limit what a cached config class could be
            $config = unserialize($cache, ['allowed_classes' => true]);

            if (! $config instanceof DataConfig) {
                throw new RuntimeException("Invalid cache provided for $name");
            }

            $this->$name = $config;

            return $config;
        }

        return $this->$name ?? null;
    }

    /**
     * Set a property.
     */
    public function __set(string $name, DataConfig $value): void
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
        return $this->__isset($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): DataConfig|null
    {
        return $this->__get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        if (! is_string($offset)) {
            throw new RuntimeException('Offset must be a string');
        }

        $this->__set($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new RuntimeException("Cannot remove offset with name $offset from config.");
    }

    /**
     * Cache the config.
     */
    public function cache(): void
    {
        $cache = [];

        foreach (get_object_vars($this) as $key => $item) {
            if ($item instanceof DataConfig) {
                $cache[$key] = serialize($item);
            }
        }

        $this->cached = $cache;
    }

    /**
     * Get a cached version of this cache.
     */
    public function getCached(): static
    {
        $this->cache();

        $static = new static(cached: $this->cached);

        $this->cached = null;

        return $static;
    }

    /**
     * Get the config as a serialized string.
     */
    public function asSerializedString(): string
    {
        return serialize($this->getCached());
    }

    /**
     * @param class-string $env The env class
     */
    protected function setConfigFromEnv(string $env): void
    {
        $this->annotation     = Annotation::fromEnv($env);
        $this->api            = Api::fromEnv($env);
        $this->app            = App::fromEnv($env);
        $this->asset          = Asset::fromEnv($env);
        $this->auth           = Auth::fromEnv($env);
        $this->broadcast      = Broadcast::fromEnv($env);
        $this->cache          = Cache::fromEnv($env);
        $this->client         = Client::fromEnv($env);
        $this->config         = ConfigConfig::fromEnv($env);
        $this->console        = Console::fromEnv($env);
        $this->container      = Container::fromEnv($env);
        $this->crypt          = Crypt::fromEnv($env);
        $this->event          = Event::fromEnv($env);
        $this->filesystem     = Filesystem::fromEnv($env);
        $this->httpMiddleware = Middleware::fromEnv($env);
        $this->httpRouting    = Routing::fromEnv($env);
        $this->httpServer     = Server::fromEnv($env);
        $this->jwt            = Jwt::fromEnv($env);
        $this->log            = Log::fromEnv($env);
        $this->mail           = Mail::fromEnv($env);
        $this->notification   = Notification::fromEnv($env);
        $this->orm            = Orm::fromEnv($env);
        $this->path           = Path::fromEnv($env);
        $this->session        = Session::fromEnv($env);
        $this->sms            = Sms::fromEnv($env);
        $this->view           = View::fromEnv($env);
    }
}
