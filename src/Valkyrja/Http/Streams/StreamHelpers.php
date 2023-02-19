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

use Valkyrja\Http\Exceptions\InvalidStream;
use Valkyrja\Http\Exceptions\StreamException;

use function fopen;
use function get_resource_type;
use function is_resource;

/**
 * Trait StreamHelpers.
 *
 * @author Melech Mizrachi
 */
trait StreamHelpers
{
    /**
     * The stream.
     *
     * @var resource|null
     */
    protected $stream;

    /**
     * @inheritDoc
     */
    abstract public function isSeekable(): bool;

    /**
     * @inheritDoc
     */
    abstract public function isReadable(): bool;

    /**
     * @inheritDoc
     */
    abstract public function isWritable(): bool;

    /**
     * @inheritDoc
     */
    abstract public function getMetadata(string $key = null): mixed;

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
        if (! is_resource($resource) || get_resource_type($resource) !== 'stream') {
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
    protected function isInValidStream(): bool
    {
        return $this->stream === null;
    }

    /**
     * Verify the stream.
     *
     * @return void
     */
    protected function verifyStream(): void
    {
        // If there is no stream
        if ($this->isInValidStream()) {
            // Throw a runtime exception
            throw new InvalidStream('No resource available; cannot read');
        }
    }

    /**
     * Is mode writable.
     *
     * @param string $mode
     *
     * @return bool
     */
    protected function isModeWriteable(string $mode): bool
    {
        return str_contains($mode, 'x')
            || str_contains($mode, 'w')
            || str_contains($mode, 'c')
            || str_contains($mode, 'a')
            || str_contains($mode, '+');
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
            throw new StreamException('Stream is not writable');
        }
    }

    /**
     * Verify the write result.
     *
     * @param int|false $result
     *
     * @return void
     */
    protected function verifyWriteResult(int|false $result): void
    {
        // If the write was not successful
        if ($result === false) {
            // Throw a runtime exception
            throw new StreamException('Error writing to stream');
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
            throw new StreamException('Stream is not seekable');
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
        if ($result !== 0) {
            // Throw a new runtime exception
            throw new StreamException('Error seeking within stream');
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
        return str_contains($mode, 'r')
            || str_contains($mode, '+');
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
            throw new StreamException('Stream is not readable');
        }
    }

    /**
     * Verify the read result.
     *
     * @param string|false $result
     *
     * @return void
     */
    protected function verifyReadResult(string|false $result): void
    {
        // If there was a failure in reading the stream
        if ($result === false) {
            // Throw a runtime exception
            throw new StreamException('Error reading stream');
        }
    }
}
