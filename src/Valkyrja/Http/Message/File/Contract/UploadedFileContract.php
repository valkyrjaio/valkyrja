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

namespace Valkyrja\Http\Message\File\Contract;

use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;

interface UploadedFileContract
{
    /**
     * Get the stream representing the uploaded file.
     *
     * @throws RuntimeException in cases when no stream is available or can be
     *                          created
     */
    public function getStream(): StreamContract;

    /**
     * Move the file to a specified target path.
     *
     * @see http://php.net/is_uploaded_file
     * @see http://php.net/move_uploaded_file
     *
     * @throws InvalidArgumentException if the $targetPath specified is
     *                                  invalid
     * @throws RuntimeException         on any error during the move
     *                                  operation, or on the second or
     *                                  subsequent call to the method
     */
    public function moveTo(string $targetPath): void;

    /**
     * Get the file size.
     */
    public function getSize(): int|null;

    /**
     * Get the upload error.
     *
     * @see http://php.net/manual/en/features.file-upload.errors.php
     */
    public function getError(): UploadError;

    /**
     * Get the client filename.
     */
    public function getClientFilename(): string|null;

    /**
     * Get the client media type.
     */
    public function getClientMediaType(): string|null;
}
