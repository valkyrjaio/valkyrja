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

namespace Valkyrja\Http\Streams;

use RuntimeException;
use Valkyrja\Http\Exceptions\InvalidStream;

use function is_resource;

/**
 * Trait StreamHelpers.
 *
 * @author Melech Mizrachi
 */
trait StreamHelpers
{
    /**
     * Set the stream.
     *
     * @param string      $stream The stream
     * @param string|null $mode   [optional] The mode
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

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     *
     * @param string|null $key Specific metadata to retrieve.
     *
     * @return array|mixed|null Returns an associative array if no key is
     *          provided. Returns a specific key value if a key is provided
     *          and the value is found, or null if the key is not found.
     */
    abstract public function getMetadata(string $key = null);

    /**
     * Is mode writable.
     *
     * @param string $mode
     *
     * @return bool
     */
    protected function isModeWriteable(string $mode): bool
    {
        return false !== strpos($mode, 'x')
            || false !== strpos($mode, 'w')
            || false !== strpos($mode, 'c')
            || false !== strpos($mode, 'a')
            || false !== strpos($mode, '+');
    }

    /**
     * Verify the stream is writable.
     *
     * @return void
     */
    protected function verifyWritable(): void
    {
        // If the stream isn't writable
        if (! $this->isWritable()) {
            // Throw a new runtime exception
            throw new RuntimeException('Stream is not writable');
        }
    }

    /**
     * Verify the write result.
     *
     * @param string|false $result
     *
     * @return void
     */
    protected function verifyWriteResult($result): void
    {
        // If the write was not successful
        if (false === $result) {
            // Throw a runtime exception
            throw new RuntimeException('Error writing to stream');
        }
    }

    /**
     * Verify the stream is seekable.
     *
     * @return void
     */
    protected function verifySeekable(): void
    {
        // If the stream isn't seekable
        if (! $this->isSeekable()) {
            // Throw a new runtime exception
            throw new RuntimeException('Stream is not seekable');
        }
    }

    /**
     * Verify the seek result.
     *
     * @param int $result
     *
     * @return void
     */
    protected function verifySeekResult(int $result): void
    {
        // If the result was not a 0, denoting an error occurred
        if (0 !== $result) {
            // Throw a new runtime exception
            throw new RuntimeException('Error seeking within stream');
        }
    }

    /**
     * Is mode readable.
     *
     * @param string $mode
     *
     * @return bool
     */
    protected function isModeReadable(string $mode): bool
    {
        return false !== strpos($mode, 'r')
            || false !== strpos($mode, '+');
    }

    /**
     * Verify the stream is readable.
     *
     * @return void
     */
    protected function verifyReadable(): void
    {
        // If the stream is not readable
        if (! $this->isReadable()) {
            // Throw a runtime exception
            throw new RuntimeException('Stream is not readable');
        }
    }

    /**
     * Verify the read result.
     *
     * @param string|false $result
     *
     * @return void
     */
    protected function verifyReadResult($result): void
    {
        // If there was a failure in reading the stream
        if (false === $result) {
            // Throw a runtime exception
            throw new RuntimeException('Error reading stream');
        }
    }
}
