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
use Valkyrja\Application\Config as App;
use Valkyrja\Asset\Config as Asset;
use Valkyrja\Auth\Config as Auth;
use Valkyrja\Broadcast\Config as Broadcast;
use Valkyrja\Cache\Config as Cache;
use Valkyrja\Client\Config as Client;
use Valkyrja\Config\Config\DataConfig as ConfigConfig;
use Valkyrja\Config\DataConfig;
use Valkyrja\Console\Config as Console;
use Valkyrja\Container\Config as Container;
use Valkyrja\Crypt\Config as Crypt;
use Valkyrja\Event\Config as Event;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Filesystem\Config as Filesystem;
use Valkyrja\Http\Routing\Config as Routing;
use Valkyrja\Jwt\Config as Jwt;
use Valkyrja\Log\Config as Log;
use Valkyrja\Mail\Config as Mail;
use Valkyrja\Notification\Config as Notification;
use Valkyrja\Orm\Config as Orm;
use Valkyrja\Path\Config as Path;
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
     * The routing component config.
     *
     * @var Routing
     */
    protected Routing $routing;

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
     * @param array<string, array<string, mixed>|string>|null $cached
     */
    public function __construct(
        protected ?array $cached = null
    ) {
        if ($cached === null) {
            $this->annotation   = new Annotation\Annotation();
            $this->api          = new Api\Api();
            $this->asset        = new Asset\Asset();
            $this->auth         = new Auth\Auth();
            $this->broadcast    = new Broadcast\Broadcast();
            $this->cache        = new Cache\Cache();
            $this->client       = new Client\Client();
            $this->config       = new ConfigConfig();
            $this->console      = new Console\Console();
            $this->container    = new Container\Container();
            $this->crypt        = new Crypt\Crypt();
            $this->event        = new Event\Event();
            $this->filesystem   = new Filesystem\Filesystem();
            $this->jwt          = new Jwt\Jwt();
            $this->log          = new Log\Log();
            $this->mail         = new Mail\Mail();
            $this->notification = new Notification\Notification();
            $this->orm          = new Orm\Orm();
            $this->path         = new Path\Path();
            $this->routing      = new Routing\Routing();
            $this->session      = new Session\Session();
            $this->sms          = new Sms\Sms();
            $this->view         = new View\View();
        }
    }

    /**
     * Get a property.
     */
    public function __get(string $name): ?DataConfig
    {
        if (! isset($this->$name) && $this->cached !== null) {
            $cache = $this->cached[$name];

            if (is_string($cache)) {
                $config = unserialize($cache);

                if (! $config instanceof DataConfig) {
                    throw new RuntimeException("Invalid cache provided for $name");
                }

                $this->$name = $config;

                return $config;
            }
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
    public function offsetGet($offset): ?DataConfig
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
}
