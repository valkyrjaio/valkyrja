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

use Valkyrja\Filesystem\Data\Contract\FilesystemFlysystemLocalConfigContract;
use Valkyrja\Filesystem\Data\FilesystemFlysystemLocalConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class FilesystemFlysystemLocalConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(
            FilesystemFlysystemLocalConfigContract::class,
            new FilesystemFlysystemLocalConfig()
        );
    }

    public function testDefaults(): void
    {
        self::assertSame('/storage/app', new FilesystemFlysystemLocalConfig()->flysystemLocalPath);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(
            '/storage/test',
            new FilesystemFlysystemLocalConfig(flysystemLocalPath: '/storage/test')->flysystemLocalPath
        );
    }
}
