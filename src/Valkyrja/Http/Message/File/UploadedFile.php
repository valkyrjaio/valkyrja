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

namespace Valkyrja\Http\Message\File;

use Override;
use Valkyrja\Http\Message\File\Contract\UploadedFileContract;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\Throwable\Exception\AlreadyMovedException;
use Valkyrja\Http\Message\File\Throwable\Exception\InvalidDirectoryException;
use Valkyrja\Http\Message\File\Throwable\Exception\InvalidUploadedFileException;
use Valkyrja\Http\Message\File\Throwable\Exception\MoveFailureException;
use Valkyrja\Http\Message\File\Throwable\Exception\UnableToWriteFileException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadErrorException;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidStreamException;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;

use function dirname;
use function fclose;
use function fopen;
use function fwrite;
use function is_dir;
use function is_file;
use function is_writable;
use function move_uploaded_file;
use function str_starts_with;
use function unlink;

use const PHP_SAPI;

class UploadedFile implements UploadedFileContract
{
    /**
     * Whether the file has been moved yet.
     *
     * @var bool
     */
    protected bool $hasBeenMoved = false;

    /**
     * @param string|null         $file        [optional] The file if not passed stream is required
     * @param StreamContract|null $stream      [optional] The stream if not passed file is required
     * @param UploadError         $uploadError [optional] The upload error
     * @param int|null            $size        [optional] The file size
     * @param string|null         $fileName    [optional] The file name
     * @param string|null         $mediaType   [optional] The file media type
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected string|null $file = null,
        protected StreamContract|null $stream = null,
        protected UploadError $uploadError = UploadError::OK,
        protected int|null $size = null,
        protected string|null $fileName = null,
        protected string|null $mediaType = null
    ) {
        // If the file is not set and the stream is not set
        if ($uploadError === UploadError::OK && $file === null && $stream === null) {
            // Throw an invalid argument exception as on or the other is required
            throw new InvalidUploadedFileException('One of file or stream are required');
        }
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getStream(): StreamContract
    {
        // If the error status is not OK
        if ($this->uploadError !== UploadError::OK) {
            // Throw a runtime exception as there's been an uploaded file error
            throw new UploadErrorException($this->uploadError);
        }

        // If the file has already been moved
        if ($this->hasBeenMoved) {
            // Throw a runtime exception as subsequent moves are not allowed in PSR-7
            throw new AlreadyMovedException('Cannot retrieve stream after it has already been moved');
        }

        // If the stream has been set
        if ($this->stream !== null) {
            // Return the stream
            return $this->stream;
        }

        // This should be impossible, but here just in case __construct is overridden
        if ($this->file === null) {
            throw new InvalidUploadedFileException('One of file or stream are required');
        }

        // Set the stream as a new native stream
        $this->stream = new Stream($this->file);

        return $this->stream;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function moveTo(string $targetPath): void
    {
        // If the error status is not OK
        if ($this->uploadError !== UploadError::OK) {
            // Throw a runtime exception as there's been an uploaded file error
            throw new UploadErrorException($this->uploadError);
        }

        // If the file has already been moved
        if ($this->hasBeenMoved) {
            // Throw a runtime exception as subsequent moves are not allowed
            // in PSR-7
            throw new AlreadyMovedException('Cannot move file after it has already been moved');
        }

        $targetDirectory = $this->getDirectoryName($targetPath);

        // If the target directory is not a directory
        // or the target directory is not writable
        if (! $this->isDir($targetDirectory) || ! $this->isWritable($targetDirectory)) {
            // Throw a runtime exception
            throw new InvalidDirectoryException(
                "The target directory `$targetDirectory` does not exists or is not writable"
            );
        }

        if ($this->shouldWriteStream()) {
            // Non-SAPI environment, or no filename present
            $this->writeStream($targetPath);

            $this->stream?->close();

            if ($this->file !== null && is_file($this->file)) {
                $this->deleteFile($this->file);
            }
        } elseif (! $this->moveUploadedFile($this->file ?? '', $targetPath)) {
            // Otherwise try to use the move_uploaded_file function
            // and if the move_uploaded_file function call failed
            // Throw a runtime exception
            throw new MoveFailureException('Error occurred while moving uploaded file');
        }

        $this->hasBeenMoved = true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getSize(): int|null
    {
        return $this->size;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getError(): UploadError
    {
        return $this->uploadError;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getClientFilename(): string|null
    {
        return $this->fileName;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getClientMediaType(): string|null
    {
        return $this->mediaType;
    }

    /**
     * Write the stream to a path.
     *
     * @param string $path The path to write the stream to
     *
     * @throws InvalidStreamException
     */
    protected function writeStream(string $path): void
    {
        // Attempt to open the path specified
        $handle = $this->openStream($path);

        // If the handler failed to open
        if ($handle === false) {
            // Throw a runtime exception
            throw new UnableToWriteFileException('Unable to write to designated path');
        }

        // Get the stream
        $stream = $this->getStream();
        // Rewind the stream
        $stream->rewind();

        // While the end of the stream hasn't been reached
        while (! $stream->eof()) {
            // Write the stream's contents to the handler
            $this->writeToStream($handle, $stream->read(4096));
        }

        // Close the path
        $this->closeStream($handle);
    }

    /**
     * Get the PHP_SAPI value.
     */
    protected function getPhpSapi(): string
    {
        return PHP_SAPI;
    }

    /**
     * Determine if a new stream should be opened to move the file.
     */
    protected function shouldWriteStream(): bool
    {
        $sapi = $this->getPhpSapi();

        // If the PHP_SAPI value is empty
        // or there is no file
        // or the PHP_SAPI value is set to a CLI environment
        return empty($sapi)
            || $this->file === null
            || $this->file === ''
            || str_starts_with($sapi, 'cli')
            || str_starts_with($sapi, 'phpdbg');
    }

    /**
     * Get the directory name for a given path.
     *
     * @param string $path The path
     */
    protected function getDirectoryName(string $path): string
    {
        return dirname($path);
    }

    /**
     * Open a stream.
     *
     * @return resource|false
     */
    protected function openStream(string $filename)
    {
        return fopen($filename, 'wb+');
    }

    /**
     * Write a stream.
     *
     * @param resource $stream The stream
     */
    protected function writeToStream($stream, string $data): int|false
    {
        return fwrite($stream, $data);
    }

    /**
     * Close a stream.
     *
     * @param resource $stream The stream
     */
    protected function closeStream($stream): bool
    {
        return fclose($stream);
    }

    /**
     * Determine if a filename is a directory.
     *
     * @param string $filename The file
     */
    protected function isDir(string $filename): bool
    {
        return is_dir($filename);
    }

    /**
     * Determine if a file is writable.
     *
     * @param string $filename The file
     */
    protected function isWritable(string $filename): bool
    {
        return is_writable($filename);
    }

    /**
     * Move an uploaded file.
     *
     * @param string $from Path to move from
     * @param string $to   Path to move to
     */
    protected function moveUploadedFile(string $from, string $to): bool
    {
        return move_uploaded_file($from, $to);
    }

    /**
     * Delete a file.
     */
    protected function deleteFile(string $filename): bool
    {
        return unlink($filename);
    }
}
