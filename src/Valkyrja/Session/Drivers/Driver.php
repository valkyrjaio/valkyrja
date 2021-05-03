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

namespace Valkyrja\Session\Drivers;

use Valkyrja\Session\Adapter;
use Valkyrja\Session\Driver as Contract;
use Valkyrja\Session\Exceptions\InvalidCsrfToken;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 */
class Driver implements Contract
{
    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * Driver constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Start the session.
     *
     * @return void
     */
    public function start(): void
    {
        $this->adapter->start();
    }

    /**
     * Get the session id.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->adapter->getId();
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
        $this->adapter->setId($id);
    }

    /**
     * Get the session name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->adapter->getName();
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
        $this->adapter->setName($name);
    }

    /**
     * Is a session active?
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->adapter->isActive();
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
        return $this->adapter->has($id);
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
        return $this->adapter->get($id, $default);
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
        $this->adapter->set($id, $value);
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
        return $this->adapter->remove($id);
    }

    /**
     * Get all items in the session.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->adapter->all();
    }

    /**
     * Generate a csrf token for a unique token id.
     *
     * @param string $id The csrf unique token id
     *
     * @return string
     */
    public function generateCsrfToken(string $id): string
    {
        return $this->adapter->generateCsrfToken($id);
    }

    /**
     * Validate a csrf token.
     *
     * @param string $id    The csrf unique token id
     * @param string $token The token to validate
     *
     * @throws InvalidCsrfToken
     *
     * @return void
     */
    public function validateCsrfToken(string $id, string $token): void
    {
        $this->adapter->validateCsrfToken($id, $token);
    }

    /**
     * Determine if a csrf token is valid.
     *
     * @param string $id    The csrf unique token id
     * @param string $token The token to validate
     *
     * @return bool
     */
    public function isCsrfTokenValid(string $id, string $token): bool
    {
        return $this->adapter->isCsrfTokenValid($id, $token);
    }

    /**
     * Clear the local session.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->adapter->clear();
    }

    /**
     * Destroy the session.
     *
     * @return void
     */
    public function destroy(): void
    {
        $this->adapter->destroy();
    }
}
