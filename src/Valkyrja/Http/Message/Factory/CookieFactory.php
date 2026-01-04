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

namespace Valkyrja\Http\Message\Factory;

use function array_keys;
use function array_map;
use function array_values;
use function implode;
use function preg_match_all;
use function urldecode;

use const PREG_SET_ORDER;

/**
 * @see https://www.php.net/manual/en/reserved.variables.cookies.php
 */
abstract class CookieFactory
{
    /** @var string */
    protected const string SEPARATOR = '; ';

    /**
     * Parse a cookie header according to RFC 6265.
     * PHP will replace special characters in cookie names, which results in other cookies not being available due to
     * overwriting. Thus, the server request should take the cookies from the request header instead.
     *
     *
     * @return array<string, string>
     */
    public static function parseCookieHeader(string $cookieHeader): array
    {
        preg_match_all(
            '(
            (?:^\\n?[ \t]*|;[ ])
            (?P<name>[!#$%&\'*+\-.0-9A-Z^_`a-z|~]+)
            =
            (?P<DQUOTE>"?)
                (?P<value>[\x21\x23-\x2b\x2d-\x3a\x3c-\x5b\x5d-\x7e]*)
            (?P=DQUOTE)
            (?=\\n?[ \t]*$|;[ ])
        )x',
            $cookieHeader,
            $matches,
            PREG_SET_ORDER
        );

        $cookies = [];

        /** @var array<int, array{name: string, value: string}> $matches */
        foreach ($matches as $match) {
            $cookies[$match['name']] = urldecode($match['value']);
        }

        return $cookies;
    }

    /**
     * @param array<string, string> $cookies The cookies
     */
    public static function convertCookieArrayToHeaderString(array $cookies): string
    {
        return implode(
            self::SEPARATOR,
            array_map([self::class, 'combineKeyAndValue'], array_keys($cookies), array_values($cookies))
        );
    }

    public static function combineKeyAndValue(string $key, string $value): string
    {
        return "$key=$value";
    }
}
