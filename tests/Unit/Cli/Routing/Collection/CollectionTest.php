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

namespace Valkyrja\Tests\Unit\Cli\Routing\Collection;

use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Collection class.
 */
class CollectionTest extends TestCase
{
    public function testDefaults(): void
    {
        $collection = new Collection();

        self::assertEmpty($collection->all());
        self::assertEmpty($collection->getData()->commands);
        self::assertNull($collection->get('test'));
        self::assertFalse($collection->has('test'));
    }
}
