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

use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Header\Value\Contract\ValueContract;

use function array_key_exists;
use function is_string;
use function str_replace;
use function strtolower;
use function substr;

abstract class HeaderFactory
{
    /**
     * Marshal headers from $_SERVER.
     *
     * @param array<string, string> $server
     *
     * @return array<lowercase-string, HeaderContract>
     */
    public static function marshalHeaders(array $server): array
    {
        $headers = [];

        foreach ($server as $key => $value) {
            // Apache prefixes environment variables with REDIRECT_
            // if they are added by rewrite rules
            if (str_starts_with($key, 'REDIRECT_')) {
                $key = substr($key, 9);

                // We will not overwrite existing variables with the
                // prefixed versions, though
                if (array_key_exists($key, $server)) {
                    continue;
                }
            }

            if ($value && str_starts_with($key, 'HTTP_')) {
                /** @var lowercase-string $name */
                $name           = str_replace('_', '-', strtolower(substr($key, 5)));
                $headers[$name] = new Header($name, $value);

                continue;
            }

            if ($value && str_starts_with($key, 'CONTENT_')) {
                $name           = 'content-' . strtolower(substr($key, 8));
                $headers[$name] = new Header($name, $value);
            }
        }

        return $headers;
    }

    /**
     * Convert psr headers to valkyrja headers.
     *
     * @param array<string, string[]> $headers The psr headers
     *
     * @return HeaderContract[]
     */
    public static function fromPsr(array $headers): array
    {
        $newHeaders = [];

        foreach ($headers as $name => $values) {
            $newHeaders[] = new Header($name, ...$values);
        }

        return $newHeaders;
    }

    /**
     * Conver valkyrja headers to psr headers.
     *
     * @param HeaderContract[] $headers The valkyrja headers
     *
     * @return array<string, string[]>
     */
    public static function toPsr(array $headers): array
    {
        $newHeaders = [];

        foreach ($headers as $header) {
            $newHeaders[$header->getName()] = static::toPsrValues($header);
        }

        return $newHeaders;
    }

    /**
     * Convert a header to psr values.
     *
     * @param HeaderContract $header The header
     *
     * @return string[]
     */
    public static function toPsrValues(HeaderContract $header): array
    {
        $headersValues = $header->getValues();

        return array_map(
            static fn (ValueContract|string $value): string => is_string($value) ? $value : $value->__toString(),
            $headersValues
        );
    }
}
