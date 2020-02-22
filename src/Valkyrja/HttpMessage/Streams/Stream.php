<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage\Streams;

use RuntimeException;
use Valkyrja\HttpMessage\Exceptions\InvalidStream;
use Valkyrja\HttpMessage\Stream as StreamContract;

use function is_int;
use function is_resource;

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
    use Read;
    use Seek;
    use Write;

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
     * @throws InvalidStream
     */
    public function __construct(string $stream, string $mode = null)
    {
        $this->setStream($stream, $mode);
    }

    /**
     * Set the stream.
     *
     * @param string $stream The stream
     * @param string $mode   [optional] The mode
     *
     * @throws InvalidStream
     *
     * @return void
     */
    protected function setStream(string $stream, string $mode = null): void
    {
        // Set the mode
        $mode = $mode ?? 'rb';

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

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     * Warning: This could attempt to load a large amount of data into memory.
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
     * Separates any underlying resources from the stream.
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
     * @throws InvalidStream
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
    public function getSize(): ?int
    {
        $fstat = null;

        // If the stream isn't set
        if ($this->isValidStream()) {
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
     * @throws RuntimeException on error.
     *
     * @return int Position of the file pointer
     */
    public function tell(): int
    {
        $this->verifyStream();

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
        if ($this->isValidStream()) {
            // Don't do anything
            return true;
        }

        return feof($this->stream);
    }

    /**
     * Returns the remaining contents in a string.
     *
     * @throws RuntimeException
     *          if unable to read or an error occurs while reading.
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
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     *
     * @param string $key Specific metadata to retrieve.
     *
     * @return array|mixed|null Returns an associative array if no key is
     *          provided. Returns a specific key value if a key is provided
     *          and the value is found, or null if the key is not found.
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
     * Is the stream valid.
     *
     * @return bool
     */
    protected function isValidStream(): bool
    {
        return null === $this->stream;
    }

    /**
     * Verify the stream.
     *
     * @return void
     */
    protected function verifyStream(): void
    {
        // If there is no stream
        if ($this->isValidStream()) {
            // Throw a runtime exception
            throw new RuntimeException('No resource available; cannot read');
        }
    }
}
