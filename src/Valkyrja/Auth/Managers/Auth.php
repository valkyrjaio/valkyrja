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
use Valkyrja\Auth\Constants\Header;
use Valkyrja\Auth\Exceptions\InvalidAuthenticationException;
use Valkyrja\Auth\Exceptions\InvalidPasswordConfirmationException;
use Valkyrja\Auth\Exceptions\InvalidRegistrationException;
use Valkyrja\Auth\LockableUser;
use Valkyrja\Auth\Repository;
use Valkyrja\Auth\User;
use Valkyrja\Container\Container;
use Valkyrja\Crypt\Exceptions\CryptException;
use Valkyrja\Http\Request;

/**
 * Class Auth.
 *
 * @author Melech Mizrachi
 */
class Auth implements Contract
{
    /**
     * The adapters.
     *
     * @var Adapter[]
     */
    protected static array $adaptersCache = [];

    /**
     * Repositories.
     *
     * @var Repository[]
     */
    protected static array $repositories = [];

    /**
     * The container service.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config = [];

    /**
     * The adapters.
     *
     * @var array
     */
    protected array $adapters = [];

    /**
     * The default adapter.
     *
     * @var string
     */
    protected string $defaultAdapter;

    /**
     * The default repository.
     *
     * @var string
     */
    protected string $defaultRepository;

    /**
     * The default user entity.
     *
     * @var string
     */
    protected string $defaultUserEntity;

    /**
     * Auth constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container         = $container;
        $this->config            = $config;
        $this->adapters          = $this->config['adapters'];
        $this->defaultAdapter    = $this->config['adapter'];
        $this->defaultRepository = $this->config['repository'];
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
     * Get an adapter by name.
     *
     * @param string|null $name [optional] The adapter
     *
     * @return Adapter
     */
    public function getAdapter(string $name = null): Adapter
    {
        $name ??= $this->defaultAdapter;

        return self::$adaptersCache[$name]
            ?? self::$adaptersCache[$name] = $this->container->get(
                $this->adapters[$name],
                [
                    $this->config,
                ]
            );
    }

    /**
     * Get a repository by user entity name.
     *
     * @param string|null $user    [optional] The user
     * @param string|null $adapter [optional] The adapter
     *
     * @return Repository
     */
    public function getRepository(string $user = null, string $adapter = null): Repository
    {
        /** @var User|string $user */
        /** @var Repository $repository */
        $user ??= $this->defaultUserEntity;
        $name = $user::getRepository() ?? $this->defaultRepository;

        return self::$repositories[$name]
            ?? self::$repositories[$name] = $this->container->get(
                $name,
                [
                    $this->getAdapter($adapter),
                    $user,
                    $this->config,
                ]
            );
    }

    /**
     * Get a request with auth token header.
     *
     * @param Request     $request The request
     * @param string|null $user    [optional] The user
     * @param string|null $adapter [optional] The adapter
     *
     * @throws CryptException
     *
     * @return Request
     */
    public function requestWithAuthToken(Request $request, string $user = null, string $adapter = null): Request
    {
        return $request->withHeader(Header::AUTH_TOKEN, $this->getRepository($user, $adapter)->getToken());
    }

    /**
     * Get a request without auth token header.
     *
     * @param Request $request The request
     *
     * @return Request
     */
    public function requestWithoutAuthToken(Request $request): Request
    {
        return $request->withoutHeader(Header::AUTH_TOKEN);
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
     * Set the logged in user.
     *
     * @param User $user The user
     *
     * @return static
     */
    public function setUser(User $user): self
    {
        $this->getRepository()->setUser($user);

        return $this;
    }

    /**
     * Get the user stored in session.
     *
     * @return User
     */
    public function getUserFromSession(): User
    {
        return $this->getRepository()->getUserFromSession();
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
        $this->getRepository()->login($user);

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
        $this->getRepository()->ensureTokenValidity($token);

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
        $this->getRepository()->ensureUserValidity($user);

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
     *
     * @return static
     */
    public function loginWithToken(string $token, bool $store = false): self
    {
        $this->getRepository()->loginWithToken($token, $store);

        return $this;
    }

    /**
     * Log in with a specific user.
     *
     * @param User $user  The user
     * @param bool $store [optional] Whether to store the user in session
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function loginWithUser(User $user, bool $store = false): self
    {
        $this->getRepository()->loginWithUser($user, $store);

        return $this;
    }

    /**
     * Log a user in via tokenized session.
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function loginFromTokenizedSession(): self
    {
        $this->getRepository()->loginFromTokenizedSession();

        return $this;
    }

    /**
     * Log a user in via a user session.
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
     * Get the user token from session.
     *
     * @return string
     */
    public function getTokenFromSession(): string
    {
        return $this->getRepository()->getTokenFromSession();
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
        $this->getRepository()->storeToken($token);

        return $this;
    }

    /**
     * Store the user in session.
     *
     * @param User|null $user [optional] The user to store
     *
     * @return static
     */
    public function storeUser(User $user = null): self
    {
        $this->getRepository()->storeUser($user);

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
     * @param User $user The user
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
     * @param User $user The user
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
     * @param string $resetToken The reset token
     * @param string $password   The password
     *
     * @return static
     */
    public function reset(string $resetToken, string $password): self
    {
        $this->getRepository()->reset($resetToken, $password);

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
        $this->getRepository()->lock($user);

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
        $this->getRepository()->unlock($user);

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
