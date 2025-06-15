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

namespace Valkyrja\Http\Message\File\Exception;

use Valkyrja\Http\Message\File\Enum\UploadError;

/**
 * Class UploadErrorException.
 *
 * @author Melech Mizrachi
 */
class UploadErrorException extends RuntimeException
{
    /** @var string */
    public const FORM_SIZE_MESSAGE = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
    /** @var string */
    public const INI_SIZE_MESSAGE = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
    /** @var string */
    public const PARTIAL_MESSAGE = 'The uploaded file was only partially uploaded';
    /** @var string */
    public const NO_FILE_MESSAGE = 'No file was uploaded';
    /** @var string */
    public const NO_TMP_DIR_MESSAGE = 'Missing a temporary folder';
    /** @var string */
    public const CANT_WRITE_MESSAGE = 'Failed to write file to disk';
    /** @var string */
    public const EXTENSION_MESSAGE = 'A PHP extension stopped the file upload';
    /** @var string */
    public const OK_MESSAGE = 'OK is not a valid upload error';

    public function __construct(UploadError $uploadError, int $code = 0, Throwable|null $previous = null)
    {
        $message = match ($uploadError) {
            UploadError::FORM_SIZE => static::FORM_SIZE_MESSAGE,
            UploadError::INI_SIZE => static::INI_SIZE_MESSAGE,
            UploadError::PARTIAL => static::PARTIAL_MESSAGE,
            UploadError::NO_FILE => static::NO_FILE_MESSAGE,
            UploadError::NO_TMP_DIR => static::NO_TMP_DIR_MESSAGE,
            UploadError::CANT_WRITE => static::CANT_WRITE_MESSAGE,
            UploadError::EXTENSION => static::EXTENSION_MESSAGE,
            UploadError::OK => throw new InvalidArgumentException(static::OK_MESSAGE),
        };

        parent::__construct($message, $code, $previous);
    }
}
