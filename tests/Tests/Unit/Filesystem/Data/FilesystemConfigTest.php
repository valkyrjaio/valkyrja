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
