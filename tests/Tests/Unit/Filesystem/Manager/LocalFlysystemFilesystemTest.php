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

namespace Valkyrja\Tests\Unit\Filesystem\Manager;

use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\FlysystemFilesystem;
use Valkyrja\Filesystem\Manager\LocalFlysystemFilesystem;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class LocalFlysystemFilesystemTest extends TestCase
{
    protected MockObject&FilesystemOperator $flysystem;

    protected LocalFlysystemFilesystem $filesystem;

    protected function setUp(): void
    {
        $this->flysystem  = $this->createMock(FilesystemOperator::class);
        $this->filesystem = new LocalFlysystemFilesystem($this->flysystem);
    }

    public function testInstanceOfContract(): void
    {
        $this->flysystem->expects($this->never())->method('has');

        self::assertInstanceOf(FilesystemContract::class, $this->filesystem);
    }

    public function testExtendsFlysystemFilesystem(): void
    {
        $this->flysystem->expects($this->never())->method('has');

        self::assertInstanceOf(FlysystemFilesystem::class, $this->filesystem);
    }

    public function testGetFlysystemReturnsOperator(): void
    {
        $this->flysystem->expects($this->never())->method('has');

        self::assertSame($this->flysystem, $this->filesystem->getFlysystem());
    }
}
