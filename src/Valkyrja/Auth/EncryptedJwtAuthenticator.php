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

namespace Valkyrja\Auth;

use Override;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsers;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Exception\InvalidAuthenticationException;
use Valkyrja\Auth\Hasher\Contract\PasswordHasher;
use Valkyrja\Auth\Store\Contract\Store;
use Valkyrja\Crypt\Manager\Contract\Crypt;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Jwt\Manager\Contract\Jwt;

use function is_string;

/**
 * Class EncryptedJwtAuthenticator.
 *
 * @author Melech Mizrachi
 *
 * @template U of User
 *
 * @extends JwtAuthenticator<U>
 */
class EncryptedJwtAuthenticator extends JwtAuthenticator
{
    /**
     * @param Store<U>        $store  The store
     * @param class-string<U> $entity The user entity
     */
    public function __construct(
        protected Crypt $crypt,
        Jwt $jwt,
        ServerRequest $request,
        Store $store,
        PasswordHasher $hasher,
        string $entity,
        AuthenticatedUsers|null $authenticatedUsers = null,
        string $headerName = HeaderName::AUTHORIZATION,
    ) {
        parent::__construct(
            jwt: $jwt,
            request: $request,
            store: $store,
            hasher: $hasher,
            entity: $entity,
            authenticatedUsers: $authenticatedUsers,
            headerName: $headerName,
        );
    }

    /**
     * Attempt to get the authenticated users from the token.
     *
     * @param string $token The token
     *
     * @return AuthenticatedUsers|null
     */
    #[Override]
    protected function getAuthenticatedUsersFromToken(string $token): AuthenticatedUsers|null
    {
        $jwtPayload = $this->jwt->decode($token);
        $users      = $jwtPayload['users'] ?? null;

        if (! is_string($users)) {
            throw new InvalidAuthenticationException('Invalid token structure. Expecting users');
        }

        $decryptedUsers    = $this->crypt->decrypt($users);
        $unserializedUsers = unserialize(
            $decryptedUsers,
            ['allowed_classes' => true]
        );

        if (! $unserializedUsers instanceof AuthenticatedUsers) {
            throw new InvalidAuthenticationException('Invalid token structure. Expecting ' . AuthenticatedUsers::class);
        }

        return $unserializedUsers;
    }
}
