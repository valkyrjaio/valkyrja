<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Session;

use Exception;
use Valkyrja\Application;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\Session\Exceptions\InvalidSessionId;
use Valkyrja\Session\Exceptions\SessionStartFailure;
use Valkyrja\Support\Providers\Provides;

/**
 * Class Session.
 *
 * @author Melech Mizrachi
 */
class NativeSession implements Session
{
    use Provides;

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The session data.
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Whether the session has been started.
     *
     * @var bool
     */
    protected bool $started;

    /**
     * Session constructor.
     *
     * @param Application $application The application
     * @param string      $sessionId   [optional] The session id
     * @param string      $sessionName [optional] The session name
     *
     * @throws InvalidSessionId
     * @throws SessionStartFailure
     */
    public function __construct(Application $application, string $sessionId = null, string $sessionName = null)
    {
        $this->app = $application;

        $sessionId   = $sessionId ?? $this->app->config()[ConfigKeyPart::SESSION][ConfigKeyPart::ID];
        $sessionName = $sessionName ?? $this->app->config()[ConfigKeyPart::SESSION][ConfigKeyPart::NAME];

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
    public function id(): string
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
    public function name(): string
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
     * @param string $id The item id
     *
     * @return mixed
     */
    public function get(string $id)
    {
        return $this->has($id) ? $this->data[$id] : null;
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

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Session::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @throws SessionStartFailure
     * @throws InvalidSessionId
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(Session::class, new static($app));
    }
}
