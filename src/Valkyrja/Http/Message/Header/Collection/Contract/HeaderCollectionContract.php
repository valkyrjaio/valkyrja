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

namespace Valkyrja\Http\Message\Header\Collection\Contract;

use Valkyrja\Http\Message\Header\Contract\HeaderContract;

interface HeaderCollectionContract
{
    /**
     * Determine if a header exists.
     *
     * @param non-empty-string $name The header name
     */
    public function hasHeader(string $name): bool;

    /**
     * Get a header.
     *
     * @param non-empty-string $name The header name
     */
    public function getHeader(string $name): HeaderContract|null;

    /**
     * Get a header's values as a string by name.
     *
     * @param non-empty-string $name The header name
     */
    public function getHeaderLine(string $name): string;

    /**
     * Get all the headers.
     *
     * @return array<non-empty-lowercase-string, HeaderContract>
     */
    public function getHeaders(): array;

    /**
     * Get only the specified headers.
     *
     * @param non-empty-string ...$names The header names
     *
     * @return array<non-empty-lowercase-string, HeaderContract>
     */
    public function onlyHeaders(string ...$names): array;

    /**
     * Get all the headers except the specified ones.
     *
     * @param non-empty-string ...$names The header names
     *
     * @return array<non-empty-lowercase-string, HeaderContract>
     */
    public function exceptHeaders(string ...$names): array;

    /**
     * Get a new instance with the specified header overriding any existing header of the same name.
     */
    public function withHeader(HeaderContract $header): static;

    /**
     * Create a new instance without the specified header.
     *
     * @param non-empty-string $name The header name
     */
    public function withoutHeader(string $name): static;

    /**
     * Get a new instance with the specified headers.
     *
     * @param HeaderContract ...$headers The headers
     */
    public function withHeaders(HeaderContract ...$headers): static;

    /**
     * Get a new instance with the added headers.
     */
    public function withAddedHeaders(HeaderContract ...$headers): static;

    /**
     * Get a new instance without the specified header.
     *
     * @param non-empty-string ...$names The header name
     */
    public function withoutHeaders(string ...$names): static;
}
