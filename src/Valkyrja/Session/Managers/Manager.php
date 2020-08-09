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
use Valkyrja\Session\Manager as Contract;
use Valkyrja\Session\Session;

/**
 * Class Manager.
 *
 * @author Melech Mizrachi
 */
class Manager implements Contract
{
    /**
     * The sessions.
     *
     * @var Session[]
     */
    protected static array $sessions = [];

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
    protected array $sessionsConfig;

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
        $this->sessionsConfig = $config['sessions'];
    }

    /**
     * Use a session by name.
     *
     * @param string|null $name    The session name
     * @param string|null $adapter The adapter
     *
     * @return Session
     */
    public function useSession(string $name = null, string $adapter = null): Session
    {
        $name ??= $this->defaultSession;
        $adapter ??= $this->sessionsConfig[$name]['adapter'];
        $cacheName = $name . $adapter;

        return self::$sessions[$cacheName]
            ?? self::$sessions[$cacheName] = $this->container->get(
                $this->sessionsConfig[$name]['driver'],
                [
                    $name,
                    $adapter,
                ]
            );
    }

    /**
     * Start the session.
     *
     * @return void
     */
    public function start(): void
    {
        $this->useSession()->start();
    }

    /**
     * Get the session id.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->useSession()->getId();
    }

    /**
     * Set the session id.
     *
     * @param string $id
     *
     * @return void
     */
    public function setId(string $id): void
    {
        $this->useSession()->setId($id);
    }

    /**
     * Get the session name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->useSession()->getName();
    }

    /**
     * Set the session name.
     *
     * @param string $name The session name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->useSession()->setName($name);
    }

    /**
     * Is a session active?
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->useSession()->isActive();
    }

    /**
     * Determine whether the session has an item.
     *
     * @param string $id The item id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return $this->useSession()->has($id);
    }

    /**
     * Get an item from the session.
     *
     * @param string     $id      The item id
     * @param mixed|null $default The default value
     *
     * @return mixed
     */
    public function get(string $id, $default = null)
    {
        return $this->useSession()->get($id, $default);
    }

    /**
     * Set an item into the session.
     *
     * @param string $id    The id
     * @param string $value The value
     *
     * @return void
     */
    public function set(string $id, string $value): void
    {
        $this->useSession()->set($id, $value);
    }

    /**
     * Remove a session item.
     *
     * @param string $id The item id
     *
     * @return bool
     */
    public function remove(string $id): bool
    {
        return $this->useSession()->remove($id);
    }

    /**
     * Get all items in the session.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->useSession()->all();
    }

    /**
     * Get a csrf token for a unique token id.
     *
     * @param string $id The csrf unique token id
     *
     * @return string
     */
    public function csrf(string $id): string
    {
        return $this->useSession()->csrf($id);
    }

    /**
     * Validate a csrf token.
     *
     * @param string $id    The csrf unique token id
     * @param string $token The token to validate
     *
     * @return bool
     */
    public function validateCsrf(string $id, string $token): bool
    {
        return $this->useSession()->validateCsrf($id, $token);
    }

    /**
     * Clear the local session.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->useSession()->clear();
    }

    /**
     * Destroy the session.
     *
     * @return void
     */
    public function destroy(): void
    {
        $this->useSession()->destroy();
    }
}
