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

use Valkyrja\Session\Exceptions\InvalidSessionId;
use Valkyrja\Session\Exceptions\SessionStartFailure;

use function headers_sent;
use function preg_match;
use function session_id;
use function session_name;
use function session_start;
use function session_status;
use function session_unset;

use const PHP_SESSION_ACTIVE;

/**
 * Class PHPAdapter.
 *
 * @author Melech Mizrachi
 */
class PHPAdapter extends NullAdapter
{
    /**
     * @inheritDoc
     */
    public function start(): void
    {
        // If the session is already active
        if ($this->isActive() || headers_sent()) {
            // No need to reactivate
            return;
        }

        // Set the session cookie parameters
        session_set_cookie_params($this->config['cookieParams'] ?? []);

        // If the session failed to start
        if (! session_start()) {
            // Throw a new exception
            throw new SessionStartFailure('The session failed to start!');
        }

        // Set the data
        $this->data = &$_SESSION;
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return session_id();
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function getName(): string
    {
        return session_name();
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): void
    {
        session_name($name);
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        return PHP_SESSION_ACTIVE === session_status();
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        parent::clear();

        session_unset();
    }

    /**
     * @inheritDoc
     */
    public function destroy(): void
    {
        parent::destroy();

        session_unset();
    }
}
