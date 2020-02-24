<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Session\Sessions;

use Valkyrja\Application\Application;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Crypt\Exceptions\CryptException;
use Valkyrja\Http\Request;
use Valkyrja\Session\Exceptions\SessionStartFailure;

/**
 * Class CookieSession.
 *
 * @author Melech Mizrachi
 */
class CookieSession extends Session
{
    /**
     * The crypt.
     *
     * @var Crypt
     */
    protected Crypt $crypt;

    /**
     * The request
     *
     * @var Request
     */
    protected Request $request;

    /**
     * CookieSession constructor.
     *
     * @param Application $application
     * @param string|null $sessionId
     * @param string|null $sessionName
     */
    public function __construct(Application $application, string $sessionId = null, string $sessionName = null)
    {
        parent::__construct($application, $sessionId, $sessionName);

        $this->crypt   = $this->app->crypt();
        $this->request = $this->app->request();
    }

    /**
     * Start the session.
     *
     * @throws SessionStartFailure
     * @throws CryptException
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

        $dataString = $this->request->getCookieParam($this->id());

        // Set the data
        $this->data = $dataString ? $this->crypt->decryptArray($dataString) : [];
    }

    /**
     * Set an item into the session.
     *
     * @param string $id    The id
     * @param string $value The value
     *
     * @throws CryptException
     *
     * @return void
     */
    public function set(string $id, string $value): void
    {
        $this->data[$id] = $value;

        $this->updateCookieSession();
    }

    /**
     * Remove a session item.
     *
     * @param string $id The item id
     *
     * @throws CryptException
     *
     * @return bool
     */
    public function remove(string $id): bool
    {
        if (! $this->has($id)) {
            return false;
        }

        unset($this->data[$id]);

        $this->updateCookieSession();

        return true;
    }

    /**
     * Clear the local session.
     *
     * @throws CryptException
     *
     * @return void
     */
    public function clear(): void
    {
        parent::clear();

        $this->updateCookieSession();
    }

    /**
     * Destroy the session.
     *
     * @throws CryptException
     *
     * @return void
     */
    public function destroy(): void
    {
        parent::destroy();

        $this->updateCookieSession();
    }

    /**
     * Update the cache session.
     *
     * @throws CryptException
     *
     * @return void
     */
    protected function updateCookieSession(): void
    {
        setcookie(
            $this->id(),
            $this->crypt->encryptArray($this->data),
            0,
            '/',
            null,
            false,
            true
        );
    }
}
