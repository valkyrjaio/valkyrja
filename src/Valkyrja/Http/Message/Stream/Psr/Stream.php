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

namespace Valkyrja\Http\Message\Stream\Psr;

use Override;
use Psr\Http\Message\StreamInterface;
use Valkyrja\Http\Message\Stream\Contract\Stream as ValkyrjaStreamContract;
use Valkyrja\Http\Message\Stream\Stream as ValkyrjaStream;

use const SEEK_SET;

/**
 * Class Stream.
 *
 * @author Melech Mizrachi
 */
class Stream implements StreamInterface
{
    public function __construct(
        protected ValkyrjaStreamContract $stream = new ValkyrjaStream(),
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
    #[Override]
    public function close(): void
    {
        $this->stream->close();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function detach()
    {
        return $this->stream->detach();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getSize(): int|null
    {
        return $this->stream->getSize();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function tell(): int
    {
        return $this->stream->tell();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function eof(): bool
    {
        return $this->stream->eof();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isSeekable(): bool
    {
        return $this->stream->isSeekable();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        $this->stream->seek($offset, $whence);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function rewind(): void
    {
        $this->stream->rewind();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isWritable(): bool
    {
        return $this->stream->isWritable();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function write(string $string): int
    {
        return $this->stream->write($string);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isReadable(): bool
    {
        return $this->stream->isReadable();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function read(int $length): string
    {
        return $this->stream->read($length);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getContents(): string
    {
        return $this->stream->getContents();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getMetadata(string|null $key = null): mixed
    {
        return $this->stream->getMetadata($key);
    }
}
