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

namespace Valkyrja\Tests\Unit\Type\Ulid;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Support\Ulid as Helper;
use Valkyrja\Type\Types\Ulid;

class UlidTest extends TestCase
{
    public function testGeneration(): void
    {
        $id = new Ulid();

        self::assertTrue(Helper::isValid($id->asValue()));
    }
}
