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
 * Interface RequestMethod
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
interface RequestMethod
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

    public const ACCEPTED_TYPES = [
        self::GET,
        self::POST,
        self::PUT,
        self::PATCH,
        self::DELETE,
        self::HEAD,
    ];
}
