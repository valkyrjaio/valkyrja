<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Annotation\Enums;

use Valkyrja\Enum\Enum;
use Valkyrja\Routing\Annotation\Models\Any;
use Valkyrja\Routing\Annotation\Models\Connect;
use Valkyrja\Routing\Annotation\Models\Delete;
use Valkyrja\Routing\Annotation\Models\Get;
use Valkyrja\Routing\Annotation\Models\Head;
use Valkyrja\Routing\Annotation\Models\Options;
use Valkyrja\Routing\Annotation\Models\Patch;
use Valkyrja\Routing\Annotation\Models\Post;
use Valkyrja\Routing\Annotation\Models\Put;
use Valkyrja\Routing\Annotation\Models\Redirect;
use Valkyrja\Routing\Annotation\Models\Redirect\Permanent;
use Valkyrja\Routing\Annotation\Models\Route;
use Valkyrja\Routing\Annotation\Models\Secure;
use Valkyrja\Routing\Annotation\Models\Trace;

/**
 * Enum AnnotationClass.
 *
 * @author Melech Mizrachi
 */
final class AnnotationClass extends Enum
{
    public const ROUTE                            = Route::class;
    public const ROUTE_ANY                        = Any::class;
    public const ROUTE_GET                        = Get::class;
    public const ROUTE_POST                       = Post::class;
    public const ROUTE_HEAD                       = Head::class;
    public const ROUTE_PATCH                      = Patch::class;
    public const ROUTE_PUT                        = Put::class;
    public const ROUTE_OPTIONS                    = Options::class;
    public const ROUTE_TRACE                      = Trace::class;
    public const ROUTE_CONNECT                    = Connect::class;
    public const ROUTE_DELETE                     = Delete::class;
    public const ROUTE_REDIRECT                   = Redirect::class;
    public const ROUTE_REDIRECT_ANY               = Redirect\Any::class;
    public const ROUTE_REDIRECT_GET               = Redirect\Get::class;
    public const ROUTE_REDIRECT_POST              = Redirect\Post::class;
    public const ROUTE_REDIRECT_HEAD              = Redirect\Head::class;
    public const ROUTE_REDIRECT_PATCH             = Redirect\Patch::class;
    public const ROUTE_REDIRECT_PUT               = Redirect\Put::class;
    public const ROUTE_REDIRECT_OPTIONS           = Redirect\Options::class;
    public const ROUTE_REDIRECT_TRACE             = Redirect\Trace::class;
    public const ROUTE_REDIRECT_CONNECT           = Redirect\Connect::class;
    public const ROUTE_REDIRECT_DELETE            = Redirect\Delete::class;
    public const ROUTE_REDIRECT_PERMANENT         = Permanent::class;
    public const ROUTE_REDIRECT_PERMANENT_ANY     = Permanent\Any::class;
    public const ROUTE_REDIRECT_PERMANENT_GET     = Permanent\Get::class;
    public const ROUTE_REDIRECT_PERMANENT_POST    = Permanent\Post::class;
    public const ROUTE_REDIRECT_PERMANENT_HEAD    = Permanent\Head::class;
    public const ROUTE_REDIRECT_PERMANENT_PATCH   = Permanent\Patch::class;
    public const ROUTE_REDIRECT_PERMANENT_PUT     = Permanent\Put::class;
    public const ROUTE_REDIRECT_PERMANENT_OPTIONS = Permanent\Options::class;
    public const ROUTE_REDIRECT_PERMANENT_TRACE   = Permanent\Trace::class;
    public const ROUTE_REDIRECT_PERMANENT_CONNECT = Permanent\Connect::class;
    public const ROUTE_REDIRECT_PERMANENT_DELETE  = Permanent\Delete::class;
    public const ROUTE_SECURE                     = Secure::class;
    public const ROUTE_SECURE_ANY                 = Secure\Any::class;
    public const ROUTE_SECURE_GET                 = Secure\Get::class;
    public const ROUTE_SECURE_POST                = Secure\Post::class;
    public const ROUTE_SECURE_HEAD                = Secure\Head::class;
    public const ROUTE_SECURE_PATCH               = Secure\Patch::class;
    public const ROUTE_SECURE_PUT                 = Secure\Put::class;
    public const ROUTE_SECURE_OPTIONS             = Secure\Options::class;
    public const ROUTE_SECURE_TRACE               = Secure\Trace::class;
    public const ROUTE_SECURE_CONNECT             = Secure\Connect::class;
    public const ROUTE_SECURE_DELETE              = Secure\Delete::class;
}
