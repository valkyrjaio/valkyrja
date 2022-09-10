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

use Exception;
use Valkyrja\Auth\Adapter;
use Valkyrja\Auth\Auth as Contract;
use Valkyrja\Auth\AuthenticatedUsers;
use Valkyrja\Auth\Constants\HeaderValue;
use Valkyrja\Auth\Factory;
use Valkyrja\Auth\Gate;
use Valkyrja\Auth\LockableUser;
use Valkyrja\Auth\Repository;
use Valkyrja\Auth\User;
use Valkyrja\Http\Constants\Header;
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
    protected static array $adapters = [];

    /**
     * Repositories.
     *
     * @var Repository[]
     */
    protected static array $repositories = [];

    /**
     * The gates cache.
     *
     * @var Gate[]
     */
    protected static array $gates = [];

    /**
     * The factory service.
     *
     * @var Factory
     */
    protected Factory $factory;

    /**
     * The request.
     *
     * @var Request
     */
    protected Request $request;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config = [];

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
     * The default gate.
     *
     * @var string
     */
    protected string $defaultGate;

    /**
     * The default user entity.
     *
     * @var string
     */
    protected string $defaultUserEntity;

    /**
     * Auth constructor.
     *
     * @param Factory $factory The factory
     * @param Request $request The request
     * @param array   $config  The config
     */
    public function __construct(Factory $factory, Request $request, array $config)
    {
        $this->factory           = $factory;
        $this->request           = $request;
        $this->config            = $config;
        $this->defaultAdapter    = $config['adapter'];
        $this->defaultRepository = $config['repository'];
        $this->defaultGate       = $config['gate'];
        $this->defaultUserEntity = $config['userEntity'];

        $this->tryAuthenticating();
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function getAdapter(string $name = null): Adapter
    {
        $name ??= $this->defaultAdapter;

        return self::$adapters[$name]
            ?? self::$adapters[$name] = $this->factory->createAdapter($name, $this->config);
    }

    /**
     * @inheritDoc
     */
    public function getRepository(string $user = null, string $adapter = null): Repository
    {
        /** @var User|string $user */
        /** @var Repository $repository */
        $user ??= $this->defaultUserEntity;
        $name = $user::getAuthRepository() ?? $this->defaultRepository;

        return self::$repositories[$name]
            ?? self::$repositories[$name] = $this->factory->createRepository(
                $this->getAdapter($adapter),
                $name,
                $user,
                $this->config
            );
    }

    /**
     * @inheritDoc
     */
    public function getGate(string $name = null, string $user = null, string $adapter = null): Gate
    {
        $name ??= $this->defaultGate;

        return self::$gates[$name]
            ?? self::$gates[$name] = $this->factory->createGate(
                $this->getRepository($user, $adapter),
                $name,
                $this->config
            );
    }

    /**
     * @inheritDoc
     */
    public function requestWithAuthToken(Request $request, string $user = null, string $adapter = null): Request
    {
        return $request->withHeader(
            Header::AUTHORIZATION,
            HeaderValue::BEARER . ' ' . $this->getRepository($user, $adapter)->getToken()
        );
    }

    /**
     * @inheritDoc
     */
    public function requestWithoutAuthToken(Request $request): Request
    {
        return $request->withoutHeader(Header::AUTHORIZATION);
    }

    /**
     * @inheritDoc
     */
    public function isAuthenticated(): bool
    {
        return $this->getRepository()->isAuthenticated();
    }

    /**
     * @inheritDoc
     */
    public function getUser(): User
    {
        return $this->getRepository()->getUser();
    }

    /**
     * @inheritDoc
     */
    public function setUser(User $user): static
    {
        $this->getRepository()->setUser($user);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUsers(): AuthenticatedUsers
    {
        return $this->getRepository()->getUsers();
    }

    /**
     * @inheritDoc
     */
    public function setUsers(AuthenticatedUsers $users): static
    {
        $this->getRepository()->setUsers($users);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(User $user): static
    {
        $this->getRepository()->authenticate($user);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function authenticateFromSession(): static
    {
        $this->getRepository()->authenticateFromSession();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function authenticateFromRequest(Request $request): static
    {
        $this->getRepository()->authenticateFromRequest($request);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function unAuthenticate(User $user = null): static
    {
        $this->getRepository()->unAuthenticate($user);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSession(): static
    {
        $this->getRepository()->setSession();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function unsetSession(): static
    {
        $this->getRepository()->unsetSession();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function register(User $user): static
    {
        $this->getRepository()->register($user);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function forgot(User $user): static
    {
        $this->getRepository()->forgot($user);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function reset(string $resetToken, string $password): static
    {
        $this->getRepository()->reset($resetToken, $password);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function lock(LockableUser $user): static
    {
        $this->getRepository()->lock($user);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function unlock(LockableUser $user): static
    {
        $this->getRepository()->unlock($user);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function confirmPassword(string $password): static
    {
        $this->getRepository()->confirmPassword($password);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isReAuthenticationRequired(): bool
    {
        return $this->getRepository()->isReAuthenticationRequired();
    }

    /**
     * Try authenticating.
     *
     * @return void
     */
    protected function tryAuthenticating(): void
    {
        try {
            $repository = $this->getRepository();

            if ($this->config['useSession']) {
                // Try to authenticate from session
                $repository->authenticateFromSession();

                return;
            }

            // Try to login from the user session
            $repository->authenticateFromRequest($this->request);
        } catch (Exception) {
        }
    }
}
