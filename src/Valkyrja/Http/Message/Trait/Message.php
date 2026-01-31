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

namespace Valkyrja\Http\Message\Trait;

use InvalidArgumentException;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;

use function strtolower;

trait Message
{
    /**
     * The headers with normalized header names.
     *
     * @var array<lowercase-string, HeaderContract>
     */
    protected array $headers = [];

    /**
     * The protocol version.
     *
     * @var ProtocolVersion
     */
    protected ProtocolVersion $protocolVersion = ProtocolVersion::V1_1;

    /**
     * The stream.
     *
     * @var StreamContract
     */
    protected StreamContract $stream;

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): ProtocolVersion
    {
        return $this->protocolVersion;
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion(ProtocolVersion $version): static
    {
        $new = clone $this;

        $new->protocolVersion = $version;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return array<lowercase-string, HeaderContract>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function hasHeader(string $name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    /**
     * @inheritDoc
     */
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
    public function getHeaderLine(string $name): string
    {
        $header = $this->getHeader($name);

        if ($header === null) {
            return '';
        }

        return $header->getValuesAsString();
    }

    /**
     * @inheritDoc
     */
    public function withHeader(HeaderContract $header): static
    {
        $name = $header->getNormalizedName();

        $new = clone $this;

        $new->headers[$name] = $header;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader(HeaderContract $header): static
    {
        $name           = $header->getNormalizedName();
        $existingHeader = $this->getHeader($name);

        if ($existingHeader === null) {
            return $this->withHeader($header);
        }

        $new = clone $this;

        $new->headers[$name] = $existingHeader->withAddedValues(...$header->getValues());

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader(string $name): static
    {
        if (! $this->hasHeader($name)) {
            return clone $this;
        }

        $headerName = strtolower($name);
        $new        = clone $this;

        unset($new->headers[$headerName]);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamContract
    {
        return $this->stream;
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamContract $body): static
    {
        $new = clone $this;

        $new->setBody($body);

        $body->rewind();

        return $new;
    }

    public function __clone()
    {
        $this->stream = clone $this->stream;
    }

    /**
     * Set the body.
     *
     * @param StreamContract $body The body
     */
    protected function setBody(StreamContract $body): void
    {
        $this->stream = $body;
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
     * Inject a header in a headers array.
     *
     * @param array<lowercase-string, HeaderContract>|null $headers  [optional] The headers
     * @param bool                                         $override [optional] Whether to override any existing value
     *
     * @return array<lowercase-string, HeaderContract>
     */
    protected function injectHeader(
        HeaderContract $header,
        array|null $headers = null,
        bool $override = false
    ): array {
        // The headers
        $headers ??= [];
        // Get the normalized header name
        $headerName = $header->getNormalizedName();
        // The original value for the header (if it exists in the headers array)
        // Defaults to the value passed in
        $originalHeader = $headers[$headerName] ?? null;

        // Set the header in the headers list
        $headers[$headerName] = $override || $originalHeader === null
            ? $header
            : $originalHeader->withAddedValues(...$header->getValues());

        return $headers;
    }
}
