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
        $metadata = new InMemoryMetadata();

        $metadata->mimetype   = 'image/png';
        $metadata->size       = 2048;
        $metadata->visibility = Visibility::PUBLIC;

        self::assertSame('image/png', $metadata->mimetype);
        self::assertSame(2048, $metadata->size);
        self::assertSame(Visibility::PUBLIC, $metadata->visibility);
    }
}
