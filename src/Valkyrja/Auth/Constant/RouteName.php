<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Constant;

final class RouteName
{
    public const string AUTHENTICATE     = 'auth.authenticate';
    public const string PASSWORD_CONFIRM = 'auth.password.confirm';
    public const string PASSWORD_FORGOT  = 'auth.password.forgot';
    public const string PASSWORD_REST    = 'auth.password.reset';
    public const string REGISTER         = 'auth.register';
    public const string DASHBOARD        = 'dashboard';
}
