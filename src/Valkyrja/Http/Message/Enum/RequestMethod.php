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

namespace Valkyrja\Http\Message\Enum;

/**
 * Enum RequestMethod.
 *
 * @author Melech Mizrachi
 *
 * @see    https://www.rfc-editor.org/rfc/rfc9110.html#name-method-definitions
 */
enum RequestMethod: string
{
    /**
     * @see https://www.rfc-editor.org/rfc/rfc9110.html#name-get
     */
    case GET = 'GET';

    /**
     * @see https://www.rfc-editor.org/rfc/rfc9110.html#name-head
     */
    case HEAD = 'HEAD';

    /**
     * @see https://www.rfc-editor.org/rfc/rfc9110.html#name-post
     */
    case POST = 'POST';

    /**
     * @see https://www.rfc-editor.org/rfc/rfc9110.html#name-put
     */
    case PUT = 'PUT';

    /**
     * @see https://www.rfc-editor.org/rfc/rfc9110.html#name-delete
     */
    case DELETE = 'DELETE';

    /**
     * @see https://www.rfc-editor.org/rfc/rfc9110.html#name-connect
     */
    case CONNECT = 'CONNECT';

    /**
     * @see https://www.rfc-editor.org/rfc/rfc9110.html#name-options
     */
    case OPTIONS = 'OPTIONS';

    /**
     * @see https://www.rfc-editor.org/rfc/rfc9110.html#name-trace
     */
    case TRACE = 'TRACE';

    /**
     * @see https://tools.ietf.org/html/rfc5789
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/PATCH
     */
    case PATCH = 'PATCH';

    /** @var RequestMethod[] */
    public const array ANY = [
        self::GET,
        self::HEAD,
        self::POST,
        self::PUT,
        self::DELETE,
        self::CONNECT,
        self::OPTIONS,
        self::TRACE,
        self::PATCH,
    ];
}
