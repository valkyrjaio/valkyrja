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

use Throwable;
use Valkyrja\Auth\AuthenticatedUsers;
use Valkyrja\Auth\Exceptions\InvalidAuthenticationException;
use Valkyrja\Auth\Exceptions\MissingTokenizableUserRequiredFieldsException;
use Valkyrja\Auth\Exceptions\TokenizationException;
use Valkyrja\Auth\TokenizableUser;
use Valkyrja\Auth\TokenizedRepository as Contract;
use Valkyrja\Auth\User;
use Valkyrja\Http\Constants\Header;
use Valkyrja\Http\Request;
use Valkyrja\Support\Type\Str;

/**
 * Abstract Class TokenizedRepository.
 *
 * @author Melech Mizrachi
 *
 * @property TokenizableUser        $user
 * @property TokenizableUser|string $userEntityName
 */
abstract class TokenizedRepository extends Repository implements Contract
{
    /**
     * The token.
     *
     * @var string
     */
    protected string $token;

    /**
     * @inheritDoc
     *
     * @throws TokenizationException
     */
    public function setUser(User $user): self
    {
        parent::setUser($user);

        $this->resetToken();

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws TokenizationException
     */
    public function setUsers(AuthenticatedUsers $users): self
    {
        parent::setUsers($users);

        $this->resetToken();

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws TokenizationException
     */
    public function authenticate(User $user): self
    {
        parent::authenticate($user);

        $this->resetToken();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function authenticateFromSession(): self
    {
        $token = $this->getTokenFromSession();

        return $this->authenticateFromToken($token);
    }

    /**
     * @inheritDoc
     */
    public function authenticateFromRequest(Request $request): self
    {
        $token = $this->getTokenFromRequest($request);

        return $this->authenticateFromToken($token);
    }

    /**
     * @inheritDoc
     *
     * @throws TokenizationException
     */
    public function setSession(): self
    {
        $this->session->set($this->user::getTokenSessionId(), $this->getToken());

        return $this;
    }

    /**
     * Get the user token.
     *
     * @throws TokenizationException
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token ??= $this->getFreshToken();
    }

    /**
     * @inheritDoc
     */
    public function authenticateFromToken(string $token): self
    {
        $user = $this->getUserFromToken($token);

        return $this->authenticateWithUser($user);
    }

    /**
     * Try tokenizing the users.
     *
     * @param AuthenticatedUsers $users The users
     *
     * @throws TokenizationException
     *
     * @return string
     */
    protected function tryTokenizingUsers(AuthenticatedUsers $users): string
    {
        try {
            return $this->tokenizeUsers($users);
        } catch (Throwable $exception) {
            throw new TokenizationException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * Get the required fields.
     *
     * @return string[]
     */
    protected function getRequiredFields(): array
    {
        $requiredFields = [
            $this->userEntityName::getIdField(),
        ];

        // If the always authenticate flag is on in the config we need the password and authentication fields to be part
        // of the tokenized user
        if ($this->config['alwaysAuthenticate']) {
            $requiredFields[] = $this->userEntityName::getPasswordField();

            $requiredFields = array_merge($requiredFields, $this->user::getAuthenticationFields());
        }

        return $requiredFields;
    }

    /**
     * Ensure required fields for tokenization.
     *
     * @param AuthenticatedUsers $users The users
     *
     * @return void
     */
    protected function ensureRequiredFieldsForTokenization(AuthenticatedUsers $users): void
    {
        // Required fields that should exist within the tokenized user
        $requiredFields = $this->getRequiredFields();

        /** @var TokenizableUser $userFromCollection */
        foreach ($users->all() as $key => $userFromCollection) {
            $userAsTokenizableArray = $collectionAsArray['users'][$key] = $userFromCollection->asTokenizableArray();

            foreach ($requiredFields as $requiredField) {
                if (! isset($userAsTokenizableArray[$requiredField])) {
                    $entityName = $this->userEntityName;

                    throw new MissingTokenizableUserRequiredFieldsException("Required field `$requiredField` is not being returned in $entityName::asTokenizableArray()");
                }
            }
        }
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
     * Get the token from a request.
     *
     * @param Request $request The request
     *
     * @return string
     */
    protected function getTokenFromRequest(Request $request): string
    {
        $token = $request->getHeaderLine(Header::AUTHORIZATION);

        return Str::replace($token, 'Bearer ', '');
    }

    /**
     * Store the user token in session.
     *
     * @param string|null $token [optional] The token to store
     *
     * @throws TokenizationException
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
     * @throws InvalidAuthenticationException
     *
     * @return User
     */
    protected function getUserFromToken(string $token = null): User
    {
        try {
            $users = $this->tryUnTokenizingUsers($token);
        } catch (Throwable $exception) {
            $this->resetAfterUnAuthentication();

            throw new InvalidAuthenticationException('Invalid user token.', $exception->getCode(), $exception);
        }

        $this->users = $users;

        return $users->getCurrent();
    }


    /**
     * Attempt to get users from token.
     *
     * @param string $token The token
     *
     * @throws TokenizationException
     *
     * @return AuthenticatedUsers
     */
    protected function tryUnTokenizingUsers(string $token): AuthenticatedUsers
    {
        try {
            return $this->unTokenizeUsers($token);
        } catch (Throwable $exception) {
            throw new TokenizationException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * Get a fresh token.
     *
     * @return string
     */
    protected function getFreshToken(): string
    {
        $user  = $this->user;
        $users = $this->users;

        $this->ensureRequiredFieldsForTokenization($users);
        $token = $this->tryTokenizingUsers($users);

        $user::setTokenized($token);

        return $token;
    }

    /**
     * Set the user token.
     *
     * @throws TokenizationException
     *
     * @return void
     */
    protected function resetToken(): void
    {
        $this->user::setTokenized($this->token = $this->getFreshToken());
    }

    /**
     * Tokenize the users.
     *
     * @param AuthenticatedUsers $users The users
     *
     * @return string
     */
    abstract protected function tokenizeUsers(AuthenticatedUsers $users): string;

    /**
     * Un-tokenize users.
     *
     * @param string $token The token
     *
     * @return AuthenticatedUsers
     */
    abstract protected function unTokenizeUsers(string $token): AuthenticatedUsers;
}
