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
use Valkyrja\Auth\Constants\SessionId;
use Valkyrja\Auth\Exceptions\InvalidAuthenticationException;
use Valkyrja\Auth\Exceptions\InvalidPasswordConfirmationException;
use Valkyrja\Auth\Exceptions\InvalidRegistrationException;
use Valkyrja\Auth\LockableUser;
use Valkyrja\Auth\Repository as Contract;
use Valkyrja\Auth\User;
use Valkyrja\Http\Request;
use Valkyrja\Session\Session;
use Valkyrja\Support\Type\Cls;

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
     * The authenticated users model.
     *
     * @var string|AuthenticatedUsers
     */
    protected string $usersModel;

    /**
     * The current authenticated user.
     *
     * @var User|null
     */
    protected ?User $user = null;

    /**
     * The current authenticated users.
     *
     * @var AuthenticatedUsers
     */
    protected AuthenticatedUsers $users;

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
     * @param Session $session
     * @param array   $config
     * @param string  $user
     */
    public function __construct(Adapter $adapter, Session $session, array $config, string $user)
    {
        Cls::validateInherits($user, User::class);

        $this->config         = $config;
        $this->adapter        = $adapter;
        $this->session        = $session;
        $this->userEntityName = $user;

        $this->usersModel = $this->userEntityName::getAuthCollection();
        $this->users      = new $this->usersModel();
    }

    /**
     * Determine if a user is authenticated.
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    /**
     * Get the authenticated user.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user ?? new $this->userEntityName();
    }

    /**
     * Set the authenticated user.
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
     * Get the authenticated users.
     *
     * @return AuthenticatedUsers
     */
    public function getUsers(): AuthenticatedUsers
    {
        return $this->users;
    }

    /**
     * Set the authenticated users.
     *
     * @param AuthenticatedUsers $users The users
     *
     * @return static
     */
    public function setUsers(AuthenticatedUsers $users): self
    {
        $this->users = $users;
        $this->user  = $users->getCurrent();

        $this->isAuthenticated = $users->hasCurrent();

        return $this;
    }

    /**
     * Authenticate a user with credentials.
     *
     * @param User $user The user
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function authenticate(User $user): self
    {
        if (! $this->adapter->authenticate($user)) {
            throw new InvalidAuthenticationException('Invalid user credentials.');
        }

        $this->setAuthenticatedUser($user);

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
        if (! $user = $this->getUserFromSession()) {
            $this->resetAfterLogout();

            throw new InvalidAuthenticationException('No user session exists.');
        }

        return $this->authenticateWithUser($user);
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
        $requestParams = $request->onlyParsedBody($this->userEntityName::getAuthenticationFields());

        $requestParams[$this->userEntityName::getPasswordField()] = $request->getParsedBodyParam($this->userEntityName::getPasswordField());

        $user = $this->userEntityName::fromArray($requestParams);

        return $this->authenticate($user);
    }

    /**
     * Un-authenticate any active users.
     *
     * @return static
     */
    public function unAuthenticate(): self
    {
        if ($this->isAuthenticated) {
            $this->resetAfterLogout();
        }

        return $this;
    }

    /**
     * Set the authenticated user in the session.
     *
     * @return static
     */
    public function setSession(): self
    {
        $collection        = $this->users;
        $collectionAsArray = $collection->asArray();

        foreach ($collection->all() as $key => $user) {
            $collectionAsArray['users'][$key] = $user->asStorableArray();
        }

        $this->session->set($this->userEntityName::getUserSessionId(), $collectionAsArray);

        return $this;
    }

    /**
     * Unset the authenticated user from the session.
     *
     * @return static
     */
    public function unsetSession(): self
    {
        $this->session->remove($this->userEntityName::getUserSessionId());

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
     * Get the user stored in session.
     *
     * @throws InvalidAuthenticationException
     *
     * @return User
     */
    protected function getUserFromSession(): User
    {
        if (isset($this->user)) {
            return $this->user;
        }

        $sessionUsers = $this->session->get($this->userEntityName::getUserSessionId());

        if (! $sessionUsers) {
            throw new InvalidAuthenticationException('No authenticated users.');
        }

        $this->users = $this->usersModel::fromArray($sessionUsers);

        return $this->user = $this->users->getCurrent();
    }

    /**
     * Authenticate with a specific user.
     *
     * @param User $user The user
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    protected function authenticateWithUser(User $user): self
    {
        if ($this->config['alwaysAuthenticate']) {
            $this->ensureUserValidity($user);

            return $this;
        }

        if ($this->config['keepUserFresh']) {
            $user = $this->adapter->retrieveById($user);
        }

        $this->setAuthenticatedUser($user);

        return $this;
    }

    /**
     * Store the confirmed password timestamp in session.
     *
     * @return static
     */
    protected function storeConfirmedPassword(): self
    {
        $this->session->set(SessionId::PASSWORD_CONFIRMED_TIMESTAMP, time());

        return $this;
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

        $this->users->setCurrent($user);
    }

    /**
     * Reset properties and session after logout.
     *
     * @return void
     */
    protected function resetAfterLogout(): void
    {
        $this->isAuthenticated = false;

        if ($this->user) {
            $this->users->remove($this->user);
        }

        if ($this->users->hasCurrent()) {
            $this->user = $this->users->getCurrent();
        } else {
            $this->user  = null;
            $this->users = new $this->usersModel();

            $this->unsetSession();
        }
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
     * Ensure a tokenized user is still valid.
     *
     * @param User $user The tokenized user
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    protected function ensureUserValidity(User $user): self
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
}
