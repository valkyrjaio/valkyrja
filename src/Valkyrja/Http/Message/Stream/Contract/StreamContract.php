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

namespace Valkyrja\Http\Message\Stream\Contract;

use RuntimeException;
use Stringable;

use const SEEK_SET;

interface StreamContract extends Stringable
{
    /**
     * Get all the contents of the stream as a string.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     */
    public function __toString(): string;

    /**
     * Closes the stream and any underlying resources.
     */
    public function close(): void;

    /**
     * Separates any underlying resources from the stream.
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach();

    /**
     * Get the size of the stream if known.
     */
    public function getSize(): int|null;

    /**
     * Get the current position of the file read/write pointer.
     *
     * @throws RuntimeException on error
     */
    public function tell(): int;

    /**
     * Determine if the stream is at the end of the stream.
     */
    public function eof(): bool;

    /**
     * Determine whether the stream is seekable.
     */
    public function isSeekable(): bool;

    /**
     * Seek to a position in the stream.
     *
     * @see http://www.php.net/manual/en/function.fseek.php
     *
     * @throws RuntimeException on failure
     */
    public function seek(int $offset, int $whence = SEEK_SET): void;

    /**
     * Seek to the beginning of the stream.
     *
     * @see  http://www.php.net/manual/en/function.fseek.php
     *
     * @throws RuntimeException on failure
     */
    public function rewind(): void;

    /**
     * Determine whether the stream is writable.
     */
    public function isWritable(): bool;

    /**
     * Write data to the stream.
     *
     * @throws RuntimeException on failure
     */
    public function write(string $string): int;

    /**
     * Determine whether the stream is readable.
     */
    public function isReadable(): bool;

    /**
     * Read data from the stream up to a given length.
     *
     * @throws RuntimeException if an error occurs
     */
    public function read(int $length): string;

    /**
     * Get the contents of the stream.
     *
     * @throws RuntimeException if unable to read or an error occurs while
     *                          reading
     */
    public function getContents(): string;

    /**
     * Get the stream metadata.
     *
     * @see http://php.net/manual/en/function.stream-get-meta-data.php
     */
    public function getMetadata(string|null $key = null): mixed;
}
