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

/**
 * Trait Read.
 *
 * @author Melech Mizrachi
 *
 * @property resource|null $stream
 */
trait Read
{
    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
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
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *                    them. Fewer than $length bytes may be returned if
     *                    underlying stream call returns fewer bytes.
     *
     * @throws RuntimeException if an error occurs.
     *
     * @return string Returns the data read from the stream, or an empty string
     *          if no bytes are available.
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
     * Is the stream valid.
     *
     * @return bool
     */
    abstract protected function isValidStream(): bool;

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
     * Verify the stream.
     *
     * @return void
     */
    abstract protected function verifyStream(): void;

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
