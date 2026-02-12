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

namespace Valkyrja\Tests\Unit\Http\Message\File\Factory;

use Valkyrja\Http\Message\File\Collection\Contract\UploadedFileCollectionContract;
use Valkyrja\Http\Message\File\Collection\UploadedFileCollection;
use Valkyrja\Http\Message\File\Contract\UploadedFileContract;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\Factory\PsrUploadedFileFactory;
use Valkyrja\Http\Message\File\Psr\UploadedFile as PsrUploadedFile;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class PsrUploadedFileFactoryTest extends TestCase
{
    public function testFromPsr(): void
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

        $uploadedFileFromFactory = PsrUploadedFileFactory::fromPsr($psrUploadedFile);

        self::assertSame($contents, $uploadedFileFromFactory->getStream()->getContents());
        self::assertSame($size, $uploadedFileFromFactory->getSize());
        self::assertSame($error, $uploadedFileFromFactory->getError());
        self::assertSame($fileName, $uploadedFileFromFactory->getClientFilename());
        self::assertSame($mediaType, $uploadedFileFromFactory->getClientMediaType());
    }

    public function testFromPsrArray(): void
    {
        $stream = new Stream();
        $stream->write($contents = 'test');

        $stream2 = new Stream();
        $stream2->write($contents2 = 'test');

        $uploadedFile    = new UploadedFile(
            stream: $stream,
            uploadError: $error   = UploadError::OK,
            size: $size           = 1,
            fileName: $fileName   = 'test',
            mediaType: $mediaType = 'txt',
        );
        $psrUploadedFile = new PsrUploadedFile($uploadedFile);

        $uploadedFile2    = new UploadedFile(
            stream: $stream2,
            uploadError: $error2   = UploadError::OK,
            size: $size2           = 1,
            fileName: $fileName2   = 'test',
            mediaType: $mediaType2 = 'txt',
        );
        $psrUploadedFile2 = new PsrUploadedFile($uploadedFile2);

        $fromPsrArray = PsrUploadedFileFactory::fromPsrArray([$psrUploadedFile, $psrUploadedFile2]);

        $uploadedFileFromFactory  = $fromPsrArray->getFile(0);
        $uploadedFileFromFactory2 = $fromPsrArray->getFile(1);

        self::assertSame($contents, $uploadedFileFromFactory->getStream()->getContents());
        self::assertSame($size, $uploadedFileFromFactory->getSize());
        self::assertSame($error, $uploadedFileFromFactory->getError());
        self::assertSame($fileName, $uploadedFileFromFactory->getClientFilename());
        self::assertSame($mediaType, $uploadedFileFromFactory->getClientMediaType());

        self::assertSame($contents2, $uploadedFileFromFactory2->getStream()->getContents());
        self::assertSame($size2, $uploadedFileFromFactory2->getSize());
        self::assertSame($error2, $uploadedFileFromFactory2->getError());
        self::assertSame($fileName2, $uploadedFileFromFactory2->getClientFilename());
        self::assertSame($mediaType2, $uploadedFileFromFactory2->getClientMediaType());
    }

    public function testFromPsrArrayWithNestedArray(): void
    {
        $stream = new Stream();
        $stream->write($contents = 'nested-test');

        $uploadedFile    = new UploadedFile(
            stream: $stream,
            uploadError: UploadError::OK,
            size: 1,
            fileName: 'nested.txt',
            mediaType: 'txt',
        );
        $psrUploadedFile = new PsrUploadedFile($uploadedFile);

        $fromPsrArray = PsrUploadedFileFactory::fromPsrArray([[$psrUploadedFile]]);

        $nestedCollection = $fromPsrArray->getFile(0);

        self::assertInstanceOf(UploadedFileCollectionContract::class, $nestedCollection);

        $nestedFile = $nestedCollection->getFile(0);

        self::assertInstanceOf(UploadedFileContract::class, $nestedFile);
        self::assertSame($contents, $nestedFile->getStream()->getContents());
    }

    public function testToPsrArrayWithNestedCollection(): void
    {
        $stream = new Stream();
        $stream->write($contents = 'inner-test');
        $stream->rewind();

        $innerFile       = new UploadedFile(
            stream: $stream,
            uploadError: UploadError::OK,
            size: 1,
            fileName: 'inner.txt',
            mediaType: 'txt',
        );
        $innerCollection = new UploadedFileCollection([$innerFile]);
        $outerCollection = new UploadedFileCollection([$innerCollection]);

        $result = PsrUploadedFileFactory::toPsrArray($outerCollection);

        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertIsArray($result[0]);
        self::assertCount(1, $result[0]);
        self::assertInstanceOf(PsrUploadedFile::class, $result[0][0]);
        self::assertSame($contents, $result[0][0]->getStream()->getContents());
    }
}
