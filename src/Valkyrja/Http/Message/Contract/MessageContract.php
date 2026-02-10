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

namespace Valkyrja\Http\Message\Contract;

use InvalidArgumentException;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;

interface MessageContract
{
    /**
     * Get the protocol version.
     */
    public function getProtocolVersion(): ProtocolVersion;

    /**
     * Create a new message with the specified protocol version.
     */
    public function withProtocolVersion(ProtocolVersion $version): static;

    /**
     * Get all the headers.
     *
     * @return array<lowercase-string, HeaderContract>
     */
    public function getHeaders(): array;

    /**
     * Determine if a header exists.
     */
    public function hasHeader(string $name): bool;

    /**
     * Get a header by name.
     */
    public function getHeader(string $name): HeaderContract|null;

    /**
     * Get the header's values as a string.
     */
    public function getHeaderLine(string $name): string;

    /**
     * Create a new instance with the specified header, overriding any existing header with the same name.
     *
     * @throws InvalidArgumentException for invalid header names or values
     */
    public function withHeader(HeaderContract $header): static;

    /**
     * Create a new instance with the specified header appended.
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the header did not
     * exist previously, it will be added.
     *
     * @throws InvalidArgumentException for invalid header names or values
     */
    public function withAddedHeader(HeaderContract $header): static;

    /**
     * Create a new instance without the specified header.
     *
     * @param string $name Case-insensitive header field name to remove
     */
    public function withoutHeader(string $name): static;

    /**
     * Gets the body of the message.
     */
    public function getBody(): StreamContract;

    /**
     * Create a new instance with the specified body.
     *
     * @throws InvalidArgumentException When the body is not valid
     */
    public function withBody(StreamContract $body): static;
}
