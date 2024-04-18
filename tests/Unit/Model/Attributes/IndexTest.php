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

namespace Valkyrja\Tests\Unit\Model\Attributes;

use Valkyrja\Model\Attributes\Index;
use Valkyrja\Tests\Unit\TestCase;

class IndexTest extends TestCase
{
    public function testConstruct(): void
    {
        $value = 12;
        $data  = new Index($value);

        self::assertSame($value, $data->num);
    }
}
