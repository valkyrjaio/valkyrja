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
 * Class ConfigName.
 *
 * @author Melech Mizrachi
 */
final class ConfigName
{
    public const string DEFAULT_ADAPTER            = 'defaultAdapter';
    public const string DEFAULT_USER_ENTITY        = 'defaultUserEntity';
    public const string DEFAULT_REPOSITORY         = 'defaultRepository';
    public const string DEFAULT_GATE               = 'defaultGate';
    public const string DEFAULT_POLICY             = 'defaultPolicy';
    public const string SHOULD_ALWAYS_AUTHENTICATE = 'shouldAlwaysAuthenticate';
    public const string SHOULD_KEEP_USER_FRESH     = 'shouldKeepUserFresh';
    public const string SHOULD_USE_SESSION         = 'shouldUseSession';
    public const string AUTHENTICATE_ROUTE         = 'authenticateRoute';
    public const string AUTHENTICATE_URL           = 'authenticateUrl';
    public const string NOT_AUTHENTICATED_ROUTE    = 'notAuthenticatedRoute';
    public const string NOT_AUTHENTICATE_URL       = 'notAuthenticatedUrl';
    public const string PASSWORD_CONFIRM_ROUTE     = 'passwordConfirmRoute';
    public const string PASSWORD_TIMEOUT           = 'passwordTimeout';
}
