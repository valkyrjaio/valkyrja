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

namespace Valkyrja\Http\Message\Stream;

use Override;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Enum\Mode;
use Valkyrja\Http\Message\Stream\Enum\ModeTranslation;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Factory\StreamFactory;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidLengthException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidStreamException;

use function fclose;
use function feof;
use function fread;
use function fseek;
use function fstat;
use function ftell;
use function fwrite;
use function stream_get_contents;
use function stream_get_meta_data;

use const SEEK_SET;

class Stream implements StreamContract
{
    /**
     * The stream.
     *
     * @var resource|null
     */
    protected $resource;

    /**
     * @throws InvalidStreamException
     */
    public function __construct(
        protected PhpWrapper|string $stream = PhpWrapper::temp,
        protected Mode $mode = Mode::WRITE_READ,
        protected ModeTranslation $modeTranslation = ModeTranslation::BINARY_SAFE
    ) {
        $this->resource = StreamFactory::getResourceStream($stream, $mode, $modeTranslation);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isSeekable(): bool
    {
        return (bool) $this->getMetadata('seekable');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        StreamFactory::verifySeekable($this);

        $stream = $this->resource;

        StreamFactory::validateStream($stream);

        // Get the results of the seek attempt
        $result = $this->seekStream($stream, $offset, $whence);

        StreamFactory::verifySeekResult($result);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isReadable(): bool
    {
        // Get the stream's mode
        /** @var string|null $mode */
        $mode = $this->getMetadata('mode');

        return StreamFactory::isModeReadable((string) $mode);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function read(int $length): string
    {
        if ($length < 0) {
            InvalidLengthException::throw("Invalid length of $length provided. Length must be greater than 0");
        }

        $stream = $this->resource;
        StreamFactory::validateStream($stream);
        /** @var int<1, max> $length */
        StreamFactory::verifyReadable($this);

        // Read the stream
        $result = $this->readFromStream($stream, $length);

        StreamFactory::verifyReadResult($result);

        /** @var string $result */

        return $result;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isWritable(): bool
    {
        // Get the stream's mode
        /** @var string|null $mode */
        $mode = $this->getMetadata('mode');

        return StreamFactory::isModeWriteable((string) $mode);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function write(string $string): int
    {
        $stream = $this->resource;
        StreamFactory::validateStream($stream);
        StreamFactory::verifyWritable($this);

        // Attempt to write to the stream
        $result = $this->writeToStream($stream, $string);

        StreamFactory::verifyWriteResult($result);

        /** @var int $result */

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return StreamFactory::toString($this);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function close(): void
    {
        // If there is no stream
        if ($this->isInvalidStream()) {
            // Don't do anything
            return;
        }

        // Detach the stream
        /** @var resource $resource */
        $resource = $this->detach();

        // Close the stream
        fclose($resource);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function detach()
    {
        $resource = $this->resource ?? null;

        $this->resource = null;

        return $resource;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getSize(): int|null
    {
        // If the stream isn't set
        if ($this->isInvalidStream()) {
            // Return without attempting to get the fstat
            return null;
        }

        /** @var resource $stream */
        $stream = $this->resource;

        // Get the stream's fstat
        $fstat = $this->getStreamStats($stream);

        if ($fstat === false) {
            return null;
        }

        return $fstat['size'];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function tell(): int
    {
        $stream = $this->resource;

        StreamFactory::validateStream($stream);

        // Get the tell for the stream
        $result = $this->tellStream($stream);

        StreamFactory::verifyTellResult($result);

        /** @var int $result */

        return $result;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function eof(): bool
    {
        // If there is no stream
        if ($this->isInvalidStream()) {
            // Don't do anything
            return true;
        }

        /** @var resource $stream */
        $stream = $this->resource;

        return feof($stream);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getContents(): string
    {
        StreamFactory::verifyReadable($this);

        /** @var resource $stream */
        $stream = $this->resource;

        // Get the stream contents
        $result = $this->getStreamContents($stream);

        StreamFactory::verifyReadResult($result);

        /** @var string $result */

        return $result;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getMetadata(string|null $key = null): mixed
    {
        // Ensure the stream is valid
        if ($this->isInvalidStream()) {
            return null;
        }

        /** @var resource $stream */
        $stream = $this->resource;

        // If no key was specified
        if ($key === null) {
            // Return all the meta data
            return $this->getStreamMetadata($stream);
        }

        // Get the meta data
        $metadata = $this->getStreamMetadata($stream);

        return $metadata[$key] ?? null;
    }

    /**
     * Serialize the stream.
     */
    public function __serialize(): array
    {
        return [
            'stream'          => $this->stream,
            'mode'            => $this->mode,
            'modeTranslation' => $this->modeTranslation,
            'content'         => $this->__toString(),
        ];
    }

    /**
     * Unserialize the stream.
     *
     * @param array{stream: PhpWrapper|string, mode: Mode, modeTranslation: ModeTranslation, content: string} $data The data
     */
    public function __unserialize(array $data): void
    {
        $this->stream          = $data['stream'];
        $this->mode            = $data['mode'];
        $this->modeTranslation = $data['modeTranslation'];

        $this->resource = StreamFactory::getResourceStream($this->stream, $this->mode, $this->modeTranslation);

        if ($this->isWritable()) {
            $this->write($data['content']);
            $this->rewind();
        }
    }

    /**
     * Seek the stream resource.
     *
     * @param resource $stream
     */
    protected function seekStream($stream, int $offset, int $whence = SEEK_SET): int
    {
        // Get the results of the seek attempt
        return fseek($stream, $offset, $whence);
    }

    /**
     * Tell the stream resource.
     *
     * @param resource $stream
     *
     * @return int|false
     */
    protected function tellStream($stream): int|false
    {
        // Get the tell for the stream
        return ftell($stream);
    }

    /**
     * Write to a stream.
     *
     * @param resource $stream The stream
     */
    protected function writeToStream($stream, string $data): int|false
    {
        return fwrite($stream, $data);
    }

    /**
     * Read from stream.
     *
     * @param resource    $stream The stream
     * @param int<1, max> $length The length
     */
    protected function readFromStream($stream, int $length): string|false
    {
        return fread($stream, $length);
    }

    /**
     * Get a stream's metadata.
     *
     * @param resource $stream The stream
     *
     * @return array{blocked: bool, crypto?: array{cipher_bits: int, cipher_name: string, cipher_version: string, protocol: string}, eof: bool, mediatype?: string, mode: string, seekable: bool, stream_type: string, timed_out: bool, unread_bytes: int, uri: string, wrapper_data: mixed, wrapper_type: string}
     */
    protected function getStreamMetadata($stream): array
    {
        // @phpstan-ignore-next-line
        return stream_get_meta_data($stream);
    }

    /**
     * Get a stream's contents.
     *
     * @param resource $stream The stream
     *
     * @return string|false
     */
    protected function getStreamContents($stream): string|false
    {
        return stream_get_contents($stream);
    }

    /**
     * Get a stream's stats.
     *
     * @param resource $stream The stream
     *
     * @return array{0: int, 10: int, 11: int, 12: int, 1: int, 2: int, 3: int, 4: int, 5: int, 6: int, 7: int, 8: int, 9: int, atime: int, blksize: int, blocks: int, ctime: int, dev: int, gid: int, ino: int, mode: int, mtime: int, nlink: int, rdev: int, size: int, uid: int}|false
     */
    protected function getStreamStats($stream): array|false
    {
        return fstat($stream);
    }

    /**
     * Is the stream valid.
     */
    protected function isInvalidStream(): bool
    {
        return $this->resource === null;
    }
}
