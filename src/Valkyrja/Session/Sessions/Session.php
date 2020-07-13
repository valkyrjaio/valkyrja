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

namespace Valkyrja\Session\Sessions;

use Valkyrja\Container\Container;
use Valkyrja\Session\Adapter;
use Valkyrja\Session\Session as Contract;

/**
 * Class Session.
 *
 * @author Melech Mizrachi
 */
class Session implements Contract
{
    /**
     * The adapters.
     *
     * @var Adapter[]
     */
    protected static array $adapters = [];

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
     * Session constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container      = $container;
        $this->config         = $config;
        $this->defaultAdapter = $config['adapter'];
    }

    /**
     * Get an adapter by name.
     *
     * @param string|null $name The adapter name
     *
     * @return Adapter
     */
    public function getAdapter(string $name = null): Adapter
    {
        $name ??= $this->defaultAdapter;

        return self::$adapters[$name]
            ?? self::$adapters[$name] = $this->container->getSingleton(
                $this->config['adapters'][$name]
            );
    }

    /**
     * Start the session.
     *
     * @return void
     */
    public function start(): void
    {
        $this->getAdapter()->start();
    }

    /**
     * Get the session id.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->getAdapter()->getId();
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
        $this->getAdapter()->setId($id);
    }

    /**
     * Get the session name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->getAdapter()->getName();
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
        $this->getAdapter()->setName($name);
    }

    /**
     * Is a session active?
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->getAdapter()->isActive();
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
        return $this->getAdapter()->has($id);
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
        return $this->getAdapter()->get($id, $default);
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
        $this->getAdapter()->set($id, $value);
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
        return $this->getAdapter()->remove($id);
    }

    /**
     * Get all items in the session.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->getAdapter()->all();
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
        return $this->getAdapter()->csrf($id);
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
        return $this->getAdapter()->validateCsrf($id, $token);
    }

    /**
     * Clear the local session.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->getAdapter()->clear();
    }

    /**
     * Destroy the session.
     *
     * @return void
     */
    public function destroy(): void
    {
        $this->getAdapter()->destroy();
    }
}
