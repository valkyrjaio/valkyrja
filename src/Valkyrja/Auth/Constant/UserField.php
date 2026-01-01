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
 */
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
