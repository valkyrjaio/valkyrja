<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Type;

use Valkyrja\Type\Exceptions\InvalidUlidException;
use Valkyrja\Type\Ulid;
use Valkyrja\Tests\Unit\TestCase;

class UlidTest extends TestCase
{
    public function testGenerate(): void
    {
        $this->assertTrue(Ulid::isValid(Ulid::generate()));
        $this->assertTrue(Ulid::isValid(Ulid::generateLowerCase()));
    }

    public function testNotValidException(): void
    {
        $ulid = 'test';

        $this->expectException(InvalidUlidException::class);
        $this->expectExceptionMessage("Invalid ULID $ulid provided.");

        Ulid::validate($ulid);
    }
}
