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

namespace Valkyrja\Http\Message\Uri\Constant;

/**
 * The characters each uri component allows unencoded, as regular expression character class atoms.
 *
 * @see https://tools.ietf.org/html/rfc3986#section-2
 */
final class Char
{
    /**
     * The unreserved characters, which every uri component allows.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2.3
     */
    public const string UNRESERVED = 'a-zA-Z0-9_\-\.~';

    /**
     * The sub-delimiters, which every uri component below allows.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2.2
     */
    public const string SUB_DELIMS = '!\$&\'\(\)\*\+,;=';

    /**
     * The user info also allows the colon that separates the username from the password.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2.1
     */
    public const string USER_INFO = self::UNRESERVED . self::SUB_DELIMS . ':';

    /**
     * A reg-name allows no character beyond the common set.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2.2
     */
    public const string HOST = self::UNRESERVED . self::SUB_DELIMS;

    /**
     * The path also allows a colon, an at sign, and the segment separator.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     */
    public const string PATH = self::UNRESERVED . self::SUB_DELIMS . ':@\/';

    /**
     * The query and the fragment also allow a colon, an at sign, a slash, and a question mark.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     */
    public const string QUERY = self::UNRESERVED . self::SUB_DELIMS . ':@\/\?';
}
