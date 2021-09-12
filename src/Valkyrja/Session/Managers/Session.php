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
use Valkyrja\Session\Driver;
use Valkyrja\Session\Session as Contract;

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
    protected static array $driversCache = [];

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
     * The adapters.
     *
     * @var array
     */
    protected array $adapters;

    /**
     * The drivers config.
     *
     * @var array
     */
    protected array $drivers;

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
        $this->adapters       = $config['adapters'];
        $this->drivers        = $config['drivers'];
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
        $adapter ??= $session['adapter'];
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$driversCache[$cacheKey]
            ?? self::$driversCache[$cacheKey] = $this->container->get(
                $this->drivers[$session['driver']],
                [
                    $session,
                    $this->adapters[$adapter],
                ]
            );
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
}
