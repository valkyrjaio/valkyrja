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

namespace Valkyrja\Auth\Constant;

final class SessionId
{
    public const string AUTHENTICATED_USERS          = 'authenticated.users';
    public const string PASSWORD_CONFIRMED_TIMESTAMP = 'auth.passwordConfirmedTimestamp';
}
