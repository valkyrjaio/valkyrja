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
    public const string DEFAULT_ADAPTER            = 'AUTH_DEFAULT_ADAPTER';
    public const string DEFAULT_USER_ENTITY        = 'AUTH_DEFAULT_USER_ENTITY';
    public const string DEFAULT_REPOSITORY         = 'AUTH_DEFAULT_REPOSITORY';
    public const string DEFAULT_GATE               = 'AUTH_DEFAULT_GATE';
    public const string DEFAULT_POLICY             = 'AUTH_DEFAULT_POLICY';
    public const string SHOULD_ALWAYS_AUTHENTICATE = 'AUTH_SHOULD_ALWAYS_AUTHENTICATE';
    public const string SHOULD_KEEP_USER_FRESH     = 'AUTH_SHOULD_KEEP_USER_FRESH';
    public const string SHOULD_USE_SESSION         = 'AUTH_SHOULD_USE_SESSION';
    public const string AUTHENTICATE_ROUTE         = 'AUTH_AUTHENTICATE_ROUTE';
    public const string AUTHENTICATE_URL           = 'AUTH_AUTHENTICATE_URL';
    public const string NOT_AUTHENTICATED_ROUTE    = 'AUTH_NOT_AUTHENTICATED_ROUTE';
    public const string NOT_AUTHENTICATE_URL       = 'AUTH_NOT_AUTHENTICATED_URL';
    public const string PASSWORD_CONFIRM_ROUTE     = 'AUTH_PASSWORD_CONFIRM_ROUTE';
    public const string PASSWORD_TIMEOUT           = 'AUTH_PASSWORD_TIMEOUT';
}
