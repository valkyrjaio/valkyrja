<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Registry;

use Valkyrja\Orm\Data\DatedMetadata;
use Valkyrja\Orm\Data\EntityMetadata;
use Valkyrja\Orm\Registry\Contract\EntityMetadataRegistryContract;
use Valkyrja\Orm\Registry\EntityMetadataRegistry;
use Valkyrja\Orm\Throwable\Exception\OrmUnregisteredEntityException;
use Valkyrja\Tests\Fixtures\Orm\Entity\EntityIntIdFixture;
use Valkyrja\Tests\Fixtures\Orm\Entity\EntityStringIdFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class EntityMetadataRegistryTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(EntityMetadataRegistryContract::class, new EntityMetadataRegistry());
    }

    public function testEmptyRegistryHasNoEntity(): void
    {
        $registry = new EntityMetadataRegistry();

        self::assertFalse($registry->has(EntityIntIdFixture::class));
    }

    public function testConstructorTakesMetadata(): void
    {
        $metadata = new EntityMetadata();

        $registry = new EntityMetadataRegistry([EntityIntIdFixture::class => $metadata]);

        self::assertTrue($registry->has(EntityIntIdFixture::class));
        self::assertSame($metadata, $registry->get(EntityIntIdFixture::class));
    }

    public function testWithEntityAddsMetadata(): void
    {
        $metadata = new EntityMetadata(dated: new DatedMetadata());

        $registry = new EntityMetadataRegistry()
            ->withEntity(EntityIntIdFixture::class, $metadata);

        self::assertTrue($registry->has(EntityIntIdFixture::class));
        self::assertSame($metadata, $registry->get(EntityIntIdFixture::class));
    }

    public function testWithEntityDoesNotModifyTheOriginal(): void
    {
        $original = new EntityMetadataRegistry();

        $new = $original->withEntity(EntityIntIdFixture::class, new EntityMetadata());

        self::assertNotSame($original, $new);
        self::assertFalse($original->has(EntityIntIdFixture::class));
        self::assertTrue($new->has(EntityIntIdFixture::class));
    }

    public function testWithEntityKeepsThePreviousEntity(): void
    {
        $first  = new EntityMetadata(dated: new DatedMetadata());
        $second = new EntityMetadata();

        $registry = new EntityMetadataRegistry()
            ->withEntity(EntityIntIdFixture::class, $first)
            ->withEntity(EntityStringIdFixture::class, $second);

        self::assertSame($first, $registry->get(EntityIntIdFixture::class));
        self::assertSame($second, $registry->get(EntityStringIdFixture::class));
    }

    public function testWithEntityReplacesTheSameEntity(): void
    {
        $first  = new EntityMetadata();
        $second = new EntityMetadata(dated: new DatedMetadata());

        $registry = new EntityMetadataRegistry()
            ->withEntity(EntityIntIdFixture::class, $first)
            ->withEntity(EntityIntIdFixture::class, $second);

        self::assertSame($second, $registry->get(EntityIntIdFixture::class));
    }

    public function testGetThrowsForAnUnregisteredEntity(): void
    {
        $registry = new EntityMetadataRegistry();

        $this->expectException(OrmUnregisteredEntityException::class);
        $this->expectExceptionMessage(
            'Entity ' . EntityIntIdFixture::class . ' has no registered metadata.'
            . ' Register the entity in a service provider.'
        );

        $registry->get(EntityIntIdFixture::class);
    }
}
