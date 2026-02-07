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

use Valkyrja\Filesystem\Data\InMemoryFile;
use Valkyrja\Filesystem\Data\InMemoryMetadata;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class InMemoryFileTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $file = new InMemoryFile('test.txt');

        self::assertSame('test.txt', $file->name);
        self::assertSame('', $file->contents);
        self::assertInstanceOf(InMemoryMetadata::class, $file->metadata);
        self::assertSame(0, $file->timestamp);
    }

    public function testConstructorWithAllValues(): void
    {
        $metadata = new InMemoryMetadata('text/plain', 100, 'public');
        $file     = new InMemoryFile('test.txt', 'file contents', $metadata, 1234567890);

        self::assertSame('test.txt', $file->name);
        self::assertSame('file contents', $file->contents);
        self::assertSame($metadata, $file->metadata);
        self::assertSame(1234567890, $file->timestamp);
    }

    public function testPropertiesAreMutable(): void
    {
        $file = new InMemoryFile('test.txt');

        $file->name      = 'renamed.txt';
        $file->contents  = 'new contents';
        $file->timestamp = 9999999999;

        self::assertSame('renamed.txt', $file->name);
        self::assertSame('new contents', $file->contents);
        self::assertSame(9999999999, $file->timestamp);
    }
}
