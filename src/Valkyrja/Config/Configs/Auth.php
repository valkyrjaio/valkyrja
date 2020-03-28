<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Configs;

use Valkyrja\Auth\Enums\ConfigValue;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Model;

use function env;

/**
 * Class Auth.
 *
 * @author Melech Mizrachi
 */
class Auth extends Model
{
    /**
     * The default adapter.
     *
     * @var string
     */
    public string $defaultAdapter;

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

    /**
     * Auth constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setAdapter(ConfigValue::ADAPTER);
        $this->setUserEntity(ConfigValue::USER);
        $this->setAdapters(ConfigValue::ADAPTERS);
        $this->setRepository(ConfigValue::REPOSITORY);
        $this->setAlwaysAuthenticate(ConfigValue::ALWAYS_AUTHENTICATE);
        $this->setKeepUserFresh(ConfigValue::KEEP_USER_FRESH);
        $this->setAuthenticateRoute(ConfigValue::AUTHENTICATE_ROUTE);
        $this->setPasswordConfirmRoute(ConfigValue::PASSWORD_CONFIRM_ROUTE);
    }

    /**
     * Set the default adapter.
     *
     * @param string $adapter [optional] The default adapter
     *
     * @return void
     */
    protected function setAdapter(string $adapter = ConfigValue::ADAPTER): void
    {
        $this->defaultAdapter = (string) env(EnvKey::AUTH_ADAPTER, $adapter);
    }

    /**
     * Set the default user entity.
     *
     * @param string $defaultUserEntity [optional] The default user entity
     *
     * @return void
     */
    protected function setUserEntity(string $defaultUserEntity = ConfigValue::USER): void
    {
        $this->userEntity = (string) env(EnvKey::AUTH_USER_ENTITY, $defaultUserEntity);
    }

    /**
     * Set the adapters.
     *
     * @param array $adapters [optional] The adapters
     *
     * @return void
     */
    protected function setAdapters(array $adapters = ConfigValue::ADAPTERS): void
    {
        $this->adapters = (array) env(EnvKey::AUTH_ADAPTERS, $adapters);
    }

    /**
     * Set the default repository to use for all entities.
     *
     * @param string $repository [optional] The default repository
     *
     * @return void
     */
    protected function setRepository(string $repository = ConfigValue::REPOSITORY): void
    {
        $this->repository = (string) env(EnvKey::AUTH_REPOSITORY, $repository);
    }

    /**
     * Set the flag to determine whether to always authenticate a user (regardless if a session exists).
     *
     * @param bool $alwaysAuthenticate [optional] The flag
     *
     * @return void
     */
    protected function setAlwaysAuthenticate(bool $alwaysAuthenticate = ConfigValue::ALWAYS_AUTHENTICATE): void
    {
        $this->alwaysAuthenticate = (bool) env(EnvKey::AUTH_ALWAYS_AUTHENTICATE, $alwaysAuthenticate);
    }

    /**
     * Set the flag to determine whether to always keep a user fresh by querying the data store.
     *
     * @param bool $keepUserFresh [optional] THe flag
     *
     * @return void
     */
    protected function setKeepUserFresh(bool $keepUserFresh = ConfigValue::KEEP_USER_FRESH): void
    {
        $this->keepUserFresh = (bool) env(EnvKey::AUTH_KEEP_USER_FRESH, $keepUserFresh);
    }

    /**
     * Set the authenticate route name.
     *
     * @param string $authenticateRoute [optional] The authenticate route name
     *
     * @return void
     */
    protected function setAuthenticateRoute(string $authenticateRoute = ConfigValue::AUTHENTICATE_ROUTE): void
    {
        $this->authenticateRoute = (string) env(EnvKey::AUTH_AUTHENTICATE_ROUTE, $authenticateRoute);
    }

    /**
     * Set the confirm password route name.
     *
     * @param string $confirmPasswordRoute [optional] The confirm password route name
     *
     * @return void
     */
    protected function setPasswordConfirmRoute(string $confirmPasswordRoute = ConfigValue::PASSWORD_CONFIRM_ROUTE): void
    {
        $this->passwordConfirmRoute = (string) env(EnvKey::AUTH_PASSWORD_CONFIRM_ROUTE, $confirmPasswordRoute);
    }
}
