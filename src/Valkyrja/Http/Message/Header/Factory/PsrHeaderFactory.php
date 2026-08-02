<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Header\Factory;

use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Header\Value\Contract\ValueContract;

use function array_map;
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
