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

namespace Valkyrja\Auth\Managers;

use Valkyrja\Auth\Adapter;
use Valkyrja\Auth\Auth as Contract;
use Valkyrja\Auth\Exceptions\InvalidAuthenticationException;
use Valkyrja\Auth\Exceptions\InvalidPasswordConfirmationException;
use Valkyrja\Auth\Exceptions\InvalidRegistrationException;
use Valkyrja\Auth\LockableUser;
use Valkyrja\Auth\Repository;
use Valkyrja\Auth\User;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Crypt\Exceptions\CryptException;
use Valkyrja\ORM\ORM;
use Valkyrja\Session\SessionManager;

/**
 * Class Auth.
 *
 * @author Melech Mizrachi
 */
class Auth implements Contract
{
    /**
     * Adapters.
     *
     * @var Adapter[]
     */
    protected static array $adapters = [];

    /**
     * Repositories.
     *
     * @var Repository[]
     */
    protected static array $repositories = [];

    /**
     * The Crypt.
     *
     * @var Crypt
     */
    protected Crypt $crypt;

    /**
     * The ORM.
     *
     * @var ORM
     */
    protected ORM $orm;

    /**
     * The session manager.
     *
     * @var SessionManager
     */
    protected SessionManager $session;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config = [];

    /**
     * The current user.
     *
     * @var User|null
     */
    protected ?User $user = null;

    /**
     * The default adapter.
     *
     * @var mixed|string
     */
    protected string $defaultAdapter;

    /**
     * The default user entity.
     *
     * @var mixed|string
     */
    protected string $defaultUserEntity;

    /**
     * Manager constructor.
     *
     * @param Crypt   $crypt
     * @param ORM     $orm
     * @param SessionManager $session
     * @param array   $config
     */
    public function __construct(Crypt $crypt, ORM $orm, SessionManager $session, array $config)
    {
        $this->crypt             = $crypt;
        $this->orm               = $orm;
        $this->config            = $config;
        $this->session           = $session;
        $this->defaultAdapter    = $this->config['adapter'];
        $this->defaultUserEntity = $this->config['userEntity'];
    }

    /**
     * Set the config.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Set the config.
     *
     * @param array $config
     *
     * @return static
     */
    public function setConfig(array $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get the Crypt.
     *
     * @return Crypt
     */
    public function getCrypt(): Crypt
    {
        return $this->crypt;
    }

    /**
     * Set the Crypt.
     *
     * @param Crypt $crypt
     *
     * @return static
     */
    public function setCrypt(Crypt $crypt): self
    {
        $this->crypt = $crypt;

        return $this;
    }

    /**
     * Get the ORM.
     *
     * @return ORM
     */
    public function getOrm(): ORM
    {
        return $this->orm;
    }

    /**
     * Set the ORM.
     *
     * @param ORM $orm
     *
     * @return static
     */
    public function setOrm(ORM $orm): self
    {
        $this->orm = $orm;

        return $this;
    }

    /**
     * Get the Session.
     *
     * @return SessionManager
     */
    public function getSession(): SessionManager
    {
        return $this->session;
    }

    /**
     * Set the session.
     *
     * @param SessionManager $session
     *
     * @return static
     */
    public function setSession(SessionManager $session): self
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get an adapter by name.
     *
     * @param string|null $name
     *
     * @return Adapter
     */
    public function getAdapter(string $name = null): Adapter
    {
        $name ??= $this->defaultAdapter;

        if (isset(self::$adapters[$name])) {
            return self::$adapters[$name];
        }

        /** @var Adapter $adapter */
        $adapter = $this->config['adapters'][$name];

        return self::$adapters[$name] = $adapter::make($this);
    }

    /**
     * Get a repository by user entity name.
     *
     * @param string|null $user
     *
     * @return Repository
     */
    public function getRepository(string $user = null): Repository
    {
        $user ??= $this->defaultUserEntity;

        if (isset(self::$repositories[$user])) {
            return self::$repositories[$user];
        }

        /** @var User|string $user */
        /** @var Repository $repository */
        $repository = $user::getAuthRepository() ?? $this->config['repository'];

        return self::$repositories[$user] = $repository::make($this, $user);
    }

    /**
     * Get the logged in user.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->getRepository()->getUser();
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
        $this->getRepository()->login($user);

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
        $this->getRepository()->loginWithToken($token);

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
        $this->getRepository()->loginFromSession();

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
        return $this->getRepository()->getToken();
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
        $this->getRepository()->storeToken();

        return $this;
    }

    /**
     * Determine if a user is logged in.
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->getRepository()->isLoggedIn();
    }

    /**
     * Log the current user out.
     *
     * @return static
     */
    public function logout(): self
    {
        $this->getRepository()->logout();

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
        $this->getRepository()->register($user);

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
        $this->getRepository()->forgot($user);

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
        $this->getRepository()->reset($user, $password);

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
        $this->getRepository()->lock($user);

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
        $this->getRepository()->unlock($user);

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
        $this->getRepository()->confirmPassword($password);

        return $this;
    }

    /**
     * Store the confirmed password timestamp in session.
     *
     * @return static
     */
    public function storeConfirmedPassword(): self
    {
        $this->getRepository()->storeConfirmedPassword();

        return $this;
    }
}
