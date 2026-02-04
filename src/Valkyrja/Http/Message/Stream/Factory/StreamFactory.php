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

namespace Valkyrja\Http\Message\Stream\Factory;

use Psr\Http\Message\StreamInterface;
use Throwable;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Enum\Mode;
use Valkyrja\Http\Message\Stream\Enum\ModeTranslation;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamReadException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamSeekException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamTellException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamWriteException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\UnreadableStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\UnseekableStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\UnwritableStreamException;

use function is_resource;

abstract class StreamFactory
{
    public static function fromPsr(StreamInterface $stream): StreamContract
    {
        $stream->rewind();
        $contents = $stream->getContents();
        $stream->rewind();

        $valkyrjaStream = new Stream(PhpWrapper::temp);
        $valkyrjaStream->write($contents);
        $valkyrjaStream->rewind();

        return $valkyrjaStream;
    }

    /**
     * Get the resource stream.
     *
     * @param PhpWrapper|string $stream          The stream
     * @param Mode              $mode            [optional] The mode
     * @param ModeTranslation   $modeTranslation [optional] The mode translation
     *
     * @throws InvalidStreamException
     *
     * @return resource
     */
    public static function getResourceStream(
        PhpWrapper|string $stream = PhpWrapper::temp,
        Mode $mode = Mode::WRITE_READ,
        ModeTranslation $modeTranslation = ModeTranslation::BINARY_SAFE
    ) {
        // Set the mode
        $fopenMode = $mode->value . $modeTranslation->value;

        $streamType = $stream instanceof PhpWrapper
            ? $stream->value
            : $stream;

        // Open a new resource stream
        $resource = self::openStream($streamType, $fopenMode);

        self::validateStream($resource);

        return $resource;
    }

    /**
     * Determine if a given mode is writable.
     */
    public static function isModeWriteable(string $mode): bool
    {
        return str_contains($mode, 'x')
            || str_contains($mode, 'w')
            || str_contains($mode, 'c')
            || str_contains($mode, 'a')
            || str_contains($mode, '+');
    }

    /**
     * Determine if a given mode is readable.
     */
    public static function isModeReadable(string $mode): bool
    {
        return str_contains($mode, 'r')
            || str_contains($mode, '+');
    }

    /**
     * Get a stream as a string.
     */
    public static function toString(StreamContract $stream): string
    {
        // If the stream is not readable
        if (! $stream->isReadable()) {
            // Return an empty string
            return '';
        }

        try {
            // Rewind the stream to the start
            $stream->rewind();

            // Get the stream's contents
            return $stream->getContents();
        } catch (Throwable) {
            // Return a string
            return '';
        }
    }

    /**
     * Verify the stream is writable.
     */
    public static function verifyWritable(StreamContract $stream): void
    {
        // If the stream isn't writable
        if (! $stream->isWritable()) {
            // Throw a new runtime exception
            UnwritableStreamException::throw('Stream is not writable');
        }
    }

    /**
     * Verify the write result.
     *
     * @psalm-assert int   $result
     *
     * @phpstan-assert int $result
     */
    public static function verifyWriteResult(int|false $result): void
    {
        // If the write was not successful
        if ($result === false) {
            // Throw a runtime exception
            StreamWriteException::throw('Error writing to stream');
        }
    }

    /**
     * Verify the stream is seekable.
     */
    public static function verifySeekable(StreamContract $stream): void
    {
        // If the stream isn't seekable
        if (! $stream->isSeekable()) {
            // Throw a new runtime exception
            UnseekableStreamException::throw('Stream is not seekable');
        }
    }

    /**
     * Verify the seek result.
     *
     * @psalm-assert int<1, max>   $result
     *
     * @phpstan-assert int<1, max> $result
     */
    public static function verifySeekResult(int $result): void
    {
        // If the result was not a 0, denoting an error occurred
        if ($result !== 0) {
            // Throw a new runtime exception
            StreamSeekException::throw('Error seeking within stream');
        }
    }

    /**
     * Verify the stream is readable.
     */
    public static function verifyReadable(StreamContract $stream): void
    {
        // If the stream is not readable
        if (! $stream->isReadable()) {
            // Throw a runtime exception
            UnreadableStreamException::throw('Stream is not readable');
        }
    }

    /**
     * Verify the read result.
     *
     * @psalm-assert string   $result
     *
     * @phpstan-assert string $result
     */
    public static function verifyReadResult(string|false $result): void
    {
        // If there was a failure in reading the stream
        if ($result === false) {
            // Throw a runtime exception
            StreamReadException::throw('Error reading stream');
        }
    }

    /**
     * Verify the tell result.
     *
     * @psalm-assert int   $result
     *
     * @phpstan-assert int $result
     */
    public static function verifyTellResult(int|false $result): void
    {
        // If the tell is not an int
        if ($result === false) {
            // Throw a runtime exception
            StreamTellException::throw('Error occurred during tell operation');
        }
    }

    /**
     * Validate a stream resource.
     *
     * @psalm-assert resource $resource
     *
     * @phpstan-assert resource $resource
     */
    public static function validateStream(mixed $resource): void
    {
        if (! self::isStream($resource)) {
            throw new InvalidStreamException('Invalid stream provided');
        }
    }

    /**
     * Open a stream.
     *
     * @return resource|false
     */
    private static function openStream(string $filename, string $mode)
    {
        return fopen($filename, $mode);
    }

    /**
     * Check if a resource is a valid stream resource.
     */
    private static function isStream(mixed $resource): bool
    {
        return is_resource($resource) && get_resource_type($resource) === 'stream';
    }
}
