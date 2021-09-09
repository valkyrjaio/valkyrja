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

namespace Valkyrja\Auth\Repositories;

use Exception;
use Valkyrja\Auth\Adapter;
use Valkyrja\Auth\AuthenticatedUsers;
use Valkyrja\Auth\Constants\Header;
use Valkyrja\Auth\Exceptions\InvalidAuthenticationException;
use Valkyrja\Auth\TokenizableUser;
use Valkyrja\Auth\TokenizedRepository as Contract;
use Valkyrja\Auth\User;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Crypt\Exceptions\CryptException;
use Valkyrja\Http\Request;
use Valkyrja\Session\Session;

/**
 * Class TokenizedRepository.
 *
 * @author Melech Mizrachi
 */
class TokenizedRepository extends Repository implements Contract
{
    /**
     * The crypt service.
     *
     * @var Crypt
     */
    protected Crypt $crypt;

    /**
     * Repository constructor.
     *
     * @param Adapter $adapter
     * @param Crypt   $crypt The crypt
     * @param Session $session
     * @param array   $config
     * @param string  $user
     */
    public function __construct(Adapter $adapter, Crypt $crypt, Session $session, array $config, string $user)
    {
        parent::__construct($adapter, $session, $config, $user);

        $this->crypt = $crypt;
    }

    /**
     * Authenticate a user with credentials.
     *
     * @param User $user The user
     *
     * @throws InvalidAuthenticationException
     * @throws CryptException
     *
     * @return static
     */
    public function authenticate(User $user): self
    {
        parent::authenticate($user);

        $this->user::setTokenized($this->getToken());

        return $this;
    }

    /**
     * Authenticate a user from an active session.
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function authenticateFromSession(): self
    {
        $token = $this->getTokenFromSession();
        $user  = $this->getUserFromToken($token);

        $this->authenticateWithUser($user);

        return $this;
    }

    /**
     * Authenticate a user from a request.
     *
     * @param Request $request The request
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function authenticateFromRequest(Request $request): self
    {
        $token = $request->getHeaderLine(Header::AUTH_TOKEN);
        $user  = $this->getUserFromToken($token);

        return $this->authenticateWithUser($user);
    }

    /**
     * Set the authenticated user in the session.
     *
     * @throws CryptException
     *
     * @return static
     */
    public function setSession(): self
    {
        $this->session->set($this->user::getTokenSessionId(), $this->getToken());

        return $this;
    }

    /**
     * Get the user token.
     *
     * @throws CryptException
     *
     * @return string
     */
    protected function getToken(): string
    {
        /** @var TokenizableUser $user */
        $user = $this->user;

        if ($token = $user::asTokenized()) {
            return $token;
        }

        $collection        = $this->users;
        $collectionAsArray = $collection->asArray();

        foreach ($collection->all() as $key => $userFromCollection) {
            $collectionAsArray['users'][$key] = $userFromCollection->asTokenizableArray();
        }

        $token = $this->crypt->encryptArray($collectionAsArray);

        $user::setTokenized($token);

        return $token;
    }

    /**
     * Determine if a token is valid.
     *
     * @param string|null $token [optional] The token
     *
     * @return bool
     */
    protected function isTokenValid(string $token = null): bool
    {
        if (! $token) {
            return false;
        }

        return $this->crypt->isValidEncryptedMessage($token);
    }

    /**
     * Get the user token from session.
     *
     * @return string|null
     */
    protected function getTokenFromSession(): ?string
    {
        return $this->session->get($this->userEntityName::getTokenSessionId());
    }

    /**
     * Store the user token in session.
     *
     * @param string|null $token [optional] The token to store
     *
     * @throws CryptException
     *
     * @return static
     */
    protected function storeToken(string $token = null): self
    {
        $this->session->set($this->user::getTokenSessionId(), $token ?? $this->getToken());

        return $this;
    }

    /**
     * Get a user from a token.
     *
     * @param string|null $token [optional] The token
     *
     * @return User
     */
    protected function getUserFromToken(string $token = null): User
    {
        if (
            ! $this->isTokenValid($token)
            || null === $users = $this->tryGettingUserFromToken($token)
        ) {
            $this->resetAfterLogout();

            throw new InvalidAuthenticationException('Invalid user token.');
        }

        $this->users = $users;

        return $users->getCurrent();
    }

    /**
     * Attempt to get users from token.
     *
     * @param string $token The token
     *
     * @return AuthenticatedUsers|null
     */
    protected function tryGettingUserFromToken(string $token): ?AuthenticatedUsers
    {
        try {
            $usersProperties = $this->crypt->decryptArray($token);
            /** @var AuthenticatedUsers $users */
            $users = $this->usersModel::fromArray($usersProperties);
        } catch (Exception $exception) {
            return null;
        }

        return $users;
    }
}
