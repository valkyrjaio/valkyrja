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

namespace Valkyrja\Tests\Unit\Http\Message\File\Constant;

use Valkyrja\Http\Message\File\Constant\UploadErrorExceptionMessage;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileInvalidUploadErrorException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileUploadErrorException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class UploadErrorExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        self::assertSame(
            UploadErrorExceptionMessage::INI_SIZE_MESSAGE,
            new UploadedFileUploadErrorException(UploadError::INI_SIZE)->getMessage()
        );

        self::assertSame(
            UploadErrorExceptionMessage::FORM_SIZE_MESSAGE,
            new UploadedFileUploadErrorException(UploadError::FORM_SIZE)->getMessage()
        );

        self::assertSame(
            UploadErrorExceptionMessage::PARTIAL_MESSAGE,
            new UploadedFileUploadErrorException(UploadError::PARTIAL)->getMessage()
        );

        self::assertSame(
            UploadErrorExceptionMessage::NO_FILE_MESSAGE,
            new UploadedFileUploadErrorException(UploadError::NO_FILE)->getMessage()
        );

        self::assertSame(
            UploadErrorExceptionMessage::NO_TMP_DIR_MESSAGE,
            new UploadedFileUploadErrorException(UploadError::NO_TMP_DIR)->getMessage()
        );

        self::assertSame(
            UploadErrorExceptionMessage::CANT_WRITE_MESSAGE,
            new UploadedFileUploadErrorException(UploadError::CANT_WRITE)->getMessage()
        );

        self::assertSame(
            UploadErrorExceptionMessage::EXTENSION_MESSAGE,
            new UploadedFileUploadErrorException(UploadError::EXTENSION)->getMessage()
        );
    }

    public function testOkException(): void
    {
        $this->expectException(UploadedFileInvalidUploadErrorException::class);
        $this->expectExceptionMessage(UploadErrorExceptionMessage::OK_MESSAGE);

        new UploadedFileUploadErrorException(UploadError::OK);
    }
}
