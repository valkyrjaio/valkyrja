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

use Override;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsersContract;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Hasher\Contract\PasswordHasherContract;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Auth\Throwable\Exception\InvalidAuthenticationException;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Jwt\Manager\Contract\JwtContract;

use function is_string;

/**
 * Class EncryptedJwtAuthenticator.
 *
 * @template U of UserContract
 *
 * @extends JwtAuthenticator<U>
 */
class EncryptedJwtAuthenticator extends JwtAuthenticator
{
    /**
     * @param StoreContract<U> $store  The store
     * @param class-string<U>  $entity The user entity
     */
    public function __construct(
        protected CryptContract $crypt,
        JwtContract $jwt,
        ServerRequestContract $request,
        StoreContract $store,
        PasswordHasherContract $hasher,
        string $entity,
        AuthenticatedUsersContract|null $authenticatedUsers = null,
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
     * @return AuthenticatedUsersContract|null
     */
    #[Override]
    protected function getAuthenticatedUsersFromToken(string $token): AuthenticatedUsersContract|null
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

        if (! $unserializedUsers instanceof AuthenticatedUsersContract) {
            throw new InvalidAuthenticationException('Invalid token structure. Expecting ' . AuthenticatedUsersContract::class);
        }

        return $unserializedUsers;
    }
}
