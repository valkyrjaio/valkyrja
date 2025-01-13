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

namespace Valkyrja\Tests\Unit\Http\Message\File\Exception;

use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\File\Exception\UploadErrorException;
use Valkyrja\Tests\Unit\TestCase;

class UploadErrorExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        self::assertSame(
            UploadErrorException::INI_SIZE_MESSAGE,
            (new UploadErrorException(UploadError::INI_SIZE))->getMessage()
        );

        self::assertSame(
            UploadErrorException::FORM_SIZE_MESSAGE,
            (new UploadErrorException(UploadError::FORM_SIZE))->getMessage()
        );

        self::assertSame(
            UploadErrorException::PARTIAL_MESSAGE,
            (new UploadErrorException(UploadError::PARTIAL))->getMessage()
        );

        self::assertSame(
            UploadErrorException::NO_FILE_MESSAGE,
            (new UploadErrorException(UploadError::NO_FILE))->getMessage()
        );

        self::assertSame(
            UploadErrorException::NO_TMP_DIR_MESSAGE,
            (new UploadErrorException(UploadError::NO_TMP_DIR))->getMessage()
        );

        self::assertSame(
            UploadErrorException::CANT_WRITE_MESSAGE,
            (new UploadErrorException(UploadError::CANT_WRITE))->getMessage()
        );

        self::assertSame(
            UploadErrorException::EXTENSION_MESSAGE,
            (new UploadErrorException(UploadError::EXTENSION))->getMessage()
        );
    }

    public function testOkException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(UploadErrorException::OK_MESSAGE);

        new UploadErrorException(UploadError::OK);
    }
}
