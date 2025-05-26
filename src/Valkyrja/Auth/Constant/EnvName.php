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
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const DEFAULT_ADAPTER            = 'AUTH_DEFAULT_ADAPTER';
    public const DEFAULT_USER_ENTITY        = 'AUTH_DEFAULT_USER_ENTITY';
    public const DEFAULT_REPOSITORY         = 'AUTH_DEFAULT_REPOSITORY';
    public const DEFAULT_GATE               = 'AUTH_DEFAULT_GATE';
    public const DEFAULT_POLICY             = 'AUTH_DEFAULT_POLICY';
    public const SHOULD_ALWAYS_AUTHENTICATE = 'AUTH_SHOULD_ALWAYS_AUTHENTICATE';
    public const SHOULD_KEEP_USER_FRESH     = 'AUTH_SHOULD_KEEP_USER_FRESH';
    public const SHOULD_USE_SESSION         = 'AUTH_SHOULD_USE_SESSION';
    public const AUTHENTICATE_ROUTE         = 'AUTH_AUTHENTICATE_ROUTE';
    public const AUTHENTICATE_URL           = 'AUTH_AUTHENTICATE_URL';
    public const NOT_AUTHENTICATED_ROUTE    = 'AUTH_NOT_AUTHENTICATED_ROUTE';
    public const NOT_AUTHENTICATE_URL       = 'AUTH_NOT_AUTHENTICATED_URL';
    public const PASSWORD_CONFIRM_ROUTE     = 'AUTH_PASSWORD_CONFIRM_ROUTE';
    public const PASSWORD_TIMEOUT           = 'AUTH_PASSWORD_TIMEOUT';
}
