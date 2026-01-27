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

use Valkyrja\Auth\Authenticator\Abstract\Authenticator;
use Valkyrja\Auth\Constant\SessionId;
use Valkyrja\Auth\Data\AuthenticatedUsers;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsersContract;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Hasher\Contract\PasswordHasherContract;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Session\Manager\Contract\SessionContract;

use function is_string;

/**
 * @template U of UserContract
 *
 * @extends Authenticator<U>
 */
class SessionAuthenticator extends Authenticator
{
    /**
     * @param StoreContract<U> $store  The store
     * @param class-string<U>  $entity The user entity
     */
    public function __construct(
        protected SessionContract $session,
        StoreContract $store,
        PasswordHasherContract $hasher,
        string $entity,
        AuthenticatedUsersContract|null $authenticatedUsers = null,
        protected string $sessionItemId = SessionId::AUTHENTICATED_USERS,
    ) {
        parent::__construct(
            store: $store,
            hasher: $hasher,
            entity: $entity,
            authenticatedUsers: $authenticatedUsers
                ?? $this->getAuthenticatedUsersFromSession()
                ?? new AuthenticatedUsers(),
        );
    }

    /**
     * Attempt to get the authenticated users from the session.
     */
    protected function getAuthenticatedUsersFromSession(): AuthenticatedUsersContract|null
    {
        /** @var mixed $sessionSerializedUsers */
        $sessionSerializedUsers = $this->session->get($this->sessionItemId);

        if (! is_string($sessionSerializedUsers)) {
            return null;
        }

        /** @var mixed $sessionUsers */
        $sessionUsers = unserialize(
            $sessionSerializedUsers,
            ['allowed_classes' => true]
        );

        if (! $sessionUsers instanceof AuthenticatedUsersContract) {
            return null;
        }

        return $sessionUsers;
    }
}
