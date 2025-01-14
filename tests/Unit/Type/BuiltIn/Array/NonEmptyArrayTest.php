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

namespace Valkyrja\Tests\Unit\Type\BuiltIn\Array;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\Array\NonEmptyArray;
use Valkyrja\Type\Exception\InvalidArgumentException;

class NonEmptyArrayTest extends TestCase
{
    public function testEmptyArrayThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new NonEmptyArray([]);
    }

    public function testConstruct(): void
    {
        $value = ['test' => 'foo'];
        $type  = new NonEmptyArray($value);

        self::assertSame($value, $type->asValue());
    }
}
