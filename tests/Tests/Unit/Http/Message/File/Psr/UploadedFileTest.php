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

namespace Valkyrja\Tests\Unit\Http\Message\File\Psr;

use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\Psr\UploadedFile as PsrUploadedFile;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Support\Directory\Directory;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function unlink;

final class UploadedFileTest extends TestCase
{
    public function testStream(): void
    {
        $stream = new Stream();
        $stream->write($contents = 'test');

        $uploadedFile    = new UploadedFile(
            stream: $stream,
            uploadError: $error   = UploadError::OK,
            size: $size           = 1,
            fileName: $fileName   = 'test',
            mediaType: $mediaType = 'txt',
        );
        $psrUploadedFile = new PsrUploadedFile($uploadedFile);
        $psrUploadedFile->getStream()->rewind();

        self::assertSame($contents, $psrUploadedFile->getStream()->getContents());
        self::assertSame($size, $psrUploadedFile->getSize());
        self::assertSame($fileName, $psrUploadedFile->getClientFilename());
        self::assertSame($mediaType, $psrUploadedFile->getClientMediaType());
        self::assertSame($error->value, $psrUploadedFile->getError());
    }

    public function testMoveTo(): void
    {
        Directory::$basePath = EnvClass::APP_DIR;

        $file  = Directory::storagePath('/PsrUploadedFileTest-testMoveTo.txt');
        $file2 = Directory::storagePath('/PsrUploadedFileTest-testMoveTo2.txt');

        $stream = new Stream();
        $stream->write('test');

        $uploadedFile = new PsrUploadedFile(new UploadedFile(stream: $stream));
        $psrStream    = $uploadedFile->getStream();
        $uploadedFile->moveTo($file);

        // Ensure the stream was closed
        self::assertFalse($psrStream->isReadable());
        self::assertFalse($psrStream->isWritable());
        self::assertNull($psrStream->getMetadata());
        // Ensure new file was created
        self::assertFileExists($file);

        $uploadedFile2 = new PsrUploadedFile(new UploadedFile(file: $file));
        $uploadedFile2->moveTo($file2);

        // Ensure previous file was deleted
        self::assertFileDoesNotExist($file);
        // Ensure new file was created
        self::assertFileExists($file2);

        unlink($file2);
    }
}
