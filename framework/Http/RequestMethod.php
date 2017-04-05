<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http;

use Valkyrja\Enum\Enum;

/**
 * Final Class RequestMethod
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
final class RequestMethod extends Enum
{
    public const GET     = 'GET';
    public const POST    = 'POST';
    public const PUT     = 'PUT';
    public const PATCH   = 'PATCH';
    public const DELETE  = 'DELETE';
    public const HEAD    = 'HEAD';
    public const PURGE   = 'PURGE';
    public const OPTIONS = 'OPTIONS';
    public const TRACE   = 'TRACE';
    public const CONNECT = 'CONNECT';
}
