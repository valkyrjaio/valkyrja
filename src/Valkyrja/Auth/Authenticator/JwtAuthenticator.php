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

use Valkyrja\Auth\Constant\HeaderValue;
use Valkyrja\Auth\Data;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsers;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Exception\InvalidAuthenticationException;
use Valkyrja\Auth\Hasher\Contract\PasswordHasher;
use Valkyrja\Auth\Store\Contract\Store;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Jwt\Manager\Contract\Jwt;

use function is_string;

/**
 * Class JwtAuthenticator.
 *
 * @author Melech Mizrachi
 *
 * @template U of User
 *
 * @extends AbstractAuthenticator<U>
 */
class JwtAuthenticator extends AbstractAuthenticator
{
    /**
     * @param Store<U>        $store  The store
     * @param class-string<U> $entity The user entity
     */
    public function __construct(
        protected Jwt $jwt,
        protected ServerRequest $request,
        Store $store,
        PasswordHasher $hasher,
        string $entity,
        AuthenticatedUsers|null $authenticatedUsers = null,
        protected string $headerName = HeaderName::AUTHORIZATION,
    ) {
        parent::__construct(
            store: $store,
            hasher: $hasher,
            entity: $entity,
            authenticatedUsers: $authenticatedUsers ?? $this->getAuthenticatedUsersFromRequest() ?? new Data\AuthenticatedUsers(),
        );
    }

    /**
     * Attempt to get the authenticated users from the request.
     *
     * @return AuthenticatedUsers|null
     */
    protected function getAuthenticatedUsersFromRequest(): AuthenticatedUsers|null
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
     * @return AuthenticatedUsers|null
     */
    protected function getAuthenticatedUsersFromToken(string $token): AuthenticatedUsers|null
    {
        $jwtPayload = $this->jwt->decode($token);
        $users      = $jwtPayload['users'] ?? null;

        if (! is_string($users)) {
            throw new InvalidAuthenticationException('Invalid token structure. Expecting users');
        }

        $unserializedUsers = unserialize(
            $users,
            ['allowed_classes' => true]
        );

        if (! $unserializedUsers instanceof AuthenticatedUsers) {
            throw new InvalidAuthenticationException('Invalid token structure. Expecting ' . AuthenticatedUsers::class);
        }

        return $unserializedUsers;
    }
}
