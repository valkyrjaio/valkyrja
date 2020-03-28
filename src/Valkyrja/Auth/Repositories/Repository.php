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

use Valkyrja\Auth\Adapter;
use Valkyrja\Auth\Authenticator;
use Valkyrja\Auth\Enums\SessionId;
use Valkyrja\Auth\Exceptions\InvalidAuthenticationException;
use Valkyrja\Auth\Exceptions\InvalidPasswordConfirmationException;
use Valkyrja\Auth\Exceptions\InvalidRegistrationException;
use Valkyrja\Auth\Auth;
use Valkyrja\Auth\LockableUser;
use Valkyrja\Auth\Registrator;
use Valkyrja\Auth\Repository as Contract;
use Valkyrja\Auth\User;
use Valkyrja\Crypt\Exceptions\CryptException;
use Valkyrja\Session\Session;
use Valkyrja\Support\ClassHelpers;

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
     * The authenticator.
     *
     * @var Authenticator
     */
    protected Authenticator $authenticator;

    /**
     * The registrator.
     *
     * @var Registrator
     */
    protected Registrator $registrator;

    /**
     * The session.
     *
     * @var Session
     */
    protected Session $session;

    /**
     * The user entity.
     *
     * @var string
     */
    protected string $userEntity;

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
     * @param Session $session
     * @param Adapter $adapter
     * @param array $config
     * @param string  $user
     */
    public function __construct(Adapter $adapter, Session $session, array $config, string $user)
    {
        ClassHelpers::validateClass($user, User::class);

        $this->config        = $config;
        $this->adapter       = $adapter;
        $this->authenticator = $adapter->getAuthenticator();
        $this->registrator   = $adapter->getRegistrator();
        $this->session       = $session;
        $this->userEntity    = $user;
    }

    /**
     * Make a new repository.
     *
     * @param Auth   $auth
     * @param string $user
     *
     * @return static
     */
    public static function make(Auth $auth, string $user): self
    {
        return new static(
            $auth->getAdapter(static::$adapterName),
            $auth->getSession(),
            $auth->getConfig(),
            $user
        );
    }

    /**
     * Get the logged in user.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Log a user in.
     *
     * @param User $user
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function login(User $user): self
    {
        if (! $this->authenticator->authenticate($user)) {
            throw new InvalidAuthenticationException('Invalid user credentials.');
        }

        $this->setAuthenticatedUser($user);

        return $this;
    }

    /**
     * Log a user in via token.
     *
     * @param string $token
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function loginWithToken(string $token): self
    {
        if (
            ! $this->authenticator->isValidToken($token)
            || null === $user = $this->authenticator->getUserFromToken($this->userEntity, $token)
        ) {
            $this->resetAfterLogout();

            throw new InvalidAuthenticationException('Invalid user token.');
        }

        if ($this->config['alwaysAuthenticate']) {
            $this->login($user);

            return $this;
        }

        if ($this->config['keepUserFresh']) {
            $this->authenticator->getFreshUser($user);
        }

        $this->setAuthenticatedUser($user);

        return $this;
    }

    /**
     * Log a user in via session.
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function loginFromSession(): self
    {
        if (! $token = $this->session->get($this->user::getSessionId())) {
            $this->resetAfterLogout();

            throw new InvalidAuthenticationException('No user session exists.');
        }

        $this->loginWithToken($token);

        return $this;
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
        return $this->authenticator->getToken($this->user);
    }

    /**
     * Store the user token in session.
     *
     * @throws CryptException
     *
     * @return static
     */
    public function storeToken(): self
    {
        $this->session->set($this->user::getSessionId(), $this->getToken());

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
     * @param User $user
     *
     * @throws InvalidRegistrationException
     *
     * @return static
     */
    public function register(User $user): self
    {
        if (! $this->registrator->register($user)) {
            throw new InvalidRegistrationException('Registration failed.');
        }

        return $this;
    }

    /**
     * Forgot password.
     *
     * @param User $user
     *
     * @return static
     */
    public function forgot(User $user): self
    {
        $this->authenticator->resetPassword($user);

        return $this;
    }

    /**
     * Reset a user's password.
     *
     * @param User   $user
     * @param string $password
     *
     * @return static
     */
    public function reset(User $user, string $password): self
    {
        $this->authenticator->updatePassword($user, $password);

        return $this;
    }

    /**
     * Lock a user.
     *
     * @param LockableUser $user
     *
     * @return static
     */
    public function lock(LockableUser $user): self
    {
        $this->authenticator->lock($user);

        return $this;
    }

    /**
     * Unlock a user.
     *
     * @param LockableUser $user
     *
     * @return static
     */
    public function unlock(LockableUser $user): self
    {
        $this->authenticator->unlock($user);

        return $this;
    }

    /**
     * Confirm the current user's password.
     *
     * @param string $password
     *
     * @throws InvalidPasswordConfirmationException
     *
     * @return static
     */
    public function confirmPassword(string $password): self
    {
        if (! $this->authenticator->isPassword($this->user, $password)) {
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
     * Set the authenticated user.
     *
     * @param User $user
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
        $this->session->remove($this->user::getSessionId());
    }
}
