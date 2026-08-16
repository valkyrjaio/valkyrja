<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Filesystem\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Filesystem\Data\Contract\FilesystemConfigContract;
use Valkyrja\Filesystem\Data\Contract\FilesystemFlysystemConfigContract;
use Valkyrja\Filesystem\Data\Contract\FilesystemFlysystemLocalConfigContract;
use Valkyrja\Filesystem\Data\Contract\FilesystemFlysystemS3ConfigContract;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\FlysystemFilesystem;
use Valkyrja\Filesystem\Manager\InMemoryFilesystem;
use Valkyrja\Filesystem\Manager\S3FlysystemFilesystem;

/**
 * An application config that implements every filesystem contract at once.
 */
final class FilesystemConfigFixture extends Config implements FilesystemConfigContract, FilesystemFlysystemConfigContract, FilesystemFlysystemLocalConfigContract, FilesystemFlysystemS3ConfigContract
{
    /**
     * @param class-string<FilesystemContract>  $defaultFilesystem
     * @param class-string<FlysystemFilesystem> $defaultFlysystemFilesystem
     * @param non-empty-string                  $flysystemLocalPath
     * @param non-empty-string                  $flysystemS3Key
     * @param non-empty-string                  $flysystemS3Secret
     * @param non-empty-string                  $flysystemS3Region
     * @param non-empty-string                  $flysystemS3Version
     * @param non-empty-string                  $flysystemS3Bucket
     * @param array<array-key, mixed>           $flysystemS3Options
     */
    public function __construct(
        public string $defaultFilesystem = InMemoryFilesystem::class,
        public string $defaultFlysystemFilesystem = S3FlysystemFilesystem::class,
        public string $flysystemLocalPath = '/storage/test',
        public string $flysystemS3Key = 'test-key',
        public string $flysystemS3Secret = 'test-secret',
        public string $flysystemS3Region = 'eu-west-1',
        public string $flysystemS3Version = '2006-03-01',
        public string $flysystemS3Bucket = 'test-bucket',
        public string $flysystemS3Prefix = 'test:',
        public array $flysystemS3Options = ['test' => true],
    ) {
        parent::__construct();
    }
}
