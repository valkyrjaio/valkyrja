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
use Valkyrja\Session\Exception\InvalidSessionId;
use Valkyrja\Session\Exception\SessionIdFailure;
use Valkyrja\Session\Exception\SessionNameFailure;
use Valkyrja\Session\Exception\SessionStartFailure;

use function headers_sent;
use function preg_match;
use function session_id;
use function session_name;
use function session_start;
use function session_status;
use function session_unset;

use const PHP_SESSION_ACTIVE;

/**
 * Class PhpSession.
 *
 * @author Melech Mizrachi
 */
class PhpSession extends NullSession
{
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
        if ($this->isActive() || headers_sent()) {
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
        if (! session_start()) {
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
        $sessionId = session_id();

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
        if (! preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $id)) {
            throw new InvalidSessionId(
                "The session id, '$id', is invalid! "
                . 'Session id can only contain alpha numeric characters, dashes, commas, '
                . 'and be at least 1 character in length but up to 128 characters long.'
            );
        }

        session_id($id);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getName(): string
    {
        $sessionName = session_name();

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
        session_name($name);
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
}
