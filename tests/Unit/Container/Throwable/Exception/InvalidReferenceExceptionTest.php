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

namespace Valkyrja\Tests\Unit\Container\Throwable\Exception;

use Valkyrja\Container\Throwable\Exception\InvalidReferenceException;
use Valkyrja\Tests\Unit\TestCase;

class InvalidReferenceExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $id = self::class;

        $exception = new InvalidReferenceException($id);

        self::assertSame("Service with `$id` not found", $exception->getMessage());
    }
}
