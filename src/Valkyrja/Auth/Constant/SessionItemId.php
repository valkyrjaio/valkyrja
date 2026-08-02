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

final class SessionItemId
{
    public const string AUTHENTICATED_USERS          = 'auth.users';
    public const string PASSWORD_CONFIRMED_TIMESTAMP = 'auth.passwordConfirmedTimestamp';
}
