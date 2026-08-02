<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Filesystem\Data;

use Valkyrja\Filesystem\Data\Contract\FilesystemFlysystemS3ConfigContract;

class FilesystemFlysystemS3Config implements FilesystemFlysystemS3ConfigContract
{
    /**
     * @param non-empty-string        $flysystemS3Key     The S3 access key
     * @param non-empty-string        $flysystemS3Secret  The S3 secret key
     * @param non-empty-string        $flysystemS3Region  The S3 region
     * @param non-empty-string        $flysystemS3Version The S3 api version
     * @param non-empty-string        $flysystemS3Bucket  The S3 bucket to store files in
     * @param string                  $flysystemS3Prefix  The prefix to prepend to every path
     * @param array<array-key, mixed> $flysystemS3Options The options to pass to the adapter
     */
    public function __construct(
        public readonly string $flysystemS3Key = 's3-key',
        public readonly string $flysystemS3Secret = 's3-secret',
        public readonly string $flysystemS3Region = 'us-east-1',
        public readonly string $flysystemS3Version = 'latest',
        public readonly string $flysystemS3Bucket = 's3-bucket',
        public readonly string $flysystemS3Prefix = '',
        public readonly array $flysystemS3Options = [],
    ) {
    }
}
