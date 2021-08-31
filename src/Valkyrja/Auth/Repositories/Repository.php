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
use JsonException;
use Valkyrja\Auth\Adapter;
use Valkyrja\Auth\Constants\SessionId;
use Valkyrja\Auth\Exceptions\InvalidAuthenticationException;
use Valkyrja\Auth\Exceptions\InvalidPasswordConfirmationException;
use Valkyrja\Auth\Exceptions\InvalidRegistrationException;
use Valkyrja\Auth\LockableUser;
use Valkyrja\Auth\Repository as Contract;
use Valkyrja\Auth\User;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Crypt\Exceptions\CryptException;
use Valkyrja\Session\Session;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Cls;

use function time;

/**
 * Class Repository.
 *
 * @author Melech Mizrachi
 */
class Repository implements Contract
{
    /**
     * The adapter name to use.
     *
     * @var string|null
     */
    protected static ?string $adapterName = null;

    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * The crypt service.
     *
     * @var Crypt
     */
    protected Crypt $crypt;

    /**
     * The session manager.
     *
     * @var Session
     */
    protected Session $session;

    /**
     * The user entity.
     *
     * @var string|User
     */
    protected string $userEntityName;

    /**
     * The current authenticated user.
     *
     * @var User
     */
    protected User $user;

    /**
     * Determine if a user is authenticated.
     *
     * @var bool
     */
    protected bool $isAuthenticated = false;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

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
        Cls::validateInherits($user, User::class);

        $this->config         = $config;
        $this->adapter        = $adapter;
        $this->crypt          = $crypt;
        $this->session        = $session;
        $this->userEntityName = $user;
    }

    /**
     * Get the logged in user.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user ?? new $this->userEntityName();
    }

    /**
     * Set the logged in user.
     *
     * @param User $user The user
     *
     * @return static
     */
    public function setUser(User $user): self
    {
        $this->setAuthenticatedUser($user);

        return $this;
    }

    /**
     * Get the user stored in session.
     *
     * @throws InvalidAuthenticationException
     * @throws JsonException
     *
     * @return User
     */
    public function getUserFromSession(): User
    {
        if (isset($this->user)) {
            return $this->user;
        }

        $sessionUser = $this->session->get($this->userEntityName::getUserSessionId());

        if (! $sessionUser) {
            throw new InvalidAuthenticationException('No logged in user.');
        }

        $userData = Arr::fromString($sessionUser);

        return $this->user = $this->userEntityName::fromArray($userData);
    }

    /**
     * Log a user in.
     *
     * @param User $user The user
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function login(User $user): self
    {
        if (! $this->adapter->authenticate($user)) {
            throw new InvalidAuthenticationException('Invalid user credentials.');
        }

        $this->setAuthenticatedUser($user);

        return $this;
    }

    /**
     * Ensure a token is still valid.
     *
     * @param string $token The token
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function ensureTokenValidity(string $token): self
    {
        $user = $this->getUserFromToken($token);

        $this->ensureUserValidity($user);

        return $this;
    }

    /**
     * Ensure a tokenized user is still valid.
     *
     * @param User $user The tokenized user
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function ensureUserValidity(User $user): self
    {
        $passwordField = $user::getPasswordField();
        // Get a fresh user from the database
        $dbUser = $this->adapter->retrieveById($user);

        // If the db password does not match the tokenized user password the token is no longer valid
        if ($dbUser->__get($passwordField) !== $user->__get($passwordField)) {
            $this->resetAfterLogout();

            throw new InvalidAuthenticationException('User is no longer valid.');
        }

        if ($this->config['keepUserFresh']) {
            $this->setAuthenticatedUser($dbUser);
        }

        return $this;
    }

    /**
     * Log a user in via token.
     *
     * @param string $token The token
     * @param bool   $store [optional] Whether to store the token in session
     *
     * @throws CryptException
     * @throws InvalidAuthenticationException
     * @throws JsonException
     *
     * @return static
     */
    public function loginWithToken(string $token, bool $store = false): self
    {
        $user = $this->getUserFromToken($token);

        $this->loginWithUser($user, $store);

        if ($store) {
            $this->storeToken($token);
        }

        return $this;
    }

    /**
     * Log in with a specific user.
     *
     * @param User $user  The user
     * @param bool $store [optional] Whether to store the user in session
     *
     * @throws InvalidAuthenticationException
     * @throws JsonException
     *
     * @return static
     */
    public function loginWithUser(User $user, bool $store = false): self
    {
        if ($this->config['alwaysAuthenticate']) {
            $this->ensureUserValidity($user);

            return $this;
        }

        if ($this->config['keepUserFresh']) {
            $user = $this->adapter->retrieveById($user);
        }

        $this->setAuthenticatedUser($user);

        if ($store) {
            $this->storeUser();
        }

        return $this;
    }

    /**
     * Log a user in via tokenized session.
     *
     * @throws CryptException
     * @throws InvalidAuthenticationException
     * @throws JsonException
     *
     * @return static
     */
    public function loginFromTokenizedSession(): self
    {
        if (! $token = $this->getTokenFromSession()) {
            $this->resetAfterLogout();

            throw new InvalidAuthenticationException('No user token session exists.');
        }

        $this->loginWithToken($token);

        return $this;
    }

    /**
     * Log a user in via a user session.
     *
     * @throws JsonException
     *
     * @return static
     */
    public function loginFromSession(): self
    {
        if (! $user = $this->getUserFromSession()) {
            $this->resetAfterLogout();

            throw new InvalidAuthenticationException('No user session exists.');
        }

        return $this->loginWithUser($user);
    }

    /**
     * Get the user token.
     *
     * @throws CryptException
     *
     * @return string
     */
    public function getToken(): string
    {
        $user = $this->user;
        // Get the password field
        $passwordField = $user::getPasswordField();

        $user->expose($passwordField);

        $token = $this->crypt->encryptArray($user->asTokenizableArray());

        $user->unexpose($passwordField);

        return $token;
    }

    /**
     * Determine if a token is valid.
     *
     * @param string $token
     *
     * @return bool
     */
    public function isTokenValid(string $token): bool
    {
        return $this->crypt->isValidEncryptedMessage($token);
    }

    /**
     * Get the user token from session.
     *
     * @return string
     */
    public function getTokenFromSession(): string
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
    public function storeToken(string $token = null): self
    {
        $this->session->set($this->user::getTokenSessionId(), $token ?? $this->getToken());

        return $this;
    }

    /**
     * Store the user in session.
     *
     * @param User|null $user [optional] The user to store
     *
     * @throws JsonException
     *
     * @return static
     */
    public function storeUser(User $user = null): self
    {
        $user = $user ?? $this->getUser();

        $this->session->set($this->user::getUserSessionId(), Arr::toString($user->asTokenizableArray()));

        return $this;
    }

    /**
     * Determine if a user is logged in.
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->isAuthenticated;
    }

    /**
     * Log the current user out.
     *
     * @return static
     */
    public function logout(): self
    {
        if ($this->isAuthenticated) {
            $this->resetAfterLogout();
        }

        return $this;
    }

    /**
     * Register a new user.
     *
     * @param User $user The user
     *
     * @throws InvalidRegistrationException
     *
     * @return static
     */
    public function register(User $user): self
    {
        $this->adapter->create($user);

        return $this;
    }

    /**
     * Forgot password.
     *
     * @param User $user The user
     *
     * @throws Exception
     *
     * @return static
     */
    public function forgot(User $user): self
    {
        $dbUser = $this->adapter->retrieve($user);

        if (! $dbUser) {
            throw new InvalidAuthenticationException('No user found.');
        }

        $this->adapter->updateResetToken($dbUser);

        $user->__set($user::getResetTokenField(), $dbUser->__get($dbUser::getResetTokenField()));
        $user->__set($user::getIdField(), $dbUser->__get($dbUser::getIdField()));

        return $this;
    }

    /**
     * Reset a user's password.
     *
     * @param string $resetToken The reset token
     * @param string $password   The password
     *
     * @throws Exception
     *
     * @return static
     */
    public function reset(string $resetToken, string $password): self
    {
        if (! $user = $this->adapter->retrieveByResetToken(new $this->userEntityName(), $resetToken)) {
            throw new InvalidAuthenticationException('Invalid reset token.');
        }

        $this->adapter->updatePassword($user, $password);
        $this->setUser($user);

        return $this;
    }

    /**
     * Lock a user.
     *
     * @param LockableUser $user The user
     *
     * @return static
     */
    public function lock(LockableUser $user): self
    {
        $this->lockUnlock($user, true);

        return $this;
    }

    /**
     * Unlock a user.
     *
     * @param LockableUser $user The user
     *
     * @return static
     */
    public function unlock(LockableUser $user): self
    {
        $this->lockUnlock($user, false);

        return $this;
    }

    /**
     * Confirm the current user's password.
     *
     * @param string $password The password
     *
     * @throws InvalidPasswordConfirmationException
     *
     * @return static
     */
    public function confirmPassword(string $password): self
    {
        if (! $this->adapter->verifyPassword($this->user, $password)) {
            throw new InvalidPasswordConfirmationException('Invalid password confirmation.');
        }

        return $this;
    }

    /**
     * Store the confirmed password timestamp in session.
     *
     * @return static
     */
    public function storeConfirmedPassword(): self
    {
        $this->session->set(SessionId::PASSWORD_CONFIRMED_TIMESTAMP, (string) time());

        return $this;
    }

    /**
     * Determine if a re-authentication needs to occur.
     *
     * @return bool
     */
    public function isReAuthenticationRequired(): bool
    {
        $confirmedAt = time() - ((int) $this->session->get(SessionId::PASSWORD_CONFIRMED_TIMESTAMP, 0));

        return $confirmedAt > (int) ($this->config['passwordTimeout'] ?? 10800);
    }

    /**
     * Set the authenticated user.
     *
     * @param User $user The user
     *
     * @return void
     */
    protected function setAuthenticatedUser(User $user): void
    {
        $this->user            = $user;
        $this->isAuthenticated = true;
    }

    /**
     * Reset properties and session after logout.
     *
     * @return void
     */
    protected function resetAfterLogout(): void
    {
        $this->isAuthenticated = false;
        $this->session->remove($this->userEntityName::getTokenSessionId());
    }

    /**
     * Lock or unlock a user.
     *
     * @param LockableUser $user
     * @param bool         $lock
     *
     * @return void
     */
    protected function lockUnlock(LockableUser $user, bool $lock): void
    {
        $user->__set($user::getIsLockedField(), $lock);

        $this->adapter->save($user);
    }

    /**
     * Get a user from a token.
     *
     * @param string $token The token
     *
     * @return User
     */
    protected function getUserFromToken(string $token): User
    {
        if (
            ! $this->isTokenValid($token)
            || null === $user = $this->tryGettingUserFromToken($token)
        ) {
            $this->resetAfterLogout();

            throw new InvalidAuthenticationException('Invalid user token.');
        }

        return $user;
    }

    /**
     * Attempt to get a user from token.
     *
     * @param string $token The token
     *
     * @return User|null
     */
    protected function tryGettingUserFromToken(string $token): ?User
    {
        try {
            $userProperties = $this->crypt->decryptArray($token);
            /** @var User $userModel */
            $userModel = $this->userEntityName::fromArray($userProperties);
        } catch (Exception $exception) {
            return null;
        }

        return $userModel;
    }
}
