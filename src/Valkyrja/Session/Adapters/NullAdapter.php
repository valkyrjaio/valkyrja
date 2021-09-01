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

namespace Valkyrja\Session\Adapters;

use Exception;
use Valkyrja\Session\Adapter as Contract;
use Valkyrja\Session\Exceptions\InvalidCsrfToken;
use Valkyrja\Session\Exceptions\InvalidSessionId;
use Valkyrja\Session\Exceptions\SessionStartFailure;

use function bin2hex;
use function hash_equals;
use function is_string;
use function preg_match;
use function random_bytes;

/**
 * Class NullAdapter.
 *
 * @author Melech Mizrachi
 */
class NullAdapter implements Contract
{
    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The session id.
     *
     * @var string
     */
    protected string $id;

    /**
     * The session name.
     *
     * @var string
     */
    protected string $name;

    /**
     * The session data.
     *
     * @var array
     */
    protected array $data = [];

    /**
     * NullAdapter constructor.
     *
     * @param array       $config      The config
     * @param string|null $sessionId   [optional] The session id
     * @param string|null $sessionName [optional] The session name
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
    }

    /**
     * Get the session id.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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

        $this->id = $id;
    }

    /**
     * Get the session name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
        $this->name = $name;
    }

    /**
     * Is a session active?
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return true;
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
     * @param mixed  $value The value
     *
     * @return void
     */
    public function set(string $id, $value): void
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
     * Generate a csrf token for a unique token id.
     *
     * @param string $id The csrf unique token id
     *
     * @throws Exception
     *
     * @return string
     */
    public function generateCsrfToken(string $id): string
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
     * @throws InvalidCsrfToken
     *
     * @return void
     */
    public function validateCsrfToken(string $id, string $token): void
    {
        if (! $this->isCsrfTokenValid($id, $token)) {
            throw new InvalidCsrfToken("CSRF token id: `{$id}` has invalid token of `{$token}` provided");
        }
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
        if (! $this->has($id)) {
            return false;
        }

        $sessionToken = $this->get($id);

        if (is_string($sessionToken) && hash_equals($token, $sessionToken)) {
            $this->remove($id);

            return true;
        }

        return false;
    }

    /**
     * Clear the local session.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->data = [];
    }

    /**
     * Destroy the session.
     *
     * @return void
     */
    public function destroy(): void
    {
        $this->data = [];
    }
}
