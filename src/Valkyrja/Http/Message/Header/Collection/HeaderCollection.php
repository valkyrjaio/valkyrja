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

namespace Valkyrja\Http\Message\Header\Collection;

use Override;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Header\Throwable\Exception\InvalidArgumentException;

use function in_array;

use const ARRAY_FILTER_USE_KEY;

class HeaderCollection implements HeaderCollectionContract
{
    /** @var array<non-empty-lowercase-string, HeaderContract> */
    protected array $headers = [];

    /**
     * The position during iteration.
     *
     * @var int
     */
    protected int $position = 0;

    /**
     * @param HeaderContract ...$headers The headers
     */
    public function __construct(HeaderContract ...$headers)
    {
        $this->setHeaders(...$headers);
    }

    /**
     * Create a new instance from an array.
     *
     * @param array<array-key, mixed> $data The data to create from
     */
    public function fromArray(array $data): static
    {
        $headers = [];

        /**
         * @var array-key $name
         * @var mixed     $param
         */
        foreach ($data as $name => $param) {
            $this->validateHeader($param);

            $headers[$name] = $param;
        }

        return new static(...$headers);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasHeader(string $name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHeader(string $name): HeaderContract|null
    {
        if (! $this->hasHeader($name)) {
            return null;
        }

        $name = strtolower($name);

        return $this->headers[$name];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function onlyHeaders(string ...$names): array
    {
        return array_filter(
            $this->headers,
            static fn (string $name): bool => in_array($name, $names, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function exceptHeaders(string ...$names): array
    {
        return array_filter(
            $this->headers,
            static fn (string $name): bool => ! in_array($name, $names, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withHeader(HeaderContract $header): static
    {
        $new = clone $this;

        $new->overrideHeader($header);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withHeaders(HeaderContract ...$headers): static
    {
        $new = clone $this;

        $new->setHeaders(...$headers);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedHeaders(HeaderContract ...$headers): static
    {
        $new = clone $this;

        foreach ($headers as $header) {
            $new->addHeader($header);
        }

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutHeaders(string ...$names): static
    {
        $new = clone $this;

        foreach ($names as $name) {
            $new->removeHeader($name);
        }

        return $new;
    }

    /**
     * Remove a header.
     *
     * @param non-empty-string $name The header name to remove
     */
    protected function removeHeader(string $name): void
    {
        if (! $this->hasHeader($name)) {
            return;
        }

        $headerName = strtolower($name);

        unset($this->headers[$headerName]);
    }

    /**
     * Override a header.
     */
    protected function overrideHeader(HeaderContract $header): void
    {
        $name = $header->getNormalizedName();

        $this->headers[$name] = $header;
    }

    /**
     * Add a header.
     */
    protected function addHeader(HeaderContract $header): void
    {
        $name           = $header->getNormalizedName();
        $existingHeader = $this->getHeader($name);

        if ($existingHeader === null) {
            $this->headers[$name] = $header;

            return;
        }

        $this->headers[$name] = $existingHeader->withAddedValues(...$header->getValues());
    }

    /**
     * Set headers.
     *
     * @param HeaderContract ...$originalHeaders The original headers
     *
     * @throws InvalidArgumentException
     */
    protected function setHeaders(HeaderContract ...$originalHeaders): void
    {
        $headers = [];

        foreach ($originalHeaders as $header) {
            $headerName = $header->getNormalizedName();

            $headers[$headerName] = $header;
        }

        $this->headers = $headers;
    }

    /**
     * Validate a header.
     *
     * @psalm-assert HeaderContract $param
     *
     * @phpstan-assert HeaderContract $param
     */
    protected function validateHeader(mixed $param): void
    {
        if (! $param instanceof HeaderContract) {
            throw new InvalidArgumentException('Param must be header');
        }
    }
}
