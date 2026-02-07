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

namespace Valkyrja\Tests\Unit\Http\Routing\Attribute\Route\RequestMethod;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod\Delete;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Delete attribute.
 */
final class DeleteTest extends TestCase
{
    public function testDefaults(): void
    {
        $value = [
            RequestMethod::DELETE,
        ];

        $route = new Delete();

        self::assertSame($value, $route->requestMethods);
    }
}
