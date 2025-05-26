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
    public const DEFAULT_ADAPTER            = 'defaultAdapter';
    public const DEFAULT_USER_ENTITY        = 'defaultUserEntity';
    public const DEFAULT_REPOSITORY         = 'defaultRepository';
    public const DEFAULT_GATE               = 'defaultGate';
    public const DEFAULT_POLICY             = 'defaultPolicy';
    public const SHOULD_ALWAYS_AUTHENTICATE = 'shouldAlwaysAuthenticate';
    public const SHOULD_KEEP_USER_FRESH     = 'shouldKeepUserFresh';
    public const SHOULD_USE_SESSION         = 'shouldUseSession';
    public const AUTHENTICATE_ROUTE         = 'authenticateRoute';
    public const AUTHENTICATE_URL           = 'authenticateUrl';
    public const NOT_AUTHENTICATED_ROUTE    = 'notAuthenticatedRoute';
    public const NOT_AUTHENTICATE_URL       = 'notAuthenticatedUrl';
    public const PASSWORD_CONFIRM_ROUTE     = 'passwordConfirmRoute';
    public const PASSWORD_TIMEOUT           = 'passwordTimeout';
}
