<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Contract;

use InvalidArgumentException;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
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
     */
    public function getHeaders(): HeaderCollectionContract;

    /**
     * Create a new instance with the provided headers.
     */
    public function withHeaders(HeaderCollectionContract $headers): static;

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
