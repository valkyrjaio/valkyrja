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
use Valkyrja\Tests\Unit\Abstract\TestCase;

class InMemoryMetadataTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $metadata = new InMemoryMetadata();

        self::assertNull($metadata->mimetype);
        self::assertSame(0, $metadata->size);
        self::assertNull($metadata->visibility);
    }

    public function testConstructorWithAllValues(): void
    {
        $metadata = new InMemoryMetadata('text/plain', 1024, 'public');

        self::assertSame('text/plain', $metadata->mimetype);
        self::assertSame(1024, $metadata->size);
        self::assertSame('public', $metadata->visibility);
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $metadata = new InMemoryMetadata('application/json', 512, 'private');

        $expected = [
            'mimetype'   => 'application/json',
            'size'       => 512,
            'visibility' => 'private',
        ];

        self::assertSame($expected, $metadata->toArray());
    }

    public function testToArrayWithDefaultValues(): void
    {
        $metadata = new InMemoryMetadata();

        $expected = [
            'mimetype'   => null,
            'size'       => 0,
            'visibility' => null,
        ];

        self::assertSame($expected, $metadata->toArray());
    }

    public function testPropertiesAreMutable(): void
    {
        $metadata = new InMemoryMetadata();

        $metadata->mimetype   = 'image/png';
        $metadata->size       = 2048;
        $metadata->visibility = 'public';

        self::assertSame('image/png', $metadata->mimetype);
        self::assertSame(2048, $metadata->size);
        self::assertSame('public', $metadata->visibility);
    }
}
