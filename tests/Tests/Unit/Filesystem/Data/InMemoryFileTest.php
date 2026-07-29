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

use ReflectionProperty;
use Valkyrja\Filesystem\Data\InMemoryFile;
use Valkyrja\Filesystem\Data\InMemoryMetadata;
use Valkyrja\Filesystem\Enum\Visibility;
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
        $metadata = new InMemoryMetadata('text/plain', 100, Visibility::PUBLIC);
        $file     = new InMemoryFile('test.txt', 'file contents', $metadata, 1234567890);

        self::assertSame('test.txt', $file->name);
        self::assertSame('file contents', $file->contents);
        self::assertSame($metadata, $file->metadata);
        self::assertSame(1234567890, $file->timestamp);
    }

    public function testPropertiesAreMutable(): void
    {
        // Assigning a public property and reading it back only exercises PHP. What
        // this pins is the design decision: these stay mutable value holders.
        $property = new ReflectionProperty(InMemoryFile::class, 'name');

        self::assertTrue($property->isPublic());
        self::assertFalse($property->isReadOnly());

        $property = new ReflectionProperty(InMemoryFile::class, 'contents');

        self::assertTrue($property->isPublic());
        self::assertFalse($property->isReadOnly());

        $property = new ReflectionProperty(InMemoryFile::class, 'timestamp');

        self::assertTrue($property->isPublic());
        self::assertFalse($property->isReadOnly());
    }
}
