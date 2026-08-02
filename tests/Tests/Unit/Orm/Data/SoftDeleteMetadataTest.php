<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Data;

use ReflectionClass;
use Valkyrja\Orm\Constant\DateFormat;
use Valkyrja\Orm\Data\SoftDeleteMetadata;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SoftDeleteMetadataTest extends TestCase
{
    public function testDefaultFormat(): void
    {
        $metadata = new SoftDeleteMetadata();

        self::assertSame(DateFormat::DEFAULT, $metadata->format);
    }

    public function testDefaultField(): void
    {
        $metadata = new SoftDeleteMetadata();

        self::assertSame('deleted_at', $metadata->dateDeletedField);
    }

    public function testCustomFormat(): void
    {
        $metadata = new SoftDeleteMetadata(format: DateFormat::MILLISECOND);

        self::assertSame(DateFormat::MILLISECOND, $metadata->format);
    }

    public function testCustomField(): void
    {
        $metadata = new SoftDeleteMetadata(dateDeletedField: 'date_deleted');

        self::assertSame('date_deleted', $metadata->dateDeletedField);
    }

    public function testReadonlyClass(): void
    {
        $reflection = new ReflectionClass(SoftDeleteMetadata::class);

        self::assertTrue($reflection->isReadOnly());
    }
}
