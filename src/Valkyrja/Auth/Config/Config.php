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
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * Array of properties in the model.
     *
     * @var array
     */
    protected static array $modelProperties = [
        CKP::ADAPTER,
        CKP::USER_ENTITY,
        CKP::ADAPTERS,
        CKP::REPOSITORY,
        CKP::ALWAYS_AUTHENTICATE,
        CKP::KEEP_USER_FRESH,
        CKP::AUTHENTICATE_ROUTE,
        CKP::PASSWORD_CONFIRM_ROUTE,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::ADAPTER                => EnvKey::AUTH_ADAPTER,
        CKP::USER_ENTITY            => EnvKey::AUTH_USER_ENTITY,
        CKP::ADAPTERS               => EnvKey::AUTH_ADAPTERS,
        CKP::REPOSITORY             => EnvKey::AUTH_REPOSITORY,
        CKP::ALWAYS_AUTHENTICATE    => EnvKey::AUTH_ALWAYS_AUTHENTICATE,
        CKP::KEEP_USER_FRESH        => EnvKey::AUTH_KEEP_USER_FRESH,
        CKP::AUTHENTICATE_ROUTE     => EnvKey::AUTH_AUTHENTICATE_ROUTE,
        CKP::PASSWORD_CONFIRM_ROUTE => EnvKey::AUTH_PASSWORD_CONFIRM_ROUTE,
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
     * The adapters.
     *
     * @var string[]
     */
    public array $adapters;

    /**
     * The default repository to use for all entities.
     *
     * @var string
     */
    public string $repository;

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
     * The password confirm route name.
     *
     * @var string
     */
    public string $passwordConfirmRoute;
}
