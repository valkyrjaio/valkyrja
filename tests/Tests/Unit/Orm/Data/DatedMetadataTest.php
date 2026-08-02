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
use Valkyrja\Orm\Constant\DateFormat;
use Valkyrja\Orm\Data\DatedMetadata;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class DatedMetadataTest extends TestCase
{
    public function testDefaultFormat(): void
    {
        $metadata = new DatedMetadata();

        self::assertSame(DateFormat::DEFAULT, $metadata->format);
    }

    public function testDefaultFields(): void
    {
        $metadata = new DatedMetadata();

        self::assertSame('created_at', $metadata->dateCreatedField);
        self::assertSame('updated_at', $metadata->dateModifiedField);
    }

    public function testCustomFormat(): void
    {
        $metadata = new DatedMetadata(format: DateFormat::MICROSECOND);

        self::assertSame(DateFormat::MICROSECOND, $metadata->format);
    }

    public function testCustomFields(): void
    {
        $metadata = new DatedMetadata(
            dateCreatedField: 'date_created',
            dateModifiedField: 'date_modified'
        );

        self::assertSame('date_created', $metadata->dateCreatedField);
        self::assertSame('date_modified', $metadata->dateModifiedField);
    }

    public function testReadonlyClass(): void
    {
        $reflection = new ReflectionClass(DatedMetadata::class);

        self::assertTrue($reflection->isReadOnly());
    }
}
