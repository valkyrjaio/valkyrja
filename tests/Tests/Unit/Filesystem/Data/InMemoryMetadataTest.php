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
use Valkyrja\Filesystem\Data\InMemoryMetadata;
use Valkyrja\Filesystem\Enum\Visibility;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class InMemoryMetadataTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $metadata = new InMemoryMetadata();

        self::assertSame('', $metadata->mimetype);
        self::assertSame(0, $metadata->size);
        self::assertSame(Visibility::PUBLIC, $metadata->visibility);
    }

    public function testConstructorWithAllValues(): void
    {
        $metadata = new InMemoryMetadata('text/plain', 1024, Visibility::PUBLIC);

        self::assertSame('text/plain', $metadata->mimetype);
        self::assertSame(1024, $metadata->size);
        self::assertSame(Visibility::PUBLIC, $metadata->visibility);
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $metadata = new InMemoryMetadata('application/json', 512, Visibility::PRIVATE);

        $expected = [
            'mimetype'   => 'application/json',
            'size'       => 512,
            'visibility' => Visibility::PRIVATE->value,
        ];

        self::assertSame($expected, $metadata->toArray());
    }

    public function testToArrayWithDefaultValues(): void
    {
        $metadata = new InMemoryMetadata();

        $expected = [
            'mimetype'   => '',
            'size'       => 0,
            'visibility' => Visibility::PUBLIC->value,
        ];

        self::assertSame($expected, $metadata->toArray());
    }

    public function testPropertiesAreMutable(): void
    {
        // This pins the design decision: these stay mutable value holders.
        $property = new ReflectionProperty(InMemoryMetadata::class, 'mimetype');

        self::assertTrue($property->isPublic());
        self::assertFalse($property->isReadOnly());

        $property = new ReflectionProperty(InMemoryMetadata::class, 'size');

        self::assertTrue($property->isPublic());
        self::assertFalse($property->isReadOnly());

        $property = new ReflectionProperty(InMemoryMetadata::class, 'visibility');

        self::assertTrue($property->isPublic());
        self::assertFalse($property->isReadOnly());
    }
}
