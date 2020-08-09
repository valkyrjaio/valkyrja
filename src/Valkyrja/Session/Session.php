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

namespace Valkyrja\Session;

/**
 * Interface Session.
 *
 * @author Melech Mizrachi
 */
interface Session
{
    /**
     * Use a session by name.
     *
     * @param string|null $name    The session name
     * @param string|null $adapter The adapter
     *
     * @return Driver
     */
    public function useSession(string $name = null, string $adapter = null): Driver;

    /**
     * Start the session.
     *
     * @return void
     */
    public function start(): void;

    /**
     * Get the session id.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Set the session id.
     *
     * @param string $id The session id
     *
     * @return void
     */
    public function setId(string $id): void;

    /**
     * Get the session name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set the session name.
     *
     * @param string $name The session name
     *
     * @return void
     */
    public function setName(string $name): void;

    /**
     * Is a session active?
     *
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Determine whether the session has an item.
     *
     * @param string $id The item id
     *
     * @return bool
     */
    public function has(string $id): bool;

    /**
     * Get an item from the session.
     *
     * @param string     $id      The item id
     * @param mixed|null $default The default value
     *
     * @return mixed
     */
    public function get(string $id, $default = null);

    /**
     * Set an item into the session.
     *
     * @param string $id    The id
     * @param string $value The value
     *
     * @return void
     */
    public function set(string $id, string $value): void;

    /**
     * Remove a session item.
     *
     * @param string $id The item id
     *
     * @return bool
     */
    public function remove(string $id): bool;

    /**
     * Get all items in the session.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Get a csrf token for a unique token id.
     *
     * @param string $id The csrf unique token id
     *
     * @return string
     */
    public function csrf(string $id): string;

    /**
     * Validate a csrf token.
     *
     * @param string $id    The csrf unique token id
     * @param string $token The token to validate
     *
     * @return bool
     */
    public function validateCsrf(string $id, string $token): bool;

    /**
     * Clear the local session.
     *
     * @return void
     */
    public function clear(): void;

    /**
     * Destroy the session.
     *
     * @return void
     */
    public function destroy(): void;
}
