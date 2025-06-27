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

namespace Valkyrja\Auth;

use Valkyrja\Auth\Adapter\Contract\Adapter as AdapterContract;
use Valkyrja\Auth\Adapter\ORMAdapter;
use Valkyrja\Auth\Constant\ConfigName;
use Valkyrja\Auth\Constant\EnvName;
use Valkyrja\Auth\Constant\RouteName;
use Valkyrja\Auth\Entity\Contract\User as UserContract;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Auth\Gate\Contract\Gate as GateContract;
use Valkyrja\Auth\Gate\Gate;
use Valkyrja\Auth\Policy\Contract\Policy as PolicyContract;
use Valkyrja\Auth\Policy\UserPermissiblePolicy;
use Valkyrja\Auth\Repository\Contract\Repository as RepositoryContract;
use Valkyrja\Auth\Repository\Repository;
use Valkyrja\Config\Config as ParentConfig;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::DEFAULT_ADAPTER            => EnvName::DEFAULT_ADAPTER,
        ConfigName::DEFAULT_USER_ENTITY        => EnvName::DEFAULT_USER_ENTITY,
        ConfigName::DEFAULT_REPOSITORY         => EnvName::DEFAULT_REPOSITORY,
        ConfigName::DEFAULT_GATE               => EnvName::DEFAULT_GATE,
        ConfigName::DEFAULT_POLICY             => EnvName::DEFAULT_POLICY,
        ConfigName::SHOULD_ALWAYS_AUTHENTICATE => EnvName::SHOULD_ALWAYS_AUTHENTICATE,
        ConfigName::SHOULD_KEEP_USER_FRESH     => EnvName::SHOULD_KEEP_USER_FRESH,
        ConfigName::SHOULD_USE_SESSION         => EnvName::SHOULD_USE_SESSION,
        ConfigName::AUTHENTICATE_ROUTE         => EnvName::AUTHENTICATE_ROUTE,
        ConfigName::AUTHENTICATE_URL           => EnvName::AUTHENTICATE_URL,
        ConfigName::NOT_AUTHENTICATED_ROUTE    => EnvName::NOT_AUTHENTICATED_ROUTE,
        ConfigName::NOT_AUTHENTICATE_URL       => EnvName::NOT_AUTHENTICATE_URL,
        ConfigName::PASSWORD_CONFIRM_ROUTE     => EnvName::PASSWORD_CONFIRM_ROUTE,
        ConfigName::PASSWORD_TIMEOUT           => EnvName::PASSWORD_TIMEOUT,
    ];

    /**
     * @param class-string<AdapterContract>    $defaultAdapter    The default adapter
     * @param class-string<UserContract>       $defaultUserEntity The default user entity
     * @param class-string<RepositoryContract> $defaultRepository The default repository
     * @param class-string<GateContract>       $defaultGate       The default gate
     * @param class-string<PolicyContract>     $defaultPolicy     The default gate
     */
    public function __construct(
        public string $defaultAdapter = ORMAdapter::class,
        public string $defaultUserEntity = User::class,
        public string $defaultRepository = Repository::class,
        public string $defaultGate = Gate::class,
        public string $defaultPolicy = UserPermissiblePolicy::class,
        public bool $shouldAlwaysAuthenticate = false,
        public bool $shouldKeepUserFresh = false,
        public bool $shouldUseSession = true,
        public string $authenticateRoute = RouteName::AUTHENTICATE,
        public string|null $authenticateUrl = null,
        public string $notAuthenticatedRoute = RouteName::DASHBOARD,
        public string|null $notAuthenticatedUrl = null,
        public string $passwordConfirmRoute = RouteName::PASSWORD_CONFIRM,
        public int $passwordTimeout = 10800,
    ) {
    }
}
