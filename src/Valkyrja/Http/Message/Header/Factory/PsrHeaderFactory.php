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

use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Header\Value\Contract\ValueContract;

use function is_string;

abstract class PsrHeaderFactory
{
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
     * @return array<string, string[]>
     */
    public static function toPsr(HeaderCollectionContract $headers): array
    {
        $newHeaders = [];

        foreach ($headers->getAll() as $header) {
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
