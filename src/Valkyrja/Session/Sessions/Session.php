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

use Exception;
use Valkyrja\Session\Exceptions\InvalidSessionId;
use Valkyrja\Session\Exceptions\SessionStartFailure;
use Valkyrja\Session\Session as Contract;

use function bin2hex;
use function hash_equals;
use function headers_sent;
use function is_string;
use function preg_match;
use function random_bytes;
use function session_id;
use function session_name;
use function session_start;
use function session_status;
use function session_unset;

use const PHP_SESSION_ACTIVE;

/**
 * Class Session.
 *
 * @author Melech Mizrachi
 */
class Session implements Contract
{
    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The session data.
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Session constructor.
     *
     * @param array  $config      The config
     * @param string $sessionId   [optional] The session id
     * @param string $sessionName [optional] The session name
     *
     * @throws InvalidSessionId
     * @throws SessionStartFailure
     */
    public function __construct(array $config, string $sessionId = null, string $sessionName = null)
    {
        $this->config = $config;

        $sessionId   = $sessionId ?? $config['id'];
        $sessionName = $sessionName ?? $config['name'];

        // If a session id is provided
        if (null !== $sessionId) {
            // Set the id
            $this->setId($sessionId);
        }

        // If a session name is provided
        if (null !== $sessionName) {
            // Set the name
            $this->setName($sessionName);
        }

        // Start the session
        $this->start();
    }

    /**
     * Start the session.
     *
     * @throws SessionStartFailure
     *
     * @return void
     */
    public function start(): void
    {
        // If the session is already active
        if ($this->isActive() || headers_sent()) {
            // No need to reactivate
            return;
        }

        // If the session failed to start
        if (! session_start()) {
            // Throw a new exception
            throw new SessionStartFailure('The session failed to start!');
        }

        // Set the data
        $this->data = &$_SESSION;
    }

    /**
     * Get the session id.
     *
     * @return string
     */
    public function getId(): string
    {
        return session_id();
    }

    /**
     * Set the session id.
     *
     * @param string $id The session id
     *
     * @throws InvalidSessionId
     *
     * @return void
     */
    public function setId(string $id): void
    {
        if (! preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $id)) {
            throw new InvalidSessionId(
                "The session id, '{$id}', is invalid! "
                . 'Session id can only contain alpha numeric characters, dashes, commas, '
                . 'and be at least 1 character in length but up to 128 characters long.'
            );
        }

        session_id($id);
    }

    /**
     * Get the session name.
     *
     * @return string
     */
    public function getName(): string
    {
        return session_name();
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
        session_name($name);
    }

    /**
     * Is a session active?
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return PHP_SESSION_ACTIVE === session_status();
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
        return isset($this->data[$id]);
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
        return $this->data[$id] ?? $default;
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
        $this->data[$id] = $value;
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
        if (! $this->has($id)) {
            return false;
        }

        unset($this->data[$id]);

        return true;
    }

    /**
     * Get all items in the session.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Get a csrf token for a unique token id.
     *
     * @param string $id The csrf unique token id
     *
     * @throws Exception
     *
     * @return string
     */
    public function csrf(string $id): string
    {
        $token = bin2hex(random_bytes(64));

        $this->set($id, $token);

        return $token;
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
        if (! $this->has($id)) {
            return false;
        }

        $sessionToken = $this->get($id);

        if (! is_string($sessionToken)) {
            return false;
        }

        $this->remove($id);

        return hash_equals($token, $sessionToken);
    }

    /**
     * Clear the local session.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->data = [];

        session_unset();
    }

    /**
     * Destroy the session.
     *
     * @return void
     */
    public function destroy(): void
    {
        $this->data = [];

        session_unset();
    }
}
