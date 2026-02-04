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
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Enum\Mode;
use Valkyrja\Http\Message\Stream\Enum\ModeTranslation;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidStreamException;

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

    /**
     * Validate a stream resouce.
     *
     * @psalm-assert resource $resource
     *
     * @phpstan-assert resource $resource
     */
    private static function validateStream(mixed $resource): void
    {
        if (! self::isStream($resource)) {
            throw new InvalidStreamException('Invalid stream provided');
        }
    }
}
