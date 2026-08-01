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

namespace Valkyrja\Tests\Unit\Orm\Data;

use ReflectionClass;
use Valkyrja\Orm\Data\DatedMetadata;
use Valkyrja\Orm\Data\EntityMetadata;
use Valkyrja\Orm\Data\SoftDeleteMetadata;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class EntityMetadataTest extends TestCase
{
    public function testDefaultsToNoDateMetadata(): void
    {
        $metadata = new EntityMetadata();

        self::assertNull($metadata->dated);
        self::assertNull($metadata->softDelete);
    }

    public function testDatedMetadata(): void
    {
        $dated = new DatedMetadata();

        $metadata = new EntityMetadata(dated: $dated);

        self::assertSame($dated, $metadata->dated);
        self::assertNull($metadata->softDelete);
    }

    public function testSoftDeleteMetadata(): void
    {
        $softDelete = new SoftDeleteMetadata();

        $metadata = new EntityMetadata(softDelete: $softDelete);

        self::assertNull($metadata->dated);
        self::assertSame($softDelete, $metadata->softDelete);
    }

    public function testBothMetadata(): void
    {
        $dated      = new DatedMetadata();
        $softDelete = new SoftDeleteMetadata();

        $metadata = new EntityMetadata(dated: $dated, softDelete: $softDelete);

        self::assertSame($dated, $metadata->dated);
        self::assertSame($softDelete, $metadata->softDelete);
    }

    public function testReadonlyClass(): void
    {
        $reflection = new ReflectionClass(EntityMetadata::class);

        self::assertTrue($reflection->isReadOnly());
    }
}
