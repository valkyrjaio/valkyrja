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
use Valkyrja\Auth\Constant\HeaderValue;
use Valkyrja\Auth\Data\AuthenticatedUsers as AuthenticatedUsersData;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsersContract;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Hasher\Contract\PasswordHasherContract;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Auth\Throwable\Exception\InvalidAuthenticationException;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;

/**
 * Class TokenAuthenticator.
 *
 * @template U of UserContract
 *
 * @extends Authenticator<U>
 */
class TokenAuthenticator extends Authenticator
{
    /**
     * @param StoreContract<U> $store  The store
     * @param class-string<U>  $entity The user entity
     */
    public function __construct(
        protected ServerRequestContract $request,
        StoreContract $store,
        PasswordHasherContract $hasher,
        string $entity,
        AuthenticatedUsersContract|null $authenticatedUsers = null,
        protected string $headerName = HeaderName::AUTHORIZATION,
    ) {
        parent::__construct(
            store: $store,
            hasher: $hasher,
            entity: $entity,
            authenticatedUsers: $authenticatedUsers ?? $this->getAuthenticatedUsersFromRequest() ?? new AuthenticatedUsersData(),
        );
    }

    /**
     * Attempt to get the authenticated users from the request.
     *
     * @return AuthenticatedUsersContract|null
     */
    protected function getAuthenticatedUsersFromRequest(): AuthenticatedUsersContract|null
    {
        $headerLine = $this->request->getHeaderLine(HeaderName::AUTHORIZATION);

        if ($headerLine === '') {
            return null;
        }

        [$bearer, $token] = explode(' ', $headerLine);

        if ($bearer !== HeaderValue::BEARER) {
            throw new InvalidAuthenticationException('Invalid authorization header');
        }

        return $this->getAuthenticatedUsersFromToken($token);
    }

    /**
     * Attempt to get the authenticated users from the token.
     *
     * @param string $token The token
     *
     * @return AuthenticatedUsersContract|null
     */
    protected function getAuthenticatedUsersFromToken(string $token): AuthenticatedUsersContract|null
    {
        $unserializedUsers = unserialize(
            $token,
            ['allowed_classes' => true]
        );

        if (! $unserializedUsers instanceof AuthenticatedUsersContract) {
            throw new InvalidAuthenticationException('Invalid token structure. Expecting ' . AuthenticatedUsersContract::class);
        }

        return $unserializedUsers;
    }
}
