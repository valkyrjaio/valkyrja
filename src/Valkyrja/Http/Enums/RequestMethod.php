<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Enums;

use Valkyrja\Enum\Enum;

/**
 * Enum RequestMethod.
 *
 * @author Melech Mizrachi
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

    protected const VALUES = [
        self::GET     => self::GET,
        self::POST    => self::POST,
        self::PUT     => self::PUT,
        self::PATCH   => self::PATCH,
        self::DELETE  => self::DELETE,
        self::HEAD    => self::HEAD,
        self::PURGE   => self::PURGE,
        self::OPTIONS => self::OPTIONS,
        self::TRACE   => self::TRACE,
        self::CONNECT => self::CONNECT,
    ];
}
