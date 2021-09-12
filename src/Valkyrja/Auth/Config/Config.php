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

namespace Valkyrja\Auth\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     */
    protected static array $envKeys = [
        CKP::ADAPTER                => EnvKey::AUTH_ADAPTER,
        CKP::USER_ENTITY            => EnvKey::AUTH_USER_ENTITY,
        CKP::REPOSITORY             => EnvKey::AUTH_REPOSITORY,
        CKP::GATE                   => EnvKey::AUTH_GATE,
        CKP::POLICY                 => EnvKey::AUTH_POLICY,
        CKP::ALWAYS_AUTHENTICATE    => EnvKey::AUTH_ALWAYS_AUTHENTICATE,
        CKP::KEEP_USER_FRESH        => EnvKey::AUTH_KEEP_USER_FRESH,
        CKP::AUTHENTICATE_ROUTE     => EnvKey::AUTH_AUTHENTICATE_ROUTE,
        CKP::PASSWORD_CONFIRM_ROUTE => EnvKey::AUTH_PASSWORD_CONFIRM_ROUTE,
        CKP::USE_SESSION            => EnvKey::AUTH_USE_SESSION,
    ];

    /**
     * The default adapter.
     *
     * @var string
     */
    public string $adapter;

    /**
     * The default user entity.
     *
     * @var string
     */
    public string $userEntity;

    /**
     * The default repository to use for all entities.
     *
     * @var string
     */
    public string $repository;

    /**
     * The default gate to use for authorization checks.
     *
     * @var string
     */
    public string $gate;

    /**
     * The default policy to use for authorization checks.
     *
     * @var string
     */
    public string $policy;

    /**
     * Whether to always authenticate a user (regardless if a session exists).
     *
     * @var bool
     */
    public bool $alwaysAuthenticate;

    /**
     * Whether to always keep a user fresh by querying the data store.
     *
     * @var bool
     */
    public bool $keepUserFresh;

    /**
     * The authenticate route name.
     *
     * @var string
     */
    public string $authenticateRoute;

    /**
     * The authenticate url.
     *
     * @var string|null
     */
    public ?string $authenticateUrl;

    /**
     * The not authenticated route name.
     *
     * @var string
     */
    public string $notAuthenticateRoute;

    /**
     * The not authenticated url.
     *
     * @var string|null
     */
    public ?string $notAuthenticateUrl;

    /**
     * The password confirm route name.
     *
     * @var string
     */
    public string $passwordConfirmRoute;

    /**
     * Whether to authenticate using a session.
     *
     * @var bool
     */
    public bool $useSession;
}
