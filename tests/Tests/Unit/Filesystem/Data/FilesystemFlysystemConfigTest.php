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
