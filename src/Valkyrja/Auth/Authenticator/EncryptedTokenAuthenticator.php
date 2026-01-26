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
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;

/**
 * @template U of UserContract
 *
 * @extends TokenAuthenticator<U>
 */
class EncryptedTokenAuthenticator extends TokenAuthenticator
{
    /**
     * @param StoreContract<U> $store  The store
     * @param class-string<U>  $entity The user entity
     */
    public function __construct(
        protected CryptContract $crypt,
        ServerRequestContract $request,
        StoreContract $store,
        PasswordHasherContract $hasher,
        string $entity,
        AuthenticatedUsersContract|null $authenticatedUsers = null,
        string $headerName = HeaderName::AUTHORIZATION,
    ) {
        parent::__construct(
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
     * @param non-empty-string $token The token
     */
    #[Override]
    protected function getAuthenticatedUsersFromToken(string $token): AuthenticatedUsersContract|null
    {
        $decryptedToken = $this->crypt->decrypt($token);

        return parent::getAuthenticatedUsersFromToken($decryptedToken);
    }
}
