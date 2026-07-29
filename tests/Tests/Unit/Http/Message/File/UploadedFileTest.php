<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\File;

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileAlreadyMovedException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileInvalidDirectoryException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileInvalidUploadedFileException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileMoveFailureException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileUnableToWriteFileException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileUploadErrorException;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageInvalidArgumentException;
use Valkyrja\Tests\Fixtures\Http\Message\File\InvalidDirectoryExceptionFixture;
use Valkyrja\Tests\Fixtures\Http\Message\File\InvalidUploadedFileExceptionFixture;
use Valkyrja\Tests\Fixtures\Http\Message\File\MoveFailureExceptionFixture;
use Valkyrja\Tests\Fixtures\Http\Message\File\MoveUploadedFileFixture;
use Valkyrja\Tests\Fixtures\Http\Message\File\UnableToWriteFileExceptionFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function unlink;

final class UploadedFileTest extends TestCase
{
    /**
     * @return array<int, array{UploadError}>
     */
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
        $this->expectException(HttpMessageInvalidArgumentException::class);

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
        $this->expectException(UploadedFileUploadErrorException::class);

        $stream = new Stream();
        $stream->write('test');

        $uploadedFile = new UploadedFile(stream: $stream, uploadError: $error);
        $uploadedFile->getStream();
    }

    public function testSubsequentMoveGetStreamException(): void
    {
        $this->expectException(UploadedFileAlreadyMovedException::class);

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
        $this->expectException(UploadedFileInvalidUploadedFileException::class);

        $uploadedFile = new InvalidUploadedFileExceptionFixture();
        $uploadedFile->getStream();
    }

    #[DataProvider('invalidUploadErrorsProvider')]
    public function testMoveUploadErrorException(UploadError $error): void
    {
        $this->expectException(UploadedFileUploadErrorException::class);

        $stream = new Stream();
        $stream->write('test');

        $uploadedFile = new UploadedFile(stream: $stream, uploadError: $error);
        $uploadedFile->moveTo(Directory::storagePath('/UploadedFileTest-testMoveUploadErrorException.txt'));
    }

    public function testSubsequentMoveException(): void
    {
        $this->expectException(UploadedFileAlreadyMovedException::class);

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
        self::assertEmpty($stream->getMetadata());
        // Ensure new file was created
        self::assertFileExists($file);

        $uploadedFile2 = new UploadedFile(file: $file);
        $uploadedFile2->moveTo($file2);

        // Ensure previous file was deleted
        self::assertFileDoesNotExist($file);
        // Ensure new file was created
        self::assertFileExists($file2);

        $uploadedFile3 = new MoveUploadedFileFixture(file: $file2);
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
        $this->expectException(UploadedFileMoveFailureException::class);

        $file = Directory::storagePath('/uploadedFileTest-testMoveFailureException.txt');

        $stream = new Stream();
        $stream->write('test');

        $uploadedFile = new UploadedFile(stream: $stream);
        $uploadedFile->moveTo($file);

        // Should fail since this is not a valid uploaded file
        $uploadedFile2 = new MoveFailureExceptionFixture(file: $file);
        $uploadedFile2->moveTo(Directory::storagePath('/uploadedFileTest-testMoveFailureException2.txt'));
    }

    public function testInvalidDirectoryException(): void
    {
        $this->expectException(UploadedFileInvalidDirectoryException::class);

        // Should fail since this is not a valid uploaded file
        $uploadedFile2 = new InvalidDirectoryExceptionFixture(file: Directory::storagePath('/uploadedFileTest-testInvalidDirectoryException.txt'));
        $uploadedFile2->moveTo(Directory::storagePath('/uploadedFileTest-testInvalidDirectoryException2.txt'));
    }

    public function testUnableToWriteFileException(): void
    {
        $this->expectException(UploadedFileUnableToWriteFileException::class);

        // Should fail since this is not a valid uploaded file
        $uploadedFile2 = new UnableToWriteFileExceptionFixture(file: Directory::storagePath('/uploadedFileTest-testUnableToWriteFileException.txt'));
        $uploadedFile2->moveTo(Directory::storagePath('/uploadedFileTest-testUnableToWriteFileException2.txt'));
    }

    public function testGetSize(): void
    {
        $uploadedFile  = new UploadedFile(file: Directory::storagePath('/uploadedFileTest-testGetSize.txt'));
        $uploadedFile2 = new UploadedFile(file: Directory::storagePath('/uploadedFileTest-testGetSize.txt'), size: $size = 1);

        self::assertFalse($uploadedFile->hasSize());
        self::assertTrue($uploadedFile2->hasSize());
        self::assertSame($size, $uploadedFile2->getSize());
    }

    public function testGetSizeReturnsEmptyStringWhenNonExistent(): void
    {
        $uploadedFile = new UploadedFile(file: Directory::storagePath('/uploadedFileTest-testGetSizeReturnsEmptyStringWhenNonExistent.txt'));

        self::assertSame(0, $uploadedFile->getSize());
    }

    public function testGetError(): void
    {
        $uploadedFile  = new UploadedFile(file: Directory::storagePath('/uploadedFileTest-testGetError.txt'));
        $uploadedFile2 = new UploadedFile(uploadError: $error = UploadError::NO_FILE);

        self::assertSame(UploadError::OK, $uploadedFile->getError());
        self::assertSame($error, $uploadedFile2->getError());
    }

    public function testGetClientFilename(): void
    {
        $uploadedFile  = new UploadedFile(file: Directory::storagePath('/uploadedFileTest-testGetClientFilename.txt'));
        $uploadedFile2 = new UploadedFile(file: Directory::storagePath('/uploadedFileTest-testGetClientFilename.txt'), fileName: $fileName = 'test');

        self::assertFalse($uploadedFile->hasClientFilename());
        self::assertTrue($uploadedFile2->hasClientFilename());
        self::assertSame($fileName, $uploadedFile2->getClientFilename());
    }

    public function testGetClientFilenameReturnsEmptyStringWhenNonExistent(): void
    {
        $uploadedFile = new UploadedFile(file: Directory::storagePath('/uploadedFileTest-testGetClientFilenameReturnsEmptyStringWhenNonExistent.txt'));

        self::assertSame('', $uploadedFile->getClientFilename());
    }

    public function testGetClientMediaType(): void
    {
        $uploadedFile  = new UploadedFile(file: Directory::storagePath('/uploadedFileTest-testGetClientMediaType.txt'));
        $uploadedFile2 = new UploadedFile(file: Directory::storagePath('/uploadedFileTest-testGetClientMediaType.txt'), mediaType: $mediaType = 'txt');

        self::assertFalse($uploadedFile->hasClientMediaType());
        self::assertTrue($uploadedFile2->hasClientMediaType());
        self::assertSame($mediaType, $uploadedFile2->getClientMediaType());
    }

    public function testGetClientMediaTypeReturnsEmptyStringWhenNonExistent(): void
    {
        $uploadedFile = new UploadedFile(file: Directory::storagePath('/uploadedFileTest-testGetClientMediaTypeReturnsEmptyStringWhenNonExistent.txt'));

        self::assertSame('', $uploadedFile->getClientMediaType());
    }
}
