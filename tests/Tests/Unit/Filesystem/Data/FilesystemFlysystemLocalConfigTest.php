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
