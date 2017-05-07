<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Session;

use Valkyrja\Contracts\Application;

/**
 * Interface Session
 *
 * @package Valkyrja\Contracts\Session
 *
 * @author  Melech Mizrachi
 */
interface Session
{
    /**
     * Session constructor.
     *
     * @param \Valkyrja\Contracts\Application $application The application
     * @param string                          $sessionId   [optional] The session id
     * @param string                          $sessionName [optional] The session name
     *
     * @throws \Valkyrja\Session\Exceptions\InvalidSessionId
     */
    public function __construct(Application $application, string $sessionId = null, string $sessionName = null);

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
    public function id(): string;

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
    public function name(): string;

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
     * @param string $id The item id
     *
     * @return mixed
     */
    public function get(string $id);

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
