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

use Throwable;
use Valkyrja\Http\Message\Stream\Contract\Stream as Contract;
use Valkyrja\Http\Message\Stream\Enum\Mode;
use Valkyrja\Http\Message\Stream\Enum\ModeTranslation;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Exception\InvalidLengthException;
use Valkyrja\Http\Message\Stream\Exception\InvalidStreamException;

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

/**
 * Class Stream.
 *
 * @author Melech Mizrachi
 */
class Stream implements Contract
{
    use StreamHelpers;

    /**
     * StreamImpl constructor.
     *
     * @param PhpWrapper|string $stream          The stream
     * @param Mode              $mode            [optional] The mode
     * @param ModeTranslation   $modeTranslation [optional] The mode translation
     *
     * @throws InvalidStreamException
     */
    public function __construct(
        protected PhpWrapper|string $stream = PhpWrapper::temp,
        protected Mode $mode = Mode::WRITE_READ,
        protected ModeTranslation $modeTranslation = ModeTranslation::BINARY_SAFE
    ) {
        $this->setStream($stream, $mode, $modeTranslation);
    }

    /**
     * @inheritDoc
     */
    public function isSeekable(): bool
    {
        // If there is no stream
        if ($this->isInvalidStream()) {
            // Don't do anything
            return false;
        }

        return (bool) $this->getMetadata('seekable');
    }

    /**
     * @inheritDoc
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        $this->verifyStream();
        $this->verifySeekable();

        /** @var resource $stream */
        $stream = $this->resource;

        // Get the results of the seek attempt
        $result = $this->seekStream($stream, $offset, $whence);

        $this->verifySeekResult($result);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * @inheritDoc
     */
    public function isReadable(): bool
    {
        // If there is no stream
        if ($this->isInvalidStream()) {
            // It's not readable
            return false;
        }

        // Get the stream's mode
        /** @var string|null $mode */
        $mode = $this->getMetadata('mode');

        return $this->isModeReadable((string) $mode);
    }

    /**
     * @inheritDoc
     */
    public function read(int $length): string
    {
        if ($length < 0) {
            throw new InvalidLengthException("Invalid length of $length provided. Length must be greater than 0");
        }

        /** @var int<1, max> $length */
        $this->verifyStream();
        $this->verifyReadable();

        /** @var resource $stream */
        $stream = $this->resource;

        // Read the stream
        $result = $this->readFromStream($stream, $length);

        $this->verifyReadResult($result);

        /** @var string $result */

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function isWritable(): bool
    {
        // If there is no stream
        if ($this->isInvalidStream()) {
            // The stream is definitely not writable
            return false;
        }

        // Get the stream's mode
        /** @var string|null $mode */
        $mode = $this->getMetadata('mode');

        return $this->isModeWriteable((string) $mode);
    }

    /**
     * @inheritDoc
     */
    public function write(string $string): int
    {
        $this->verifyStream();
        $this->verifyWritable();

        /** @var resource $stream */
        $stream = $this->resource;

        // Attempt to write to the stream
        $result = $this->writeToStream($stream, $string);

        $this->verifyWriteResult($result);

        /** @var int $result */

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        // If the stream is not readable
        if (! $this->isReadable()) {
            // Return an empty string
            return '';
        }

        try {
            // Rewind the stream to the start
            $this->rewind();

            // Get the stream's contents
            return $this->getContents();
        } // On a runtime exception
        catch (Throwable) {
            // Return a string
            return '';
        }
    }

    /**
     * @inheritDoc
     */
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
        $this->closeStream($resource);
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        $resource = $this->resource ?? null;

        $this->resource = null;

        return $resource;
    }

    /**
     * @inheritDoc
     */
    public function getSize(): ?int
    {
        // If the stream isn't set
        if ($this->isInvalidStream()) {
            // Return without attempting to get the fstat
            return null;
        }

        /** @var resource $stream */
        $stream = $this->resource;

        // Get the stream's fstat
        $fstat = fstat($stream);

        if ($fstat === false) {
            return null;
        }

        return $fstat['size'];
    }

    /**
     * @inheritDoc
     */
    public function tell(): int
    {
        $this->verifyStream();

        /** @var resource $stream */
        $stream = $this->resource;

        // Get the tell for the stream
        $result = $this->tellStream($stream);

        $this->verifyTellResult($result);

        /** @var int $result */

        return $result;
    }

    /**
     * @inheritDoc
     */
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
    public function getContents(): string
    {
        $this->verifyReadable();

        /** @var resource $stream */
        $stream = $this->resource;

        // Get the stream contents
        $result = $this->getStreamContents($stream);

        $this->verifyReadResult($result);

        /** @var string $result */

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(?string $key = null): mixed
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

    // public function __clone()
    // {
    //     $this->rewind();
    //
    //     $contents = $this->getContents();
    //
    //     $this->setStream($this->stream, $this->mode, $this->modeTranslation);
    //
    //     if ($this->isWritable()) {
    //         $this->write($contents);
    //         $this->rewind();
    //     }
    // }

    /**
     * Seek the stream resource.
     *
     * @param resource $stream
     * @param int      $offset
     * @param int      $whence
     *
     * @return int
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
     * Close a stream.
     *
     * @param resource $stream The stream
     *
     * @return bool
     */
    protected function closeStream($stream): bool
    {
        return fclose($stream);
    }
}
