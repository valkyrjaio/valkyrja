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

use App\ORM\Entities\User;
use Valkyrja\Auth\Config\Config as Model;
use Valkyrja\Auth\Constants\ConfigValue;

/**
 * Class Auth.
 */
class Auth extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array $properties = null): void
    {
        $this->adapter              = ConfigValue::ADAPTER;
        $this->userEntity           = User::class;
        $this->repository           = ConfigValue::REPOSITORY;
        $this->gate                 = ConfigValue::GATE;
        $this->policy               = ConfigValue::POLICY;
        $this->alwaysAuthenticate   = false;
        $this->keepUserFresh        = false;
        $this->authenticateRoute    = ConfigValue::AUTHENTICATE_ROUTE;
        $this->authenticateUrl      = ConfigValue::AUTHENTICATE_URL;
        $this->notAuthenticateRoute = ConfigValue::NOT_AUTHENTICATE_ROUTE;
        $this->notAuthenticateUrl   = ConfigValue::NOT_AUTHENTICATE_URL;
        $this->passwordConfirmRoute = ConfigValue::PASSWORD_CONFIRM_ROUTE;
        $this->useSession           = true;
    }
}
