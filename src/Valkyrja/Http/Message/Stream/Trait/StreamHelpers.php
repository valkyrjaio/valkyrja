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

namespace Valkyrja\Http\Message\Stream\Trait;

use Valkyrja\Http\Message\Stream\Enum\Mode;
use Valkyrja\Http\Message\Stream\Enum\ModeTranslation;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\NoStreamAvailableException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamReadException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamSeekException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamTellException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamWriteException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\UnreadableStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\UnseekableStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\UnwritableStreamException;

use function fopen;
use function get_resource_type;
use function is_resource;
use function str_contains;

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
    protected $resource;

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
    abstract public function getMetadata(string|null $key = null): mixed;

    /**
     * Set the stream.
     *
     * @param PhpWrapper|string $stream          The stream
     * @param Mode              $mode            [optional] The mode
     * @param ModeTranslation   $modeTranslation [optional] The mode translation
     *
     * @throws InvalidStreamException
     *
     * @return void
     */
    protected function setStream(
        PhpWrapper|string $stream = PhpWrapper::temp,
        Mode $mode = Mode::WRITE_READ,
        ModeTranslation $modeTranslation = ModeTranslation::BINARY_SAFE
    ): void {
        // Set the mode
        $fopenMode = $mode->value . $modeTranslation->value;

        $streamType = $stream instanceof PhpWrapper
            ? $stream->value
            : $stream;

        // Open a new resource stream
        $resource = $this->openStream($streamType, $fopenMode);

        // If the resource isn't a resource or a stream resource type
        if (! $this->isStream($resource)) {
            // Throw a new invalid stream exception
            throw new InvalidStreamException('Invalid stream provided');
        }

        /** @var resource $resource */

        // Set the stream
        $this->resource = $resource;
    }

    /**
     * Open a stream.
     *
     * @return resource|false
     */
    protected function openStream(string $filename, string $mode)
    {
        return fopen($filename, $mode);
    }

    protected function isStream(mixed $resource): bool
    {
        return is_resource($resource) && get_resource_type($resource) === 'stream';
    }

    /**
     * Is the stream valid.
     *
     * @return bool
     */
    protected function isInvalidStream(): bool
    {
        return $this->resource === null;
    }

    /**
     * Verify the stream.
     *
     * @return void
     */
    protected function verifyStream(): void
    {
        // If there is no stream
        if ($this->isInvalidStream()) {
            // Throw a runtime exception
            throw new NoStreamAvailableException('No stream resource');
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
            throw new UnwritableStreamException('Stream is not writable');
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
            throw new StreamWriteException('Error writing to stream');
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
            throw new UnseekableStreamException('Stream is not seekable');
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
            throw new StreamSeekException('Error seeking within stream');
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
            throw new UnreadableStreamException('Stream is not readable');
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
            throw new StreamReadException('Error reading stream');
        }
    }

    /**
     * Verify the tell result.
     *
     * @param int|false $result
     *
     * @return void
     */
    protected function verifyTellResult(int|false $result): void
    {
        // If the tell is not an int
        if ($result === false) {
            // Throw a runtime exception
            throw new StreamTellException('Error occurred during tell operation');
        }
    }
}
