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

use Valkyrja\Http\Message\File\Factory\UploadedFileFactory;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class UploadedFileFactoryTest extends TestCase
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

        self::assertCount(1, $uploadedFiles->getAll());
        self::assertInstanceOf(UploadedFile::class, $uploadedFiles->get('avatar'));
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

        self::assertCount(1, $uploadedFiles->getAll());
        self::assertCount(2, $uploadedFiles->get('files')->getAll());
        self::assertInstanceOf(UploadedFile::class, $uploadedFiles->get('files')->get(0));
        self::assertInstanceOf(UploadedFile::class, $uploadedFiles->get('files')->get(1));
    }

    public function testNormalizeFilesUploadFilesAlready(): void
    {
        $files = [
            'file1' => new UploadedFile(file: 'test.jpg'),
            'file2' => [new UploadedFile(file: 'test.jpg')],
        ];

        $uploadedFiles = UploadedFileFactory::normalizeFiles($files);

        self::assertCount(2, $uploadedFiles->getAll());
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

        self::assertCount(1, $uploadedFiles->getAll());
        self::assertCount(1, $uploadedFiles->get('my-form')->getAll());
        self::assertCount(1, $uploadedFiles->get('my-form')->get('details')->getAll());
        self::assertInstanceOf(UploadedFile::class, $uploadedFiles->get('my-form')->get('details')->get('avatar'));
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

        self::assertCount(1, $uploadedFiles->getAll());
        self::assertCount(1, $uploadedFiles->get('my-form')->getAll());
        self::assertCount(1, $uploadedFiles->get('my-form')->get('details')->getAll());
        self::assertCount(3, $uploadedFiles->get('my-form')->get('details')->get('avatars')->getAll());

        $avatars = $uploadedFiles->get('my-form')->get('details')->get('avatars');

        self::assertInstanceOf(UploadedFile::class, $avatars->get(0));
        self::assertInstanceOf(UploadedFile::class, $avatars->get(1));
        self::assertInstanceOf(UploadedFile::class, $avatars->get(2));
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
}
