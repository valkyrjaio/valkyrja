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

use Valkyrja\Api\Config as Api;
use Valkyrja\Application\Config as App;
use Valkyrja\Asset\Config as Asset;
use Valkyrja\Auth\Config as Auth;
use Valkyrja\Broadcast\Config as Broadcast;
use Valkyrja\Cache\Config as Cache;
use Valkyrja\Cli\Config as Cli;
use Valkyrja\Config\Config;
use Valkyrja\Config\Exception\InvalidArgumentException;
use Valkyrja\Config\Exception\RuntimeException;
use Valkyrja\Container\Config as Container;
use Valkyrja\Crypt\Config as Crypt;
use Valkyrja\Event\Config as Event;
use Valkyrja\Filesystem\Config as Filesystem;
use Valkyrja\Http\Client\Config as Client;
use Valkyrja\Http\Config as Http;
use Valkyrja\Jwt\Config as Jwt;
use Valkyrja\Log\Config as Log;
use Valkyrja\Mail\Config as Mail;
use Valkyrja\Notification\Config as Notification;
use Valkyrja\Orm\Config as Orm;
use Valkyrja\Session\Config as Session;
use Valkyrja\Sms\Config as Sms;
use Valkyrja\View\Config as View;

use function unserialize;

/**
 * Class Valkyrja.
 *
 * @author Melech Mizrachi
 *
 * @property Api          $api
 * @property App          $app
 * @property Asset        $asset
 * @property Auth         $auth
 * @property Broadcast    $broadcast
 * @property Cache        $cache
 * @property Cli          $cli
 * @property Client       $client
 * @property Container    $container
 * @property Crypt        $crypt
 * @property Event        $event
 * @property Filesystem   $filesystem
 * @property Http         $http
 * @property Jwt          $jwt
 * @property Log          $log
 * @property Mail         $mail
 * @property Notification $notification
 * @property Orm          $orm
 * @property Session      $session
 * @property Sms          $sms
 * @property View         $view
 */
class ValkyrjaConfig
{
    /**
     * An array of config classes.
     *
     * @var array<string, Config>
     */
    protected array $map = [];

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
    }

    /**
     * Get a config from a serialized string version of itself.
     *
     * @param non-empty-string $cached The cached config
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
     *
     * @param non-empty-string $name The name of the property
     */
    public function __get(string $name): Config|null
    {
        if (! isset($this->map[$name]) && $this->cached !== null) {
            $cache = $this->cached[$name];

            // Allow all classes, and filter for only Config classes down below since allowed_classes cannot be
            // a class that others extend off of, and we don't want to limit what a cached config class could be
            $config = unserialize($cache, ['allowed_classes' => true]);

            if (! $config instanceof Config) {
                throw new RuntimeException("Invalid cache provided for $name");
            }

            $this->map[$name] = $config;

            return $config;
        }

        return $this->map[$name] ?? null;
    }

    /**
     * Set a property.
     *
     * @param non-empty-string $name The name of the config to add
     */
    public function __set(string $name, Config $value): void
    {
        $this->map[$name] = $value;
    }

    /**
     * Determine if a property isset.
     *
     * @param non-empty-string $name The name of the config to check for
     */
    public function __isset(string $name): bool
    {
        return isset($this->map[$name]);
    }

    /**
     * Cache the config.
     */
    public function cache(): void
    {
        $cache = array_map('serialize', $this->map);

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
     * Set config properties from env after setup.
     *
     * @param class-string $env The env class
     */
    public function setConfigFromEnv(string $env): void
    {
    }
}
