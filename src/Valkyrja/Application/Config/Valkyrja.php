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

namespace Valkyrja\Application\Config;

use ArrayAccess;
use Valkyrja\Annotation\Config as Annotation;
use Valkyrja\Api\Config as Api;
use Valkyrja\Application\Config as App;
use Valkyrja\Asset\Config as Asset;
use Valkyrja\Auth\Config as Auth;
use Valkyrja\Broadcast\Config as Broadcast;
use Valkyrja\Cache\Config as Cache;
use Valkyrja\Cli\Interaction\Config as CliInteraction;
use Valkyrja\Cli\Middleware\Config as CliMiddleware;
use Valkyrja\Cli\Routing\Config as CliRouting;
use Valkyrja\Cli\Server\Config as CliServer;
use Valkyrja\Client\Config as Client;
use Valkyrja\Config\Config;
use Valkyrja\Config\Config\Config as ConfigConfig;
use Valkyrja\Config\Exception\InvalidArgumentException;
use Valkyrja\Config\Exception\RuntimeException;
use Valkyrja\Container\Config as Container;
use Valkyrja\Crypt\Config as Crypt;
use Valkyrja\Event\Config as Event;
use Valkyrja\Filesystem\Config as Filesystem;
use Valkyrja\Http\Middleware\Config as HttpMiddleware;
use Valkyrja\Http\Routing\Config as HttpRouting;
use Valkyrja\Http\Server\Config as HttpServer;
use Valkyrja\Jwt\Config as Jwt;
use Valkyrja\Log\Config as Log;
use Valkyrja\Mail\Config as Mail;
use Valkyrja\Notification\Config as Notification;
use Valkyrja\Orm\Config as Orm;
use Valkyrja\Session\Config as Session;
use Valkyrja\Sms\Config as Sms;
use Valkyrja\View\Config as View;

use function is_string;
use function unserialize;

/**
 * Class Valkyrja.
 *
 * @author Melech Mizrachi
 *
 * @implements ArrayAccess<string, Config>
 *
 * @property Annotation     $annotation
 * @property Api            $api
 * @property App            $app
 * @property Asset          $asset
 * @property Auth           $auth
 * @property Broadcast      $broadcast
 * @property Cache          $cache
 * @property CliInteraction $cliInteraction
 * @property CliMiddleware  $cliMiddleware
 * @property CliRouting     $cliRouting
 * @property CliServer      $cliServer
 * @property Client         $client
 * @property ConfigConfig   $config
 * @property Container      $container
 * @property Crypt          $crypt
 * @property Event          $event
 * @property Filesystem     $filesystem
 * @property HttpMiddleware $httpMiddleware
 * @property HttpRouting    $httpRouting
 * @property HttpServer     $httpServer
 * @property Jwt            $jwt
 * @property Log            $log
 * @property Mail           $mail
 * @property Notification   $notification
 * @property Orm            $orm
 * @property Session        $session
 * @property Sms            $sms
 * @property View           $view
 */
class Valkyrja implements ArrayAccess
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
     * The cli interaction component config.
     *
     * @var CliInteraction
     */
    protected CliInteraction $cliInteraction;

    /**
     * The cli middleware component config.
     *
     * @var CliMiddleware
     */
    protected CliMiddleware $cliMiddleware;

    /**
     * The cli routing component config.
     *
     * @var CliRouting
     */
    protected CliRouting $cliRouting;

    /**
     * The cli server component config.
     *
     * @var CliServer
     */
    protected CliServer $cliServer;

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
     * @var HttpMiddleware
     */
    protected HttpMiddleware $httpMiddleware;

    /**
     * The http routing component config.
     *
     * @var HttpRouting
     */
    protected HttpRouting $httpRouting;

    /**
     * The http server component config.
     *
     * @var HttpServer
     */
    protected HttpServer $httpServer;

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
    public static function fromSerializedString(string $cached): static
    {
        $config = unserialize($cached, ['allowed_classes' => true]);

        if (! $config instanceof static) {
            throw new RuntimeException('Invalid cached config provided');
        }

        /** @psalm-suppress MixedReturnStatement It's a static object, not sure why Psalm is confused */
        return $config;
    }

    /**
     * Get a property.
     */
    public function __get(string $name): Config|null
    {
        if (! isset($this->$name) && $this->cached !== null) {
            $cache = $this->cached[$name];

            // Allow all classes, and filter for only Config classes down below since allowed_classes cannot be
            // a class that others extend off of, and we don't want to limit what a cached config class could be
            $config = unserialize($cache, ['allowed_classes' => true]);

            if (! $config instanceof Config) {
                throw new RuntimeException("Invalid cache provided for $name");
            }

            $this->$name = $config;

            return $config;
        }

        /** @psalm-suppress MixedReturnStatement Can be all sorts of config objects */
        return $this->$name ?? null;
    }

    /**
     * Set a property.
     */
    public function __set(string $name, Config $value): void
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
    public function offsetGet($offset): Config|null
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
            if ($item instanceof Config) {
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
        $this->cliInteraction = CliInteraction::fromEnv($env);
        $this->cliMiddleware  = CliMiddleware::fromEnv($env);
        $this->cliRouting     = CliRouting::fromEnv($env);
        $this->cliServer      = CliServer::fromEnv($env);
        $this->client         = Client::fromEnv($env);
        $this->config         = ConfigConfig::fromEnv($env);
        $this->container      = Container::fromEnv($env);
        $this->crypt          = Crypt::fromEnv($env);
        $this->event          = Event::fromEnv($env);
        $this->filesystem     = Filesystem::fromEnv($env);
        $this->httpMiddleware = HttpMiddleware::fromEnv($env);
        $this->httpRouting    = HttpRouting::fromEnv($env);
        $this->httpServer     = HttpServer::fromEnv($env);
        $this->jwt            = Jwt::fromEnv($env);
        $this->log            = Log::fromEnv($env);
        $this->mail           = Mail::fromEnv($env);
        $this->notification   = Notification::fromEnv($env);
        $this->orm            = Orm::fromEnv($env);
        $this->session        = Session::fromEnv($env);
        $this->sms            = Sms::fromEnv($env);
        $this->view           = View::fromEnv($env);
    }
}
