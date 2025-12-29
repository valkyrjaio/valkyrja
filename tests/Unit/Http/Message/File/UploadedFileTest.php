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

namespace Valkyrja\Tests\Unit\Http\Message\File;

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\Exception\AlreadyMovedException;
use Valkyrja\Http\Message\File\Exception\InvalidDirectoryException;
use Valkyrja\Http\Message\File\Exception\InvalidUploadedFileException;
use Valkyrja\Http\Message\File\Exception\MoveFailureException;
use Valkyrja\Http\Message\File\Exception\UnableToWriteFileException;
use Valkyrja\Http\Message\File\Exception\UploadErrorException;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Support\Directory;
use Valkyrja\Tests\Classes\Http\Message\File\InvalidDirectoryExceptionClass;
use Valkyrja\Tests\Classes\Http\Message\File\InvalidUploadedFileExceptionClass;
use Valkyrja\Tests\Classes\Http\Message\File\MoveFailureExceptionClass;
use Valkyrja\Tests\Classes\Http\Message\File\MoveUploadedFileClass;
use Valkyrja\Tests\Classes\Http\Message\File\UnableToWriteFileExceptionClass;
use Valkyrja\Tests\Unit\TestCase;

use function unlink;

class UploadedFileTest extends TestCase
{
    public static function invalidUploadErrorsProvider(): array
    {
        return [
            [UploadError::INI_SIZE],
            [UploadError::FORM_SIZE],
            [UploadError::PARTIAL],
            [UploadError::NO_FILE],
            [UploadError::NO_TMP_DIR],
            [UploadError::CANT_WRITE],
            [UploadError::EXTENSION],
        ];
    }

    public function testInvalidFile(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new UploadedFile(uploadError: UploadError::OK);
    }

    #[DataProvider('invalidUploadErrorsProvider')]
    public function testInvalidFileNotThrownForUploadErrors(UploadError $error): void
    {
        $uploadedFile = new UploadedFile(uploadError: $error);

        self::assertSame($error, $uploadedFile->getError());
    }

    public function testStream(): void
    {
        $stream = new Stream();
        $stream->write($contents = 'test');

        $uploadedFile = new UploadedFile(stream: $stream, uploadError: UploadError::OK);

        $uploadedFile->getStream()->rewind();

        self::assertSame($stream, $uploadedFile->getStream());
        self::assertSame($contents, $uploadedFile->getStream()->getContents());
    }

    #[DataProvider('invalidUploadErrorsProvider')]
    public function testGetStreamUploadErrorException(UploadError $error): void
    {
        $this->expectException(UploadErrorException::class);

        $stream = new Stream();
        $stream->write('test');

        $uploadedFile = new UploadedFile(stream: $stream, uploadError: $error);
        $uploadedFile->getStream();
    }

    public function testSubsequentMoveGetStreamException(): void
    {
        $this->expectException(AlreadyMovedException::class);

        $file = Directory::storagePath('/UploadedFileTest-testSubsequentMoveGetStreamException.txt');

        $stream = new Stream();
        $stream->write('test');

        $uploadedFile = new UploadedFile(stream: $stream);
        $uploadedFile->moveTo($file);

        unlink($file);

        $uploadedFile->getStream();
    }

    public function testInvalidUploadedFileException(): void
    {
        $this->expectException(InvalidUploadedFileException::class);

        $uploadedFile = new InvalidUploadedFileExceptionClass();
        $uploadedFile->getStream();
    }

    #[DataProvider('invalidUploadErrorsProvider')]
    public function testMoveUploadErrorException(UploadError $error): void
    {
        $this->expectException(UploadErrorException::class);

        $stream = new Stream();
        $stream->write('test');

        $uploadedFile = new UploadedFile(stream: $stream, uploadError: $error);
        $uploadedFile->moveTo(Directory::storagePath('/UploadedFileTest-testMoveUploadErrorException.txt'));
    }

    public function testSubsequentMoveException(): void
    {
        $this->expectException(AlreadyMovedException::class);

        $file = Directory::storagePath('/UploadedFileTest-testSubsequentMoveException.txt');

        $stream = new Stream();
        $stream->write('test');

        $uploadedFile = new UploadedFile(stream: $stream);
        $uploadedFile->moveTo($file);

        unlink($file);

        $uploadedFile->moveTo(Directory::storagePath('/UploadedFileTest-testSubsequentMoveException2.txt'));
    }

    public function testMoveTo(): void
    {
        $file  = Directory::storagePath('/UploadedFileTest-testMoveTo.txt');
        $file2 = Directory::storagePath('/UploadedFileTest-testMoveTo2.txt');
        $file3 = Directory::storagePath('/UploadedFileTest-testMoveTo3.txt');

        $stream = new Stream();
        $stream->write('test');

        $uploadedFile = new UploadedFile(stream: $stream);
        $uploadedFile->moveTo($file);

        // Ensure the stream was closed
        self::assertFalse($stream->isReadable());
        self::assertFalse($stream->isWritable());
        self::assertNull($stream->getMetadata());
        // Ensure new file was created
        self::assertFileExists($file);

        $uploadedFile2 = new UploadedFile(file: $file);
        $uploadedFile2->moveTo($file2);

        // Ensure previous file was deleted
        self::assertFileDoesNotExist($file);
        // Ensure new file was created
        self::assertFileExists($file2);

        $uploadedFile3 = new MoveUploadedFileClass(file: $file2);
        $uploadedFile3->moveTo($file3);

        // Ensure previous file was deleted
        self::assertFileDoesNotExist($file2);
        // Ensure new file was created
        self::assertFileExists($file3);

        // Delete the last created file
        unlink($file3);
    }

    public function testMoveFailureException(): void
    {
        $this->expectException(MoveFailureException::class);

        $file = Directory::storagePath('/uploadedFileTest-testMoveFailureException.txt');

        $stream = new Stream();
        $stream->write('test');

        $uploadedFile = new UploadedFile(stream: $stream);
        $uploadedFile->moveTo($file);

        // Should fail since this is not a valid uploaded file
        $uploadedFile2 = new MoveFailureExceptionClass(file: $file);
        $uploadedFile2->moveTo(Directory::storagePath('/uploadedFileTest-testMoveFailureException2.txt'));
    }

    public function testInvalidDirectoryException(): void
    {
        $this->expectException(InvalidDirectoryException::class);

        // Should fail since this is not a valid uploaded file
        $uploadedFile2 = new InvalidDirectoryExceptionClass(file: 'test');
        $uploadedFile2->moveTo('test2');
    }

    public function testUnableToWriteFileException(): void
    {
        $this->expectException(UnableToWriteFileException::class);

        // Should fail since this is not a valid uploaded file
        $uploadedFile2 = new UnableToWriteFileExceptionClass(file: 'test');
        $uploadedFile2->moveTo('test2');
    }

    public function testGetSize(): void
    {
        $uploadedFile  = new UploadedFile(file: 'test');
        $uploadedFile2 = new UploadedFile(file: 'test', size: $size = 1);

        self::assertNull($uploadedFile->getSize());
        self::assertSame($size, $uploadedFile2->getSize());
    }

    public function testGetError(): void
    {
        $uploadedFile  = new UploadedFile(file: 'test');
        $uploadedFile2 = new UploadedFile(uploadError: $error = UploadError::NO_FILE);

        self::assertSame(UploadError::OK, $uploadedFile->getError());
        self::assertSame($error, $uploadedFile2->getError());
    }

    public function testGetClientFilename(): void
    {
        $uploadedFile  = new UploadedFile(file: 'test');
        $uploadedFile2 = new UploadedFile(file: 'test', fileName: $fileName = 'test');

        self::assertNull($uploadedFile->getClientFilename());
        self::assertSame($fileName, $uploadedFile2->getClientFilename());
    }

    public function testGetClientMediaType(): void
    {
        $uploadedFile  = new UploadedFile(file: 'test');
        $uploadedFile2 = new UploadedFile(file: 'test', mediaType: $mediaType = 'txt');

        self::assertNull($uploadedFile->getClientMediaType());
        self::assertSame($mediaType, $uploadedFile2->getClientMediaType());
    }
}
