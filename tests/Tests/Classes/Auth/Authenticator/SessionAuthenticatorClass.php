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

namespace Valkyrja\Tests\Classes\Auth\Authenticator;

use Valkyrja\Auth\Authenticator\SessionAuthenticator;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsersContract;

/**
 * Test wrapper for SessionAuthenticator to expose protected methods.
 */
final class SessionAuthenticatorClass extends SessionAuthenticator
{
    /**
     * Expose getAuthenticatedUsersFromSession for testing.
     */
    public function testGetAuthenticatedUsersFromSession(): AuthenticatedUsersContract|null
    {
        return $this->getAuthenticatedUsersFromSession();
    }
}
