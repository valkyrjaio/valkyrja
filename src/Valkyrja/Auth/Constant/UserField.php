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

final class UserField
{
    public const string USERNAME       = 'username';
    public const string PASSWORD       = 'password';
    public const string EMAIL          = 'email';
    public const string RESET_TOKEN    = 'reset_token';
    public const string LOGIN_ATTEMPTS = 'login_attempts';
    public const string IS_LOCKED      = 'is_locked';
    public const string IS_VERIFIED    = 'is_verified';
}
