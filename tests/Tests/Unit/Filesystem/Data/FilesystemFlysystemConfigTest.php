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

use Valkyrja\Filesystem\Data\Contract\FilesystemFlysystemConfigContract;
use Valkyrja\Filesystem\Data\FilesystemFlysystemConfig;
use Valkyrja\Filesystem\Manager\LocalFlysystemFilesystem;
use Valkyrja\Filesystem\Manager\S3FlysystemFilesystem;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class FilesystemFlysystemConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(FilesystemFlysystemConfigContract::class, new FilesystemFlysystemConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame(
            LocalFlysystemFilesystem::class,
            new FilesystemFlysystemConfig()->defaultFlysystemFilesystem
        );
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(
            S3FlysystemFilesystem::class,
            new FilesystemFlysystemConfig(defaultFlysystemFilesystem: S3FlysystemFilesystem::class)->defaultFlysystemFilesystem
        );
    }
}
