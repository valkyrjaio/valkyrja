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

namespace Valkyrja\Session\Managers;

use Valkyrja\Container\Container;
use Valkyrja\Session\Adapter;
use Valkyrja\Session\CacheAdapter;
use Valkyrja\Session\Driver;
use Valkyrja\Session\LogAdapter;
use Valkyrja\Session\Session as Contract;
use Valkyrja\Support\Type\Cls;

/**
 * Class Sessions.
 *
 * @author Melech Mizrachi
 */
class Session implements Contract
{
    /**
     * The drivers.
     *
     * @var Driver[]
     */
    protected static array $drivers = [];

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The default adapter.
     *
     * @var string
     */
    protected string $defaultAdapter;

    /**
     * The default driver.
     *
     * @var string
     */
    protected string $defaultDriver;

    /**
     * The default session.
     *
     * @var string
     */
    protected string $defaultSession;

    /**
     * The sessions.
     *
     * @var array
     */
    protected array $sessions;

    /**
     * Session constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container      = $container;
        $this->config         = $config;
        $this->defaultSession = $config['default'];
        $this->defaultAdapter = $config['adapter'];
        $this->defaultDriver  = $config['driver'];
        $this->sessions       = $config['sessions'];
    }

    /**
     * @inheritDoc
     */
    public function useSession(string $name = null, string $adapter = null): Driver
    {
        // The session to use
        $name ??= $this->defaultSession;
        // The session to use
        $session = $this->sessions[$name];
        // The adapter to use
        $driver ??= $session['driver'] ?? $this->defaultDriver;
        // The adapter to use
        $adapter ??= $session['adapter'] ?? $this->defaultAdapter;
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$drivers[$cacheKey]
            ?? self::$drivers[$cacheKey] = $this->createDriver($driver, $adapter, $session);
    }

    /**
     * @inheritDoc
     */
    public function start(): void
    {
        $this->useSession()->start();
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return $this->useSession()->getId();
    }

    /**
     * @inheritDoc
     */
    public function setId(string $id): void
    {
        $this->useSession()->setId($id);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->useSession()->getName();
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): void
    {
        $this->useSession()->setName($name);
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        return $this->useSession()->isActive();
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return $this->useSession()->has($id);
    }

    /**
     * @inheritDoc
     */
    public function get(string $id, $default = null)
    {
        return $this->useSession()->get($id, $default);
    }

    /**
     * @inheritDoc
     */
    public function set(string $id, $value): void
    {
        $this->useSession()->set($id, $value);
    }

    /**
     * @inheritDoc
     */
    public function remove(string $id): bool
    {
        return $this->useSession()->remove($id);
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->useSession()->all();
    }

    /**
     * @inheritDoc
     */
    public function generateCsrfToken(string $id): string
    {
        return $this->useSession()->generateCsrfToken($id);
    }

    /**
     * @inheritDoc
     */
    public function validateCsrfToken(string $id, string $token): void
    {
        $this->useSession()->validateCsrfToken($id, $token);
    }

    /**
     * @inheritDoc
     */
    public function isCsrfTokenValid(string $id, string $token): bool
    {
        return $this->useSession()->isCsrfTokenValid($id, $token);
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        $this->useSession()->clear();
    }

    /**
     * @inheritDoc
     */
    public function destroy(): void
    {
        $this->useSession()->destroy();
    }

    /**
     * Get an driver by name.
     *
     * @param string $name    The driver
     * @param string $adapter The adapter
     * @param array  $config  The config
     *
     * @return Driver
     */
    protected function createDriver(string $name, string $adapter, array $config): Driver
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Driver::class,
            [
                $this->createAdapter($adapter, $config),
            ]
        );
    }

    /**
     * Get an adapter by name.
     *
     * @param string $name   The adapter
     * @param array  $config The config
     *
     * @return Adapter
     */
    protected function createAdapter(string $name, array $config): Adapter
    {
        $defaultClass = Adapter::class;

        if (Cls::inherits($name, CacheAdapter::class)) {
            $defaultClass = CacheAdapter::class;
        } elseif (Cls::inherits($name, LogAdapter::class)) {
            $defaultClass = LogAdapter::class;
        }

        return Cls::getDefaultableService(
            $this->container,
            $name,
            $defaultClass,
            [
                $config,
            ]
        );
    }
}
