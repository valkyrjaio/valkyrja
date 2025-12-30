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
use Valkyrja\Http\Message\Header\Security\HeaderSecurity;
use Valkyrja\Http\Message\Stream\Contract\Stream;

use function array_merge;
use function implode;
use function is_array;
use function strtolower;

/**
 * Trait MessageTrait.
 *
 * @author Melech Mizrachi
 */
trait Message
{
    /**
     * The headers with normalized header names.
     *
     * @var array<string, string[]>
     */
    protected array $headers = [];

    /**
     * Original header names.
     *
     * @var array<string, string>
     */
    protected array $headerNames = [];

    /**
     * The protocol version.
     *
     * @var ProtocolVersion
     */
    protected ProtocolVersion $protocolVersion = ProtocolVersion::V1_1;

    /**
     * The stream.
     *
     * @var Stream
     */
    protected Stream $stream;

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): ProtocolVersion
    {
        return $this->protocolVersion;
    }

    /**
     * @inheritDoc
     *
     * @return static
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
     * @return array<string, string[]>
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
        return isset($this->headerNames[strtolower($name)]);
    }

    /**
     * @inheritDoc
     *
     * @return string[]
     */
    public function getHeader(string $name): array
    {
        if (! $this->hasHeader($name)) {
            return [];
        }

        $name = $this->headerNames[strtolower($name)];

        return $this->headers[$name];
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine(string $name): string
    {
        $value = $this->getHeader($name);

        if (empty($value)) {
            return '';
        }

        return implode(',', $value);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function withHeader(string $name, string ...$values): static
    {
        HeaderSecurity::assertValidName($name);

        $normalized = strtolower($name);

        $new = clone $this;

        if ($new->hasHeader($name)) {
            unset($new->headers[$new->headerNames[$normalized]]);
        }

        $new->headerNames[$normalized] = $name;

        $new->headers[$name] = $this->assertHeaderValues(...$values);

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @param string ...$values Header value(s).
     *
     * @return static
     */
    public function withAddedHeader(string $name, string ...$values): static
    {
        HeaderSecurity::assertValidName($name);

        if (! $this->hasHeader($name)) {
            return $this->withHeader($name, ...$values);
        }

        $name = $this->headerNames[strtolower($name)];

        $new = clone $this;

        $new->headers[$name] = array_merge($this->headers[$name], $this->assertHeaderValues(...$values));

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function withoutHeader(string $name): static
    {
        if (! $this->hasHeader($name)) {
            return clone $this;
        }

        $normalized = strtolower($name);
        $original   = $this->headerNames[$normalized];
        $new        = clone $this;

        unset($new->headers[$original], $new->headerNames[$normalized]);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): Stream
    {
        return $this->stream;
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function withBody(Stream $body): static
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
     * @param Stream $body The body
     *
     * @return void
     */
    protected function setBody(Stream $body): void
    {
        $this->stream = $body;
    }

    /**
     * Set headers.
     *
     * @param array<string, string|string[]> $originalHeaders The original headers
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    protected function setHeaders(array $originalHeaders): void
    {
        $headerNames = $headers = [];

        foreach ($originalHeaders as $header => $value) {
            $value = is_array($value) ? $value : [$value];

            HeaderSecurity::assertValidName($header);

            $headerNames[strtolower($header)] = $header;

            $headers[$header] = $this->assertHeaderValues(...$value);
        }

        $this->headerNames = $headerNames;
        $this->headers     = $headers;
    }

    /**
     * Filter header values.
     *
     * @param string ...$values Header values
     *
     * @throws InvalidArgumentException
     *
     * @return string[]
     */
    protected function assertHeaderValues(string ...$values): array
    {
        foreach ($values as $value) {
            HeaderSecurity::assertValid($value);
        }

        return $values;
    }

    /**
     * Inject a header in a headers array.
     *
     * @param string                       $header   The header to set
     * @param string                       $value    The value to set
     * @param array<string, string[]>|null $headers  [optional] The headers
     * @param bool                         $override [optional] Whether to override any existing value
     *
     * @return array<string, string[]>
     */
    protected function injectHeader(
        string $header,
        string $value,
        array|null $headers = null,
        bool $override = false
    ): array {
        // The headers
        $headers ??= [];
        // Normalize the content type header
        $normalized = strtolower($header);
        // The original value for the header (if it exists in the headers array)
        // Defaults to the value passed in
        $originalValue = [$value];

        // Iterate through all the headers
        foreach ($headers as $headerIndex => $headerValue) {
            // Normalize the header name and check if it matches the normalized
            // passed in header
            if (strtolower($headerIndex) === $normalized) {
                // Set the original value as this header value
                $originalValue = $headerValue;

                // Unset the header as we want to use the header string that was
                // passed in as the header
                unset($headers[$headerIndex]);
            }
        }

        // Set the header in the headers list
        $headers[$header] = $override
            ? [$value]
            : $originalValue;

        return $headers;
    }
}
