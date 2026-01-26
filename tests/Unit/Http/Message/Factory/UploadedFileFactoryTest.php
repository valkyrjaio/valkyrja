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

namespace Valkyrja\Tests\Unit\Http\Message\Factory;

use Valkyrja\Http\Message\Factory\UploadedFileFactory;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\Psr\UploadedFile as PsrUploadedFile;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class UploadedFileFactoryTest extends TestCase
{
    public function testNormalizeFilesSingleUpload(): void
    {
        $files         = [
            'avatar' => [
                'tmp_name' => 'phpUxcOty',
                'name'     => 'my-avatar.png',
                'size'     => 90996,
                'type'     => 'image/png',
                'error'    => 0,
            ],
        ];
        $uploadedFiles = UploadedFileFactory::normalizeFiles($files);

        self::assertCount(1, $uploadedFiles);
        self::assertInstanceOf(UploadedFile::class, $uploadedFiles['avatar']);
    }

    public function testNormalizeFilesSingleUploadInvalidTmpName(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $files = [
            'avatar' => [
                'tmp_name' => 0,
                'name'     => 'my-avatar.png',
                'size'     => 90996,
                'type'     => 'image/png',
                'error'    => 0,
            ],
        ];
        UploadedFileFactory::normalizeFiles($files);
    }

    public function testNormalizeFilesNestedFiles(): void
    {
        $nestedFiles = [
            'files' => [
                'tmp_name' => [
                    0 => 'phpmFLrzD',
                    1 => 'phpV2pBil',
                ],
                'name'     => [
                    0 => 'file0.txt',
                    1 => 'file1.html',
                ],
                'type'     => [
                    0 => 'text/plain',
                    1 => 'text/html',
                ],
                'error'    => [
                    0 => 0,
                    1 => 0,
                ],
            ],
        ];

        $uploadedFiles = UploadedFileFactory::normalizeFiles($nestedFiles);

        self::assertCount(1, $uploadedFiles);
        self::assertCount(2, $uploadedFiles['files']);
        self::assertInstanceOf(UploadedFile::class, $uploadedFiles['files'][0]);
        self::assertInstanceOf(UploadedFile::class, $uploadedFiles['files'][1]);
    }

    public function testNormalizeFilesUploadFilesAlready(): void
    {
        $files = [
            'file1' => new UploadedFile(file: 'test.jpg'),
            'file2' => [new UploadedFile(file: 'test.jpg')],
        ];

        $uploadedFiles = UploadedFileFactory::normalizeFiles($files);

        self::assertCount(2, $uploadedFiles);
    }

    public function testNormalizeFilesSingleDeeplyNested(): void
    {
        $nestedSingleFiles = [
            'my-form' => [
                'name'     => [
                    'details' => [
                        'avatar' => 'my-avatar.png',
                    ],
                ],
                'type'     => [
                    'details' => [
                        'avatar' => 'image/png',
                    ],
                ],
                'tmp_name' => [
                    'details' => [
                        'avatar' => 'phpmFLrzD',
                    ],
                ],
                'error'    => [
                    'details' => [
                        'avatar' => 0,
                    ],
                ],
                'size'     => [
                    'details' => [
                        'avatar' => 90996,
                    ],
                ],
            ],
        ];

        $uploadedFiles = UploadedFileFactory::normalizeFiles($nestedSingleFiles);

        self::assertCount(1, $uploadedFiles);
        self::assertCount(1, $uploadedFiles['my-form']);
        self::assertCount(1, $uploadedFiles['my-form']['details']);
        self::assertInstanceOf(UploadedFile::class, $uploadedFiles['my-form']['details']['avatar']);
    }

    public function testNormalizeFilesMultiDeeplyNested(): void
    {
        $nestedMultipleFiles = [
            'my-form' => [
                'name'     => [
                    'details' => [
                        'avatars' => [
                            0 => 'my-avatar.png',
                            1 => 'my-avatar2.png',
                            2 => 'my-avatar3.png',
                        ],
                    ],
                ],
                'type'     => [
                    'details' => [
                        'avatars' => [
                            0 => 'image/png',
                            1 => 'image/png',
                            2 => 'image/png',
                        ],
                    ],
                ],
                'tmp_name' => [
                    'details' => [
                        'avatars' => [
                            0 => 'phpmFLrzD',
                            1 => 'phpV2pBil',
                            2 => 'php8RUG8v',
                        ],
                    ],
                ],
                'error'    => [
                    'details' => [
                        'avatars' => [
                            0 => 0,
                            1 => 0,
                            2 => 0,
                        ],
                    ],
                ],
                'size'     => [
                    'details' => [
                        'avatars' => [
                            0 => 90996,
                            1 => 90996,
                            2 => 90996,
                        ],
                    ],
                ],
            ],
        ];

        $uploadedFiles = UploadedFileFactory::normalizeFiles($nestedMultipleFiles);

        self::assertCount(1, $uploadedFiles);
        self::assertCount(1, $uploadedFiles['my-form']);
        self::assertCount(1, $uploadedFiles['my-form']['details']);
        self::assertCount(3, $uploadedFiles['my-form']['details']['avatars']);
        self::assertInstanceOf(UploadedFile::class, $uploadedFiles['my-form']['details']['avatars'][0]);
        self::assertInstanceOf(UploadedFile::class, $uploadedFiles['my-form']['details']['avatars'][1]);
        self::assertInstanceOf(UploadedFile::class, $uploadedFiles['my-form']['details']['avatars'][2]);
    }

    public function testNormalizeFilesInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        UploadedFileFactory::normalizeFiles([
            [
                'test',
            ],
        ]);
    }

    public function testNormalizeNestedFileSpecInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        UploadedFileFactory::normalizeNestedFileSpec([
            [
                'test',
            ],
        ]);
    }

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

        $uploadedFileFromFactory = UploadedFileFactory::fromPsr($psrUploadedFile);

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

        [
            $uploadedFileFromFactory,
            $uploadedFileFromFactory2,
        ] = UploadedFileFactory::fromPsrArray($psrUploadedFile, $psrUploadedFile2);

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
}
