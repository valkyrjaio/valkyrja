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

namespace Valkyrja\Session\Manager;

use Override;
use Valkyrja\Session\Data\CookieParams;
use Valkyrja\Session\Manager\Abstract\Session;
use Valkyrja\Session\Throwable\Exception\InvalidSessionId;
use Valkyrja\Session\Throwable\Exception\SessionIdFailure;
use Valkyrja\Session\Throwable\Exception\SessionNameFailure;
use Valkyrja\Session\Throwable\Exception\SessionStartFailure;

use function headers_sent;
use function session_id;
use function session_name;
use function session_start;
use function session_status;
use function session_unset;

use const PHP_SESSION_ACTIVE;

class PhpSession extends Session
{
    /**
     * @param non-empty-string|null $sessionId   The session id
     * @param non-empty-string|null $sessionName The session id
     */
    public function __construct(
        protected CookieParams $cookieParams,
        string|null $sessionId = null,
        string|null $sessionName = null,
    ) {
        parent::__construct(
            sessionId: $sessionId,
            sessionName: $sessionName
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function start(): void
    {
        // If the session is already active
        if ($this->isActive() || $this->headersSent()) {
            // No need to reactivate
            return;
        }

        // Set the session cookie parameters
        session_set_cookie_params([
            'path'     => $this->cookieParams->path,
            'domain'   => $this->cookieParams->domain,
            'lifetime' => $this->cookieParams->lifetime,
            'secure'   => $this->cookieParams->secure,
            'httponly' => $this->cookieParams->httpOnly,
            'samesite' => $this->cookieParams->sameSite->value,
        ]);

        // If the session failed to start
        if (! $this->sessionStart()) {
            // Throw a new exception
            throw new SessionStartFailure('The session failed to start');
        }

        // Set the data
        $this->data = &$_SESSION;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getId(): string
    {
        $sessionId = $this->sessionId();

        if ($sessionId === false) {
            throw new SessionIdFailure('Retrieval of session id failed');
        }

        return $sessionId;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setId(string $id): void
    {
        parent::setId($id);

        $this->sessionId();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getName(): string
    {
        $sessionName = $this->sessionName();

        if ($sessionName === false) {
            throw new SessionNameFailure('Retrieval of session id failed');
        }

        return $sessionName;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setName(string $name): void
    {
        parent::setName($name);

        $this->sessionName($name);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function clear(): void
    {
        parent::clear();

        session_unset();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function destroy(): void
    {
        parent::destroy();

        session_unset();
    }

    /**
     * Get or set the session id.
     */
    protected function sessionId(): string|false
    {
        return session_id();
    }

    /**
     * Get or set the session name.
     */
    protected function sessionName(string|null $name = null): string|false
    {
        return session_name($name);
    }

    /**
     * Start the session.
     */
    protected function sessionStart(): bool
    {
        return session_start();
    }

    /**
     * Determine if the headers have been sent.
     */
    protected function headersSent(): bool
    {
        return headers_sent();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function validateId(string $id): void
    {
        if (! preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $id)) {
            throw new InvalidSessionId(
                "The session id, '$id', is invalid! "
                . 'Session id can only contain alpha numeric characters, dashes, commas, '
                . 'and be at least 1 character in length but up to 128 characters long.'
            );
        }
    }
}
