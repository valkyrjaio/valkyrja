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

namespace Valkyrja\Tests\Unit\Type\BuiltIn\String;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\String\NonEmptyString;
use Valkyrja\Type\Exception\InvalidArgumentException;

class NonEmptyStringTest extends TestCase
{
    public function testEmptyArrayThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new NonEmptyString('');
    }

    public function testConstruct(): void
    {
        $value = 'foo';
        $type  = new NonEmptyString($value);

        self::assertSame($value, $type->asValue());
    }
}
