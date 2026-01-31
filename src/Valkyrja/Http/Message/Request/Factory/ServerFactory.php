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

namespace Valkyrja\Http\Message\Request\Factory;

use function function_exists;

abstract class ServerFactory
{
    /**
     * Marshal the $_SERVER array.
     * Pre-processes and returns the $_SERVER superglobal.
     *
     * @param array<string, string> $server
     *
     * @return array<string, string>
     */
    public static function normalizeServer(array $server): array
    {
        $apacheRequestHeaders = self::apacheRequestHeaders();

        if (isset($server['HTTP_AUTHORIZATION'])) {
            return $server;
        }

        if (isset($apacheRequestHeaders['Authorization'])) {
            $server['HTTP_AUTHORIZATION'] = $apacheRequestHeaders['Authorization'];

            return $server;
        }

        if (isset($apacheRequestHeaders['authorization'])) {
            $server['HTTP_AUTHORIZATION'] = $apacheRequestHeaders['authorization'];

            return $server;
        }

        return $server;
    }

    /**
     * @return array{Authorization?: string, authorization?: string}
     */
    private static function apacheRequestHeaders(): array
    {
        $headers = [];

        if (function_exists('apache_request_headers')) {
            /** @var array{Authorization?: string, authorization?: string} $headers */
            // This seems to be the only way to get the Authorization header on Apache
            $headers = apache_request_headers();
        }

        return $headers;
    }
}
