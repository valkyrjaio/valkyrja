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

namespace Valkyrja\Auth\Repository;

use Exception;
use Valkyrja\Auth\Adapter\Contract\Adapter;
use Valkyrja\Auth\Config;
use Valkyrja\Auth\Constant\SessionId;
use Valkyrja\Auth\Entity\Contract\LockableUser;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Exception\InvalidAuthenticationException;
use Valkyrja\Auth\Exception\InvalidCurrentAuthenticationException;
use Valkyrja\Auth\Exception\InvalidPasswordConfirmationException;
use Valkyrja\Auth\Model\Contract\AuthenticatedUsers;
use Valkyrja\Auth\Repository\Contract\Repository as Contract;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Session\Contract\Session as SessionManager;
use Valkyrja\Session\Driver\Contract\Driver as Session;

use function assert;

/**
 * Class Repository.
 *
 * @author Melech Mizrachi
 */
class Repository implements Contract
{
    /**
     * The session.
     *
     * @var Session
     */
    protected Session $session;

    /**
     * The user entity.
     *
     * @var class-string<User>
     */
    protected string $userEntityName;

    /**
     * The authenticated users model.
     *
     * @var class-string<AuthenticatedUsers>
     */
    protected string $usersModel;

    /**
     * The current authenticated user.
     *
     * @var User|null
     */
    protected User|null $user = null;

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
     * Repository constructor.
     *
     * @param Adapter            $adapter The adapter
     * @param SessionManager     $session The session service
     * @param Config|array       $config  The config
     * @param class-string<User> $user    The user class
     */
    public function __construct(
        protected Adapter $adapter,
        SessionManager $session,
        protected Config|array $config,
        string $user
    ) {
        assert(is_a($user, User::class, true));

        $this->session        = $session->use();
        $this->userEntityName = $user;

        $this->usersModel = $user::getAuthCollection() ?? \Valkyrja\Auth\Model\AuthenticatedUsers::class;
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
        return $this->authenticateWithUser($this->getUserFromSession());
    }

    /**
     * @inheritDoc
     */
    public function authenticateFromRequest(ServerRequest $request): static
    {
        /** @var class-string<User> $userClassName */
        $userClassName = $this->userEntityName;
        $requestParams = $request->onlyParsedBody($userClassName::getAuthenticationFields());

        if (empty($requestParams)) {
            throw new InvalidAuthenticationException('No authentication fields');
        }

        $requestParams[$userClassName::getPasswordField()]
            = $request->getParsedBodyParam($userClassName::getPasswordField());

        $user = $userClassName::fromArray($requestParams);

        return $this->authenticate($user);
    }

    /**
     * @inheritDoc
     */
    public function unAuthenticate(User|null $user = null): static
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
        /** @var class-string<User> $userClassName */
        $userClassName = $this->userEntityName;

        foreach ($collection->all() as $key => $user) {
            $collectionAsArray['users'][$key] = $user->asStorableArray();
        }

        $this->session->set($userClassName::getUserSessionId(), $collectionAsArray);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function unsetSession(): static
    {
        /** @var class-string<User> $userClassName */
        $userClassName = $this->userEntityName;

        $this->session->remove($userClassName::getUserSessionId());

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
        if ($this->user === null) {
            throw new InvalidCurrentAuthenticationException('No user currently authenticated.');
        }

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
     * @inheritDoc
     */
    public function getAdapter(): Adapter
    {
        return $this->adapter;
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

        /** @var class-string<User> $userClassName */
        $userClassName = $this->userEntityName;
        $sessionUsers  = $this->session->get($userClassName::getUserSessionId());

        if (! $sessionUsers) {
            $this->resetAfterUnAuthentication();

            throw new InvalidAuthenticationException('No authenticated users.');
        }

        $this->users = $this->usersModel::fromArray($sessionUsers);

        $current = $this->users->getCurrent();

        if (! $current) {
            $this->resetAfterUnAuthentication();

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
    protected function resetAfterUnAuthentication(User|null $user = null): void
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
