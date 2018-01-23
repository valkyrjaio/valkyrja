<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage;

use InvalidArgumentException;
use RuntimeException;

/**
 * Value object representing a file uploaded through an HTTP request.
 *
 * Instances of this interface are considered immutable; all methods that
 * might change state MUST be implemented such that they retain the internal
 * state of the current instance and return an instance that contains the
 * changed state.
 *
 * @author Melech Mizrachi
 */
class NativeUploadedFile implements UploadedFile
{
    /**
     * The uploaded file.
     *
     * @var string
     */
    protected $file;

    /**
     * The uploaded file as a stream.
     *
     * @var \Valkyrja\HttpMessage\Stream
     */
    protected $stream;

    /**
     * THe uploaded file size.
     *
     * @var int
     */
    protected $size;

    /**
     * The error status. One of UPLOAD_ERR_* constant.
     *
     * @var int
     */
    protected $errorStatus;

    /**
     * The uploaded file's name.
     *
     * @var string
     */
    protected $fileName;

    /**
     * The uploaded file's media type.
     *
     * @var string
     */
    protected $mediaType;

    /**
     * Whether the file has been moved yet.
     *
     * @var bool
     */
    protected $moved = false;

    /**
     * NativeUploadedFile constructor.
     *
     * @param int    $size        The file size
     * @param int    $errorStatus The error status
     * @param string $file        [optional] The file
     * @param Stream $stream      [optional] The stream
     * @param string $fileName    [optional] The file name
     * @param string $mediaType   [optional] The file media type
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        int $size,
        int $errorStatus,
        string $file = null,
        Stream $stream = null,
        string $fileName = null,
        string $mediaType = null
    ) {
        // If the error is less than the lowest valued UPLOAD_ERR_* constant
        // Or the error is greater than the highest valued UPLOAD_ERR_* constant
        if (
            UPLOAD_ERR_OK > $errorStatus
            || $errorStatus > UPLOAD_ERR_EXTENSION
        ) {
            // Throw an invalid argument exception for the error status
            throw new InvalidArgumentException(
                'Invalid error status for UploadedFile;'
                . ' must be an UPLOAD_ERR_* constant value.'
            );
        }

        // If the file is not set
        // and the stream is not set
        if (null === $file && null === $stream) {
            // Throw an invalid argument exception as on or the other
            // is required
            throw new InvalidArgumentException(
                'Either one of file or stream are required.'
                . ' Neither passed as arguments.'
            );
        }

        $this->file        = $file;
        $this->size        = $size;
        $this->errorStatus = $errorStatus;
        $this->stream      = $stream;
        $this->fileName    = $fileName;
        $this->mediaType   = $mediaType;
    }

    /**
     * Retrieve a stream representing the uploaded file.
     *
     * This method MUST return a StreamInterface instance, representing the
     * uploaded file. The purpose of this method is to allow utilizing native
     * PHP stream functionality to manipulate the file upload, such as
     * stream_copy_to_stream() (though the result will need to be decorated in
     * a native PHP stream wrapper to work with such functions).
     *
     * If the moveTo() method has been called previously, this method MUST
     * raise an exception.
     *
     * @throws \RuntimeException in cases when no stream is available or can
     *          be created.
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStream
     *
     * @return \Valkyrja\HttpMessage\Stream Stream representation of the
     *          uploaded file.
     */
    public function getStream(): Stream
    {
        // If the error status is not OK
        if (UPLOAD_ERR_OK !== $this->errorStatus) {
            // Throw a runtime exception as there's been an uploaded file error
            throw new RuntimeException(
                'Cannot retrieve stream due to upload error'
            );
        }

        // If the file has already been moved
        if ($this->moved) {
            // Throw a runtime exception as subsequent moves are not allowed
            // in PSR-7
            throw new RuntimeException(
                'Cannot retrieve stream after it has already been moved'
            );
        }

        // If the stream has been set
        if (null !== $this->stream) {
            // Return the stream
            return $this->stream;
        }

        // Set the stream as a new native stream
        $this->stream = new NativeStream($this->file);

        return $this->stream;
    }

    /**
     * Move the uploaded file to a new location.
     *
     * Use this method as an alternative to move_uploaded_file(). This method
     * is
     * guaranteed to work in both SAPI and non-SAPI environments.
     * Implementations must determine which environment they are in, and use
     * the appropriate method (move_uploaded_file(), rename(), or a stream
     * operation) to perform the operation.
     *
     * $targetPath may be an absolute path, or a relative path. If it is a
     * relative path, resolution should be the same as used by PHP's rename()
     * function.
     *
     * The original file or stream MUST be removed on completion.
     *
     * If this method is called more than once, any subsequent calls MUST raise
     * an exception.
     *
     * When used in an SAPI environment where $_FILES is populated, when
     * writing files via moveTo(), is_uploaded_file() and
     * move_uploaded_file() SHOULD be used to ensure permissions and upload
     * status are verified correctly.
     *
     * If you wish to move to a stream, use getStream(), as SAPI operations
     * cannot guarantee writing to stream destinations.
     *
     * @see http://php.net/is_uploaded_file
     * @see http://php.net/move_uploaded_file
     *
     * @param string $targetPath Path to which to move the uploaded file.
     *
     * @throws \InvalidArgumentException if the $targetPath specified is
     *          invalid.
     * @throws \RuntimeException on any error during the move operation, or
     *          on the second or subsequent call to the method.
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStream
     *
     * @return void
     */
    public function moveTo(string $targetPath): void
    {
        // If the error status is not OK
        if (UPLOAD_ERR_OK !== $this->errorStatus) {
            // Throw a runtime exception as there's been an uploaded file error
            throw new RuntimeException(
                'Cannot retrieve stream due to upload error'
            );
        }

        // If the file has already been moved
        if ($this->moved) {
            // Throw a runtime exception as subsequent moves are not allowed
            // in PSR-7
            throw new RuntimeException(
                'Cannot move file after it has already been moved'
            );
        }

        $targetDirectory = \dirname($targetPath);

        // If the target directory is not a directory
        // or the target directory is not writable
        if (! is_dir($targetDirectory) || ! is_writable($targetDirectory)) {
            // Throw a runtime exception
            throw new RuntimeException(
                sprintf(
                    'The target directory `%s` does not exists '
                    . 'or is not writable',
                    $targetDirectory
                )
            );
        }

        $sapi = PHP_SAPI;

        // If the PHP_SAPI value is empty
        // or there is no file
        // or the PHP_SAPI value is set to a CLI environment
        if (
            empty($sapi)
            || ! $this->file
            || 0 === strpos($sapi, 'cli')
        ) {
            // Non-SAPI environment, or no filename present
            $this->writeStream($targetPath);
        }
        // Otherwise try to use the move_uploaded_file function
        // and if the move_uploaded_file function call failed
        elseif (false === move_uploaded_file($this->file, $targetPath)) {
            // Throw a runtime exception
            throw new RuntimeException(
                'Error occurred while moving uploaded file'
            );
        }

        $this->moved = true;
    }

    /**
     * Retrieve the file size.
     *
     * Implementations SHOULD return the value stored in the "size" key of
     * the file in the $_FILES array if available, as PHP calculates this based
     * on the actual size transmitted.
     *
     * @return int|null The file size in bytes or null if unknown.
     */
    public function getSize(): ? int
    {
        return $this->size;
    }

    /**
     * Retrieve the error associated with the uploaded file.
     *
     * The return value MUST be one of PHP's UPLOAD_ERR_XXX constants.
     *
     * If the file was uploaded successfully, this method MUST return
     * UPLOAD_ERR_OK.
     *
     * Implementations SHOULD return the value stored in the "error" key of
     * the file in the $_FILES array.
     *
     * @see http://php.net/manual/en/features.file-upload.errors.php
     *
     * @return int One of PHP's UPLOAD_ERR_XXX constants.
     */
    public function getError(): int
    {
        return $this->errorStatus;
    }

    /**
     * Retrieve the filename sent by the client.
     *
     * Do not trust the value returned by this method. A client could send
     * a malicious filename with the intention to corrupt or hack your
     * application.
     *
     * Implementations SHOULD return the value stored in the "name" key of
     * the file in the $_FILES array.
     *
     * @return string|null The filename sent by the client or null if none was
     *          provided.
     */
    public function getClientFilename(): ? string
    {
        return $this->fileName;
    }

    /**
     * Retrieve the media type sent by the client.
     *
     * Do not trust the value returned by this method. A client could send
     * a malicious media type with the intention to corrupt or hack your
     * application.
     *
     * Implementations SHOULD return the value stored in the "type" key of
     * the file in the $_FILES array.
     *
     * @return string|null The media type sent by the client or null if none
     *          was provided.
     */
    public function getClientMediaType(): ? string
    {
        return $this->mediaType;
    }

    /**
     * Write the stream to a path.
     *
     * @param string $path The path to write the stream to
     *
     * @throws \RuntimeException
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStream
     *
     * @return void
     */
    protected function writeStream(string $path): void
    {
        // Attempt to open the path specified
        $handle = fopen($path, 'wb+');

        // If the handler failed to open
        if (false === $handle) {
            // Throw a runtime exception
            throw new RuntimeException(
                'Unable to write to designated path'
            );
        }

        // Get the stream
        $stream = $this->getStream();
        // Rewind the stream
        $stream->rewind();

        // While the end of file hasn't been reached
        while (! $stream->eof()) {
            // Write the stream's contents to the handler
            fwrite($handle, $stream->read(4096));
        }

        // Close the file path
        fclose($handle);
    }
}
