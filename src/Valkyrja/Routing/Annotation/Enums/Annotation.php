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

namespace Valkyrja\Routing\Annotation\Enums;

use Valkyrja\Enum\Enums\Enum;

/**
 * Enum Annotation.
 *
 * @author Melech Mizrachi
 */
final class Annotation extends Enum
{
    public const ROUTE                            = 'Route';
    public const ROUTE_ANY                        = 'Route\\Any';
    public const ROUTE_GET                        = 'Route\\Get';
    public const ROUTE_POST                       = 'Route\\Post';
    public const ROUTE_HEAD                       = 'Route\\Head';
    public const ROUTE_PATCH                      = 'Route\\Patch';
    public const ROUTE_PUT                        = 'Route\\Put';
    public const ROUTE_OPTIONS                    = 'Route\\Options';
    public const ROUTE_TRACE                      = 'Route\\Trace';
    public const ROUTE_CONNECT                    = 'Route\\Connect';
    public const ROUTE_DELETE                     = 'Route\\Delete';
    public const ROUTE_REDIRECT                   = 'Route\\Redirect';
    public const ROUTE_REDIRECT_ANY               = 'Route\\Redirect\\Any';
    public const ROUTE_REDIRECT_GET               = 'Route\\Redirect\\Get';
    public const ROUTE_REDIRECT_POST              = 'Route\\Redirect\\Post';
    public const ROUTE_REDIRECT_HEAD              = 'Route\\Redirect\\Head';
    public const ROUTE_REDIRECT_PATCH             = 'Route\\Redirect\\Patch';
    public const ROUTE_REDIRECT_PUT               = 'Route\\Redirect\\Put';
    public const ROUTE_REDIRECT_OPTIONS           = 'Route\\Redirect\\Options';
    public const ROUTE_REDIRECT_TRACE             = 'Route\\Redirect\\Trace';
    public const ROUTE_REDIRECT_CONNECT           = 'Route\\Redirect\\Connect';
    public const ROUTE_REDIRECT_DELETE            = 'Route\\Redirect\\Delete';
    public const ROUTE_REDIRECT_PERMANENT         = 'Route\\Redirect\\Permanent';
    public const ROUTE_REDIRECT_PERMANENT_ANY     = 'Route\\Redirect\\Permanent\\Any';
    public const ROUTE_REDIRECT_PERMANENT_GET     = 'Route\\Redirect\\Permanent\\Get';
    public const ROUTE_REDIRECT_PERMANENT_POST    = 'Route\\Redirect\\Permanent\\Post';
    public const ROUTE_REDIRECT_PERMANENT_HEAD    = 'Route\\Redirect\\Permanent\\Head';
    public const ROUTE_REDIRECT_PERMANENT_PATCH   = 'Route\\Redirect\\Permanent\\Patch';
    public const ROUTE_REDIRECT_PERMANENT_PUT     = 'Route\\Redirect\\Permanent\\Put';
    public const ROUTE_REDIRECT_PERMANENT_OPTIONS = 'Route\\Redirect\\Permanent\\Options';
    public const ROUTE_REDIRECT_PERMANENT_TRACE   = 'Route\\Redirect\\Permanent\\Trace';
    public const ROUTE_REDIRECT_PERMANENT_CONNECT = 'Route\\Redirect\\Permanent\\Connect';
    public const ROUTE_REDIRECT_PERMANENT_DELETE  = 'Route\\Redirect\\Permanent\\Delete';
    public const ROUTE_SECURE                     = 'Route\\Secure';
    public const ROUTE_SECURE_ANY                 = 'Route\\Secure\\Any';
    public const ROUTE_SECURE_GET                 = 'Route\\Secure\\Get';
    public const ROUTE_SECURE_POST                = 'Route\\Secure\\Post';
    public const ROUTE_SECURE_HEAD                = 'Route\\Secure\\Head';
    public const ROUTE_SECURE_PATCH               = 'Route\\Secure\\Patch';
    public const ROUTE_SECURE_PUT                 = 'Route\\Secure\\Put';
    public const ROUTE_SECURE_OPTIONS             = 'Route\\Secure\\Options';
    public const ROUTE_SECURE_TRACE               = 'Route\\Secure\\Trace';
    public const ROUTE_SECURE_CONNECT             = 'Route\\Secure\\Connect';
    public const ROUTE_SECURE_DELETE              = 'Route\\Secure\\Delete';
}
