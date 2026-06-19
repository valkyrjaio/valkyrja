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

namespace Valkyrja\Tests\Unit\Http\Message\File\Throwable\Exception;

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Http\Message\File\Constant\UploadErrorExceptionMessage;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileInvalidUploadErrorException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileUploadErrorException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class UploadedFileUploadErrorExceptionTest extends TestCase
{
    /**
     * @return array<string, array{UploadError, string}>
     */
    public static function provideUploadErrors(): array
    {
        return [
            'form size'  => [UploadError::FORM_SIZE, UploadErrorExceptionMessage::FORM_SIZE_MESSAGE],
            'ini size'   => [UploadError::INI_SIZE, UploadErrorExceptionMessage::INI_SIZE_MESSAGE],
            'partial'    => [UploadError::PARTIAL, UploadErrorExceptionMessage::PARTIAL_MESSAGE],
            'no file'    => [UploadError::NO_FILE, UploadErrorExceptionMessage::NO_FILE_MESSAGE],
            'no tmp dir' => [UploadError::NO_TMP_DIR, UploadErrorExceptionMessage::NO_TMP_DIR_MESSAGE],
            'cant write' => [UploadError::CANT_WRITE, UploadErrorExceptionMessage::CANT_WRITE_MESSAGE],
            'extension'  => [UploadError::EXTENSION, UploadErrorExceptionMessage::EXTENSION_MESSAGE],
        ];
    }

    #[DataProvider('provideUploadErrors')]
    public function testMessageMatchesUploadError(UploadError $uploadError, string $expectedMessage): void
    {
        $exception = new UploadedFileUploadErrorException($uploadError);

        self::assertSame($expectedMessage, $exception->getMessage());
    }

    public function testPreservesCodeAndPrevious(): void
    {
        $previous  = new UploadedFileUploadErrorException(UploadError::NO_FILE);
        $exception = new UploadedFileUploadErrorException(UploadError::PARTIAL, 7, $previous);

        self::assertSame(7, $exception->getCode());
        self::assertSame($previous, $exception->getPrevious());
    }

    public function testOkErrorThrowsInvalidUploadErrorException(): void
    {
        $this->expectException(UploadedFileInvalidUploadErrorException::class);
        $this->expectExceptionMessage(UploadErrorExceptionMessage::OK_MESSAGE);

        new UploadedFileUploadErrorException(UploadError::OK);
    }
}