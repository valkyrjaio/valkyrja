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

namespace Valkyrja\Http\Streams;

use RuntimeException;
use Valkyrja\Http\Exceptions\InvalidStream;
use Valkyrja\Http\Stream as StreamContract;

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
    public function __construct(string $stream, string $mode = null)
    {
        $this->setStream($stream, $mode);
    }

    /**
     * @inheritDoc
     */
    public function isSeekable(): bool
    {
        // If there is no stream
        if ($this->isValidStream()) {
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

        // Get the results of the seek attempt
        $result = fseek($this->stream, $offset, $whence);

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
        if ($this->isValidStream()) {
            // It's not readable
            return false;
        }

        // Get the stream's mode
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

        // Read the stream
        $result = fread($this->stream, $length);

        $this->verifyReadResult($result);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function isWritable(): bool
    {
        // If there is no stream
        if ($this->isValidStream()) {
            // The stream is definitely not writable
            return false;
        }

        // Get the stream's mode
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

        // Attempt to write to the stream
        $result = fwrite($this->stream, $string);

        $this->verifyWriteResult($result);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
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
        catch (RuntimeException $e) {
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
        if ($this->isValidStream()) {
            // Don't do anything
            return;
        }

        // Detach the stream
        $resource = $this->detach();

        // Close the stream
        fclose($resource);
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        $resource     = $this->stream;
        $this->stream = null;

        return $resource;
    }

    /**
     * @inheritDoc
     */
    public function attach(string $stream, string $mode = null): void
    {
        $this->setStream($stream, $mode);
    }

    /**
     * @inheritDoc
     */
    public function getSize(): ?int
    {
        // If the stream isn't set
        if ($this->isValidStream()) {
            // Return without attempting to get the fstat
            return null;
        }

        // Get the stream's fstat
        $fstat = fstat($this->stream);

        return $fstat['size'];
    }

    /**
     * @inheritDoc
     */
    public function tell(): int
    {
        $this->verifyStream();

        // Get the tell for the stream
        $result = ftell($this->stream);

        // If the tell is not an int
        if ($result === false) {
            // Throw a runtime exception
            throw new RuntimeException('Error occurred during tell operation');
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function eof(): bool
    {
        // If there is no stream
        if ($this->isValidStream()) {
            // Don't do anything
            return true;
        }

        return feof($this->stream);
    }

    /**
     * @inheritDoc
     */
    public function getContents(): string
    {
        // If the stream isn't readable
        if (! $this->isReadable()) {
            // Throw a runtime exception
            throw new RuntimeException('Stream is not readable');
        }

        // Get the stream contents
        $result = stream_get_contents($this->stream);

        // If there was a failure in getting the stream contents
        if (false === $result) {
            // Throw a runtime exception
            throw new RuntimeException('Error reading from stream');
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(string $key = null)
    {
        // If no key was specified
        if (null === $key) {
            // Return all the meta data
            return stream_get_meta_data($this->stream);
        }

        // Get the meta data
        $metadata = stream_get_meta_data($this->stream);

        return $metadata[$key] ?? null;
    }
}
