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

namespace Valkyrja\Http\Streams\Psr;

use Psr\Http\Message\StreamInterface;
use Valkyrja\Http\Stream as ValkyrjaStream;

/**
 * Class Stream.
 *
 * @author Melech Mizrachi
 */
class Stream implements StreamInterface
{
    public function __construct(
        protected ValkyrjaStream $stream,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->stream->__toString();
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        $this->stream->close();
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        return $this->stream->detach();
    }

    /**
     * @inheritDoc
     */
    public function getSize(): ?int
    {
        return $this->stream->getSize();
    }

    /**
     * @inheritDoc
     */
    public function tell(): int
    {
        return $this->stream->tell();
    }

    /**
     * @inheritDoc
     */
    public function eof(): bool
    {
        return $this->stream->eof();
    }

    /**
     * @inheritDoc
     */
    public function isSeekable(): bool
    {
        return $this->stream->isSeekable();
    }

    /**
     * @inheritDoc
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        $this->stream->seek($offset, $whence);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->stream->rewind();
    }

    /**
     * @inheritDoc
     */
    public function isWritable(): bool
    {
        return $this->stream->isWritable();
    }

    /**
     * @inheritDoc
     */
    public function write(string $string): int
    {
        return $this->stream->write($string);
    }

    /**
     * @inheritDoc
     */
    public function isReadable(): bool
    {
        return $this->stream->isReadable();
    }

    /**
     * @inheritDoc
     */
    public function read(int $length): string
    {
        return $this->stream->read($length);
    }

    /**
     * @inheritDoc
     */
    public function getContents(): string
    {
        return $this->stream->getContents();
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(?string $key = null)
    {
        return $this->stream->getMetadata($key);
    }
}
