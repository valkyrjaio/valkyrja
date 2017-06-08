<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage;

use RuntimeException;
use Valkyrja\HttpMessage\Exceptions\InvalidStream;

/**
 * Describes a data stream.
 *
 * Typically, an instance will wrap a PHP stream; this interface provides
 * a wrapper around the most common operations, including serialization of
 * the entire stream to a string.
 *
 * @author Melech Mizrachi
 */
class NativeStream implements Stream
{
    /**
     * The stream.
     *
     * @var resource
     */
    protected $stream;

    /**
     * StreamImpl constructor.
     *
     * @param string $stream The stream
     * @param string $mode   [optional] The mode
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStream
     */
    public function __construct(string $stream, string $mode = null)
    {
        $this->setStream($stream, $mode);
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     *
     * @return string
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
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close(): void
    {
        // If there is no stream
        if (null === $this->stream) {
            // Don't do anything
            return;
        }

        // Detach the stream
        $resource = $this->detach();

        // Close the stream
        fclose($resource);
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach()
    {
        $resource     = $this->stream;
        $this->stream = null;

        return $resource;
    }

    /**
     * Attaches a new stream.
     *
     * @param string $stream The stream
     * @param string $mode   [optional] The mode
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStream
     *
     * @return void
     */
    public function attach(string $stream, string $mode = null): void
    {
        $this->setStream($stream, $mode);
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null Returns the size in bytes if known, or null if unknown.
     */
    public function getSize():? int
    {
        $fstat = null;

        // If the stream isn't set
        if (null === $this->stream) {
            // Return without attempting to get the fstat
            return $fstat;
        }

        // Get the stream's fstat
        $fstat = fstat($this->stream);

        return $fstat['size'];
    }

    /**
     * Returns the current position of the file read/write pointer.
     *
     * @throws \RuntimeException on error.
     *
     * @return int Position of the file pointer
     */
    public function tell(): int
    {
        // If there is no stream available
        if (null === $this->stream) {
            // Throw a runtime exception
            throw new RuntimeException('No resource available; cannot tell position');
        }

        // Get the tell for the stream
        $result = ftell($this->stream);

        // If the tell is not an int
        if (! is_int($result)) {
            // Throw a runtime exception
            throw new RuntimeException('Error occurred during tell operation');
        }

        return $result;
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof(): bool
    {
        // If there is no stream
        if (null === $this->stream) {
            // Don't do anything
            return true;
        }

        return feof($this->stream);
    }

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable(): bool
    {
        // If there is no stream
        if (null === $this->stream) {
            // Don't do anything
            return false;
        }

        return $this->getMetadata('seekable');
    }

    /**
     * Seek to a position in the stream.
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     *
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *                    based on the seek offset. Valid values are identical to the built-in
     *                    PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *                    offset bytes SEEK_CUR: Set position to current location plus offset
     *                    SEEK_END: Set position to end-of-stream plus offset.
     *
     * @throws \RuntimeException on failure.
     *
     * @return void
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        // If there is no stream
        if (null === $this->stream) {
            // Throw a new runtime exception
            throw new RuntimeException('No resource available; cannot seek position');
        }

        // If the stream isn't seekable
        if (! $this->isSeekable()) {
            // Throw a new runtime exception
            throw new RuntimeException('Stream is not seekable');
        }

        // Get the results of the seek attempt
        $result = fseek($this->stream, $offset, $whence);

        // If the result was not a 0, denoting an error occurred
        if (0 !== $result) {
            // Throw a new runtime exception
            throw new RuntimeException('Error seeking within stream');
        }
    }

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @see  seek()
     * @link http://www.php.net/manual/en/function.fseek.php
     *
     * @throws \RuntimeException on failure.
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * Returns whether or not the stream is writable.
     *
     * @return bool
     */
    public function isWritable(): bool
    {
        // If there is no stream
        if (null === $this->stream) {
            // The stream is definitely not writable
            return false;
        }

        // Get the stream's mode
        $mode = $this->getMetadata('mode');

        return (
            false !== strpos($mode, 'x')
            || false !== strpos($mode, 'w')
            || false !== strpos($mode, 'c')
            || false !== strpos($mode, 'a')
            || false !== strpos($mode, '+')
        );
    }

    /**
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     *
     * @throws \RuntimeException on failure.
     *
     * @return int Returns the number of bytes written to the stream.
     */
    public function write(string $string): int
    {
        // If there is no stream
        if (null === $this->stream) {
            // Throw a new runtime exception
            throw new RuntimeException('No resource available; cannot write');
        }

        // If the stream isn't seekable
        if (! $this->isSeekable()) {
            // Throw a new runtime exception
            throw new RuntimeException('Stream is not writable');
        }

        // Attempt to write to the stream
        $result = fwrite($this->stream, $string);

        // If the write was not successful
        if (false === $result) {
            // Throw a runtime exception
            throw new RuntimeException('Error writing to stream');
        }

        return $result;
    }

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        // If there is no stream
        if (null === $this->stream) {
            // It's not readable
            return false;
        }

        // Get the stream's mode
        $mode = $this->getMetadata('mode');

        return false !== strpos($mode, 'r') || false !== strpos($mode, '+');
    }

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *                    them. Fewer than $length bytes may be returned if underlying stream
     *                    call returns fewer bytes.
     *
     * @throws \RuntimeException if an error occurs.
     *
     * @return string Returns the data read from the stream, or an empty string
     *                if no bytes are available.
     */
    public function read(int $length): string
    {
        // If there is no stream
        if (null === $this->stream) {
            // Throw a runtime exception
            throw new RuntimeException('No resource available; cannot read');
        }

        // If the stream is not readable
        if (! $this->isReadable()) {
            // Throw a runtime exception
            throw new RuntimeException('Stream is not readable');
        }

        // Read the stream
        $result = fread($this->stream, $length);

        // If there was a failure in reading the stream
        if (false === $result) {
            // Throw a runtime exception
            throw new RuntimeException('Error reading stream');
        }

        return $result;
    }

    /**
     * Returns the remaining contents in a string.
     *
     * @throws \RuntimeException if unable to read or an error occurs while reading.
     *
     * @return string
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
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     *
     * @param string $key Specific metadata to retrieve.
     *
     * @return array|mixed|null Returns an associative array if no key is
     *                          provided. Returns a specific key value if a key is provided and the
     *                          value is found, or null if the key is not found.
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

    /**
     * Set the stream.
     *
     * @param string $stream The stream
     * @param string $mode   [optional] The mode
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStream
     *
     * @return void
     */
    protected function setStream(string $stream, string $mode = null): void
    {
        // Set the mode
        $mode = $mode ?? 'r';

        // Open a new resource stream
        $resource = fopen($stream, $mode);

        // If the resource isn't a resource or a stream resource type
        if (! is_resource($resource) || 'stream' !== get_resource_type($resource)) {
            // Throw a new invalid stream exception
            throw new InvalidStream(
                'Invalid stream provided; must be a string stream identifier or stream resource'
            );
        }

        // Set the stream
        $this->stream = $resource;
    }
}
