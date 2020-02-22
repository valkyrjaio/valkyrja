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

/**
 * Trait Write.
 *
 * @author Melech Mizrachi
 *
 * @property resource|null $stream
 */
trait Write
{
    /**
     * Returns whether or not the stream is writable.
     *
     * @return bool
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
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     *
     * @throws RuntimeException on failure.
     *
     * @return int Returns the number of bytes written to the stream.
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
     * Verify the stream.
     *
     * @return void
     */
    abstract protected function verifyStream(): void;

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
}
