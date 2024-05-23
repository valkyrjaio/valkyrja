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

use RuntimeException;
use Valkyrja\Http\Message\Exception\InvalidStream;
use Valkyrja\Http\Message\Exception\StreamException;
use Valkyrja\Http\Message\Stream\Contract\Stream as StreamContract;

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
 * Describes a data stream.
 * Typically, an instance will wrap a PHP stream; this interface provides
 * a wrapper around the most common operations, including serialization of
 * the entire stream to a string.
 *
 * @author Melech Mizrachi
 */
class Stream implements StreamContract
{
    use StreamHelpers;

    /**
     * StreamImpl constructor.
     *
     * @param string      $stream The stream
     * @param string|null $mode   [optional] The mode
     *
     * @throws InvalidStream
     */
    public function __construct(string $stream, string|null $mode = null)
    {
        $this->setStream($stream, $mode);
    }

    /**
     * @inheritDoc
     */
    public function isSeekable(): bool
    {
        // If there is no stream
        if ($this->isInValidStream()) {
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
        $stream = $this->stream;

        // Get the results of the seek attempt
        $result = fseek($stream, $offset, $whence);

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
        if ($this->isInValidStream()) {
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
        $this->verifyStream();
        $this->verifyReadable();

        /** @var resource $stream */
        $stream = $this->stream;

        // Read the stream
        $result = fread($stream, $length);

        $this->verifyReadResult($result);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function isWritable(): bool
    {
        // If there is no stream
        if ($this->isInValidStream()) {
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
        $stream = $this->stream;

        // Attempt to write to the stream
        $result = fwrite($stream, $string);

        $this->verifyWriteResult($result);

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
        catch (RuntimeException) {
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
        if ($this->isInValidStream()) {
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
    public function detach()
    {
        $resource = $this->stream ?? null;

        $this->stream = null;

        return $resource;
    }

    /**
     * @inheritDoc
     */
    public function attach(string $stream, string|null $mode = null): void
    {
        $this->setStream($stream, $mode);
    }

    /**
     * @inheritDoc
     */
    public function getSize(): int|null
    {
        // If the stream isn't set
        if ($this->isInValidStream()) {
            // Return without attempting to get the fstat
            return null;
        }

        /** @var resource $stream */
        $stream = $this->stream;

        // Get the stream's fstat
        $fstat = fstat($stream);

        return $fstat['size'];
    }

    /**
     * @inheritDoc
     */
    public function tell(): int
    {
        $this->verifyStream();

        /** @var resource $stream */
        $stream = $this->stream;

        // Get the tell for the stream
        $result = ftell($stream);

        // If the tell is not an int
        if ($result === false) {
            // Throw a runtime exception
            throw new StreamException('Error occurred during tell operation');
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function eof(): bool
    {
        // If there is no stream
        if ($this->isInValidStream()) {
            // Don't do anything
            return true;
        }

        /** @var resource $stream */
        $stream = $this->stream;

        return feof($stream);
    }

    /**
     * @inheritDoc
     */
    public function getContents(): string
    {
        // If the stream isn't readable
        if (! $this->isReadable()) {
            // Throw a runtime exception
            throw new StreamException('Stream is not readable');
        }

        /** @var resource $stream */
        $stream = $this->stream;

        // Get the stream contents
        $result = stream_get_contents($stream);

        // If there was a failure in getting the stream contents
        if ($result === false) {
            // Throw a runtime exception
            throw new StreamException('Error reading from stream');
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(string|null $key = null): mixed
    {
        // Ensure the stream is valid
        if ($this->isInValidStream()) {
            return null;
        }

        /** @var resource $stream */
        $stream = $this->stream;

        // If no key was specified
        if ($key === null) {
            // Return all the meta data
            return stream_get_meta_data($stream);
        }

        // Get the meta data
        $metadata = stream_get_meta_data($stream);

        return $metadata[$key] ?? null;
    }
}
