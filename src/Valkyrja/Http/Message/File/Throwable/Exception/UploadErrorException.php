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

namespace Valkyrja\Http\Message\File\Throwable\Exception;

use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\Throwable\Contract\Throwable;
use Valkyrja\Http\Message\File\Throwable\Exception\Constant\UploadErrorExceptionMessage;

class UploadErrorException extends RuntimeException
{
    public function __construct(UploadError $uploadError, int $code = 0, Throwable|null $previous = null)
    {
        $message = match ($uploadError) {
            UploadError::FORM_SIZE  => UploadErrorExceptionMessage::FORM_SIZE_MESSAGE,
            UploadError::INI_SIZE   => UploadErrorExceptionMessage::INI_SIZE_MESSAGE,
            UploadError::PARTIAL    => UploadErrorExceptionMessage::PARTIAL_MESSAGE,
            UploadError::NO_FILE    => UploadErrorExceptionMessage::NO_FILE_MESSAGE,
            UploadError::NO_TMP_DIR => UploadErrorExceptionMessage::NO_TMP_DIR_MESSAGE,
            UploadError::CANT_WRITE => UploadErrorExceptionMessage::CANT_WRITE_MESSAGE,
            UploadError::EXTENSION  => UploadErrorExceptionMessage::EXTENSION_MESSAGE,
            UploadError::OK         => throw new InvalidArgumentException(UploadErrorExceptionMessage::OK_MESSAGE),
        };

        parent::__construct($message, $code, $previous);
    }
}
