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

/**
 * Constant SessionId.
 *
 * @author Melech Mizrachi
 */
final class SessionId
{
    public const USER_TOKEN                   = 'auth.user.token';
    public const USER_TOKENS                  = 'auth.users.tokens';
    public const USER                         = 'auth.user';
    public const USERS                        = 'auth.users';
    public const PASSWORD_CONFIRMED_TIMESTAMP = 'auth.passwordConfirmedTimestamp';
}
