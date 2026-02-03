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

namespace Valkyrja\Http\Message\Header\Factory;

use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Header\Throwable\Exception\InvalidNameException;
use Valkyrja\Http\Message\Header\Throwable\Exception\InvalidValueException;
use Valkyrja\Http\Message\Header\Value\Contract\ValueContract;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;

use function array_key_exists;
use function in_array;
use function is_string;
use function ord;
use function sprintf;
use function str_replace;
use function strlen;
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

    /**
     * Filter a header value.
     *
     * @see http://en.wikipedia.org/wiki/HTTP_response_splitting
     */
    public static function filterValue(string $value): string
    {
        $length = strlen($value);
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $ascii = ord($value[$i]);

            // Detect continuation sequences
            if ($ascii === 13) {
                $lf = ord($value[$i + 1]);
                $ws = ord($value[$i + 2]);

                if ($lf === 10 && in_array($ws, [9, 32], true)) {
                    $string .= $value[$i] . $value[$i + 1];
                    $i++;
                }

                continue;
            }

            // Non-visible, non-whitespace characters
            // 9 === horizontal tab
            // 32-126, 128-254 === visible
            // 127 === DEL
            // 255 === null byte
            if (
                ($ascii < 32 && $ascii !== 9)
                || $ascii === 127
                || $ascii > 254
            ) {
                continue;
            }

            $string .= $value[$i];
        }

        return $string;
    }

    /**
     * Assert a header value is valid.
     *
     * @throws InvalidArgumentException for invalid values
     */
    public static function assertValidValue(string $value): void
    {
        if (! self::isValidValue($value)) {
            throw new InvalidValueException(sprintf('"%s" is not valid header value', $value));
        }
    }

    /**
     * Validate a header value.
     * Per RFC 7230, only VISIBLE ASCII characters, spaces, and horizontal
     * tabs are allowed in values; header continuations MUST consist of
     * a single CRLF sequence followed by a space or horizontal tab.
     *
     * @see http://en.wikipedia.org/wiki/HTTP_response_splitting
     */
    public static function isValidValue(string $value): bool
    {
        // Look for:
        // \n not preceded by \r, OR
        // \r not followed by \n, OR
        // \r\n not followed by space or horizontal tab; these are all CRLF attacks
        if (preg_match("#(?:(?:(?<!\r)\n)|(?:\r(?!\n))|(?:\r\n(?![ \t])))#", $value)) {
            return false;
        }

        // Non-visible, non-whitespace characters
        // 9 === horizontal tab
        // 10 === line feed
        // 13 === carriage return
        // 32-126, 128-254 === visible
        // 127 === DEL (disallowed)
        // 255 === null byte (disallowed)
        if (preg_match('/[^\x09\x0a\x0d\x20-\x7E\x80-\xFE]/', $value)) {
            return false;
        }

        return true;
    }

    /**
     * Assert whether or not a header name is valid.
     *
     * @see http://tools.ietf.org/html/rfc7230#section-3.2
     *
     * @throws InvalidArgumentException
     */
    public static function assertValidName(string $name): void
    {
        if (! self::isValidName($name)) {
            throw new InvalidNameException(sprintf('"%s" is not valid header name', $name));
        }
    }

    public static function isValidName(string $name): bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9\'`#$%&*+.^_|~!-]+$/D', $name);
    }
}
