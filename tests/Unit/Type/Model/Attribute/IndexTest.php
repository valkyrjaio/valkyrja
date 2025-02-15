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

namespace Valkyrja\Tests\Unit\Type\Model\Attribute;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Model\Attribute\Index;

class IndexTest extends TestCase
{
    public function testConstruct(): void
    {
        $value = 12;
        $data  = new Index($value);

        self::assertSame($value, $data->num);
    }
}
