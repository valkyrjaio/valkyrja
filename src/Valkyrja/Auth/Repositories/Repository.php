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
use Valkyrja\Auth\Exceptions\InvalidCurrentAuthenticationException;
use Valkyrja\Auth\Exceptions\InvalidPasswordConfirmationException;
use Valkyrja\Auth\LockableUser;
use Valkyrja\Auth\Repository as Contract;
use Valkyrja\Auth\User;
use Valkyrja\Http\Request;
use Valkyrja\Session\Driver as Session;
use Valkyrja\Session\Session as SessionManager;
use Valkyrja\Support\Type\Cls;

/**
 * Class Repository.
 *
 * @author Melech Mizrachi
 */
class Repository implements Contract
{
    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * The session.
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
     * @param Adapter        $adapter The adapter
     * @param SessionManager $session The session service
     * @param array          $config  The config
     * @param string         $user    The user class
     */
    public function __construct(Adapter $adapter, SessionManager $session, array $config, string $user)
    {
        Cls::validateInherits($user, User::class);

        $this->config         = $config;
        $this->adapter        = $adapter;
        $this->session        = $session->use();
        $this->userEntityName = $user;

        $this->usersModel = $this->userEntityName::getAuthCollection();
        $this->users      = new $this->usersModel();
    }

    /**
     * @inheritDoc
     */
    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    /**
     * @inheritDoc
     */
    public function getUser(): User
    {
        return $this->user ?? new $this->userEntityName();
    }

    /**
     * @inheritDoc
     */
    public function setUser(User $user): static
    {
        $this->setAuthenticatedUser($user);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUsers(): AuthenticatedUsers
    {
        if ($this->config['keepUserFresh']) {
            foreach ($this->users->all() as $user) {
                $dbUser = $this->adapter->retrieveById($user);

                $user->updateProperties($dbUser->asStorableArray());
            }
        }

        return $this->users;
    }

    /**
     * @inheritDoc
     */
    public function setUsers(AuthenticatedUsers $users): static
    {
        $this->users = $users;
        $this->user  = $users->getCurrent();

        $this->isAuthenticated = $users->hasCurrent();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(User $user): static
    {
        if (! $this->adapter->authenticate($user)) {
            throw new InvalidAuthenticationException('Invalid user credentials.');
        }

        $this->setAuthenticatedUser($user);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function authenticateFromSession(): static
    {
        if (! $user = $this->getUserFromSession()) {
            $this->resetAfterUnAuthentication();

            throw new InvalidAuthenticationException('No user session exists.');
        }

        return $this->authenticateWithUser($user);
    }

    /**
     * @inheritDoc
     */
    public function authenticateFromRequest(Request $request): static
    {
        $requestParams = $request->onlyParsedBody($this->userEntityName::getAuthenticationFields());

        $requestParams[$this->userEntityName::getPasswordField()] =
            $request->getParsedBodyParam($this->userEntityName::getPasswordField());

        $user = $this->userEntityName::fromArray($requestParams);

        return $this->authenticate($user);
    }

    /**
     * @inheritDoc
     */
    public function unAuthenticate(User $user = null): static
    {
        if ($this->isAuthenticated) {
            $this->resetAfterUnAuthentication($user);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSession(): static
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
     * @inheritDoc
     */
    public function unsetSession(): static
    {
        $this->session->remove($this->userEntityName::getUserSessionId());

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function register(User $user): static
    {
        $this->adapter->create($user);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function forgot(User $user): static
    {
        $dbUser = $this->adapter->retrieve($user);

        if (! $dbUser) {
            throw new InvalidAuthenticationException('No user found.');
        }

        $this->adapter->updateResetToken($dbUser);

        $user->updateProperties($dbUser->asStorableArray());

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function reset(string $resetToken, string $password): static
    {
        if (! $user = $this->adapter->retrieveByResetToken(new $this->userEntityName(), $resetToken)) {
            throw new InvalidAuthenticationException('Invalid reset token.');
        }

        $this->adapter->updatePassword($user, $password);
        $this->setUser($user);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function lock(LockableUser $user): static
    {
        $this->lockUnlock($user, true);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function unlock(LockableUser $user): static
    {
        $this->lockUnlock($user, false);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function confirmPassword(string $password): static
    {
        if (! $this->adapter->verifyPassword($this->user, $password)) {
            throw new InvalidPasswordConfirmationException('Invalid password confirmation.');
        }

        return $this;
    }

    /**
     * @inheritDoc
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
        if ($this->user !== null) {
            return $this->user;
        }

        $sessionUsers = $this->session->get($this->userEntityName::getUserSessionId());

        if (! $sessionUsers) {
            throw new InvalidAuthenticationException('No authenticated users.');
        }

        $this->users = $this->usersModel::fromArray($sessionUsers);

        $current = $this->users->getCurrent();

        if (! $current) {
            throw new InvalidCurrentAuthenticationException('No current authenticated user.');
            // Debate whether to use this instead since there are session users so just take the first one... but that
            // could be incorrect if that should not be the current user amongst a plethora of possibilities. It should
            // be up to the app developer on how to handle this.
            //
            // $current = $this->users->all()[0];
            //
            // $this->users->setCurrent($current);
        }

        return $this->user = $current;
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
    protected function authenticateWithUser(User $user): static
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
     * Reset properties and session after un-authentication.
     *
     * @param User|null $user [optional] The user to un-authenticate
     *
     * @return void
     */
    protected function resetAfterUnAuthentication(User $user = null): void
    {
        $this->isAuthenticated = false;

        if ($user) {
            $this->users->remove($user);
        }

        if ($user && $this->users->hasCurrent()) {
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
    protected function ensureUserValidity(User $user): static
    {
        $passwordField = $user::getPasswordField();
        // Get a fresh user from the database
        $dbUser = $this->adapter->retrieveById($user);

        // If the db password does not match the tokenized user password the token is no longer valid
        if ($dbUser->__get($passwordField) !== $user->__get($passwordField)) {
            $this->resetAfterUnAuthentication();

            throw new InvalidAuthenticationException('User is no longer valid.');
        }

        if ($this->config['keepUserFresh']) {
            $this->setAuthenticatedUser($dbUser);
        }

        return $this;
    }
}
