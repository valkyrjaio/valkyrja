<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Filesystem\Manager;

use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\FlysystemFilesystem;
use Valkyrja\Filesystem\Manager\S3FlysystemFilesystem;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class S3FlysystemFilesystemTest extends TestCase
{
    protected MockObject&FilesystemOperator $flysystem;

    protected S3FlysystemFilesystem $filesystem;

    protected function setUp(): void
    {
        $this->flysystem  = $this->createMock(FilesystemOperator::class);
        $this->filesystem = new S3FlysystemFilesystem($this->flysystem);
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
