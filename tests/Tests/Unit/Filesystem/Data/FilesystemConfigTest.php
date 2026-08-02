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

use Valkyrja\Filesystem\Data\Contract\FilesystemConfigContract;
use Valkyrja\Filesystem\Data\FilesystemConfig;
use Valkyrja\Filesystem\Manager\FlysystemFilesystem;
use Valkyrja\Filesystem\Manager\InMemoryFilesystem;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class FilesystemConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(FilesystemConfigContract::class, new FilesystemConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame(FlysystemFilesystem::class, new FilesystemConfig()->defaultFilesystem);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(
            InMemoryFilesystem::class,
            new FilesystemConfig(defaultFilesystem: InMemoryFilesystem::class)->defaultFilesystem
        );
    }
}
