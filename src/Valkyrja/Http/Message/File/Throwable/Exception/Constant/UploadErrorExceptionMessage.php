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

namespace Valkyrja\Http\Message\File\Throwable\Exception\Constant;

final class UploadErrorExceptionMessage
{
    /** @var non-empty-string */
    public const string FORM_SIZE_MESSAGE = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
    /** @var non-empty-string */
    public const string INI_SIZE_MESSAGE = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
    /** @var non-empty-string */
    public const string PARTIAL_MESSAGE = 'The uploaded file was only partially uploaded';
    /** @var non-empty-string */
    public const string NO_FILE_MESSAGE = 'No file was uploaded';
    /** @var non-empty-string */
    public const string NO_TMP_DIR_MESSAGE = 'Missing a temporary folder';
    /** @var non-empty-string */
    public const string CANT_WRITE_MESSAGE = 'Failed to write file to disk';
    /** @var non-empty-string */
    public const string EXTENSION_MESSAGE = 'A PHP extension stopped the file upload';
    /** @var non-empty-string */
    public const string OK_MESSAGE = 'OK is not a valid upload error';
}
