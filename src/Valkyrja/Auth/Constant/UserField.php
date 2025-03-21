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
 * Constant UserField.
 *
 * @author Melech Mizrachi
 */
final class UserField
{
    public const USERNAME       = 'username';
    public const PASSWORD       = 'password';
    public const EMAIL          = 'email';
    public const RESET_TOKEN    = 'reset_token';
    public const LOGIN_ATTEMPTS = 'login_attempts';
    public const IS_LOCKED      = 'is_locked';
    public const IS_VERIFIED    = 'is_verified';
}
