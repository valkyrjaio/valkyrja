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

/**
 * Final Class RequestMethod
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
final class RequestMethod
{
    /**
     * Get request method constant.
     *
     * @constant string
     */
    public const GET = 'GET';

    /**
     * Post request method constant.
     *
     * @constant string
     */
    public const POST = 'POST';

    /**
     * Put request method constant.
     *
     * @constant string
     */
    public const PUT = 'PUT';

    /**
     * Patch request method constant.
     *
     * @constant string
     */
    public const PATCH = 'PATCH';

    /**
     * Delete request method constant.
     *
     * @constant string
     */
    public const DELETE = 'DELETE';

    /**
     * Head request method constant.
     *
     * @constant string
     */
    public const HEAD = 'HEAD';

    /**
     * Purge request method constant.
     *
     * @constant string
     */
    public const PURGE = 'PURGE';

    /**
     * Options request method constant.
     *
     * @constant string
     */
    public const OPTIONS = 'OPTIONS';

    /**
     * Trace request method constant.
     *
     * @constant string
     */
    public const TRACE = 'TRACE';

    /**
     * Connect request method constant.
     *
     * @constant string
     */
    public const CONNECT = 'CONNECT';

    /**
     * Accepted method types.
     *
     * @constant array
     */
    public const ACCEPTED_TYPES = [
        self::GET,
        self::POST,
        self::PUT,
        self::PATCH,
        self::DELETE,
        self::HEAD,
    ];
}
