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

use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;

trait Message
{
    /**
     * The headers with normalized header names.
     */
    protected HeaderCollectionContract $headers;

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
     */
    public function getHeaders(): HeaderCollectionContract
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function withHeaders(HeaderCollectionContract $headers): static
    {
        $new = clone $this;

        $new->headers = $headers;

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

    /**
     * Clone this message.
     */
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
     * Inject a header in a headers array.
     *
     * @param bool $override [optional] Whether to override any existing value
     */
    protected function injectHeader(
        HeaderContract $header,
        HeaderCollectionContract $headers,
        bool $override = false
    ): HeaderCollectionContract {
        // Get the normalized header name
        $headerName = $header->getNormalizedName();
        // The original value for the header (if it exists in the headers array)
        // Defaults to the value passed in
        $originalHeader = $headers->get($headerName);

        // Set the header in the headers list
        $newHeader = $override || $originalHeader === null
            ? $header
            : $originalHeader->withAddedValues(...$header->getValues());

        return $headers->withHeader($newHeader);
    }
}
