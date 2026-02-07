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

namespace Valkyrja\Tests\Unit\Cli\Routing\Attribute\Route;

use Valkyrja\Cli\Routing\Attribute\Route\Name;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class NameTest extends TestCase
{
    public function testValue(): void
    {
        $value = 'foo';
        $name  = new Name(value: $value);

        self::assertSame($value, $name->value);
    }
}
