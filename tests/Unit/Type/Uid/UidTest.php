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

namespace Valkyrja\Tests\Unit\Type\Uid;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Support\Uid as Helper;
use Valkyrja\Type\Types\Uid;

class UidTest extends TestCase
{
    public function testValidation(): void
    {
        $id = new Uid('abc123');

        self::assertTrue(Helper::isValid($id->asValue()));
    }
}
