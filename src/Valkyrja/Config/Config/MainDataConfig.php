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
use Valkyrja\Orm\Config\Config as ORM;
use Valkyrja\Path\Config as Path;
use Valkyrja\Session\Config as Session;
use Valkyrja\Sms\Config as Sms;
use Valkyrja\View\Config as View;

use function is_string;
use function unserialize;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 *
 * @implements ArrayAccess<string, DataConfig>
 */
class MainDataConfig implements ArrayAccess
{
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
     * The client module config.
     *
     * @var Config
     */
    protected Config $config;

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
     * @param array<string, array<string, mixed>|string>|null $cached
     */
    public function __construct(
        protected array|null $cached = null
    ) {
    }

    /**
     * Get a property.
     */
    public function __get(string $name): DataConfig|null
    {
        if (! isset($this->$name) && $this->cached !== null) {
            $cache = $this->cached[$name];

            if (is_string($cache)) {
                $config = unserialize($cache);

                if (! $config instanceof DataConfig) {
                    throw new RuntimeException("Invalid cache provided for $name");
                }

                return $config;
            }

            match ($name) {
                'annotations' => new Annotation\Annotation($cache),
                'api' => new Api\Api($cache),
                'app' => new App\App($cache),
                'asset' => new Asset\Asset($cache),
                default => throw new RuntimeException("$name has not been implemented"),
            };
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
}
