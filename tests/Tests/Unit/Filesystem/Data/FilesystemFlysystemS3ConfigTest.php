<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Filesystem\Data;

use Valkyrja\Filesystem\Data\Contract\FilesystemFlysystemS3ConfigContract;
use Valkyrja\Filesystem\Data\FilesystemFlysystemS3Config;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class FilesystemFlysystemS3ConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(FilesystemFlysystemS3ConfigContract::class, new FilesystemFlysystemS3Config());
    }

    public function testDefaults(): void
    {
        $config = new FilesystemFlysystemS3Config();

        self::assertSame('s3-key', $config->flysystemS3Key);
        self::assertSame('s3-secret', $config->flysystemS3Secret);
        self::assertSame('us-east-1', $config->flysystemS3Region);
        self::assertSame('latest', $config->flysystemS3Version);
        self::assertSame('s3-bucket', $config->flysystemS3Bucket);
        self::assertSame('', $config->flysystemS3Prefix);
        self::assertSame([], $config->flysystemS3Options);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new FilesystemFlysystemS3Config(
            flysystemS3Key: 'test-key',
            flysystemS3Secret: 'test-secret',
            flysystemS3Region: 'eu-west-1',
            flysystemS3Version: '2006-03-01',
            flysystemS3Bucket: 'test-bucket',
            flysystemS3Prefix: 'test:',
            flysystemS3Options: ['test' => true],
        );

        self::assertSame('test-key', $config->flysystemS3Key);
        self::assertSame('test-secret', $config->flysystemS3Secret);
        self::assertSame('eu-west-1', $config->flysystemS3Region);
        self::assertSame('2006-03-01', $config->flysystemS3Version);
        self::assertSame('test-bucket', $config->flysystemS3Bucket);
        self::assertSame('test:', $config->flysystemS3Prefix);
        self::assertSame(['test' => true], $config->flysystemS3Options);
    }
}
