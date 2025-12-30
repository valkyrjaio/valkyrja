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

namespace Valkyrja\Auth\Authenticator;

use Valkyrja\Auth\Constant\SessionId;
use Valkyrja\Auth\Data\AuthenticatedUsers as AuthenticatedUsersData;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsers;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Hasher\Contract\PasswordHasher;
use Valkyrja\Auth\Store\Contract\Store;
use Valkyrja\Session\Manager\Contract\Session;

use function is_string;

/**
 * Class SessionAuthenticator.
 *
 * @author Melech Mizrachi
 *
 * @template U of User
 *
 * @extends AbstractAuthenticator<U>
 */
class SessionAuthenticator extends AbstractAuthenticator
{
    /**
     * @param Store<U>        $store  The store
     * @param class-string<U> $entity The user entity
     */
    public function __construct(
        protected Session $session,
        Store $store,
        PasswordHasher $hasher,
        string $entity,
        AuthenticatedUsers|null $authenticatedUsers = null,
        protected string $sessionId = SessionId::AUTHENTICATED_USERS,
    ) {
        parent::__construct(
            store: $store,
            hasher: $hasher,
            entity: $entity,
            authenticatedUsers: $authenticatedUsers ?? $this->getAuthenticatedUsersFromSession() ?? new AuthenticatedUsersData(),
        );
    }

    /**
     * Attempt to get the authenticated users from the session.
     *
     * @return AuthenticatedUsers|null
     */
    protected function getAuthenticatedUsersFromSession(): AuthenticatedUsers|null
    {
        $sessionSerializedUsers = $this->session->get($this->sessionId);

        if (! is_string($sessionSerializedUsers)) {
            return null;
        }

        $sessionUsers = unserialize(
            $sessionSerializedUsers,
            ['allowed_classes' => true]
        );

        if (! $sessionUsers instanceof AuthenticatedUsers) {
            return null;
        }

        return $sessionUsers;
    }
}
