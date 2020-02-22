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

use const SEEK_SET;

/**
 * Trait Seek.
 *
 * @author Melech Mizrachi
 *
 * @property resource|null $stream
 */
trait Seek
{
    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
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
     * Seek to a position in the stream.
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     *
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *                    based on the seek offset. Valid values are identical
     *                    to the built-in PHP $whence values for `fseek()`.
     *                    SEEK_SET: Set position equal to offset bytes
     *                    SEEK_CUR: Set position to current location plus
     *                    offset SEEK_END: Set position to end-of-stream plus
     *                    offset.
     *
     * @throws RuntimeException on failure.
     *
     * @return void
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
     * Seek to the beginning of the stream.
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     *
     * @throws RuntimeException on failure.
     *
     * @return void
     *
     * @see  seek()
     */
    public function rewind(): void
    {
        $this->seek(0);
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
    abstract public function getMetadata(string $key = null);

    /**
     * Is the stream valid.
     *
     * @return bool
     */
    abstract protected function isValidStream(): bool;

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
     * Verify the stream.
     *
     * @return void
     */
    abstract protected function verifyStream(): void;
}
