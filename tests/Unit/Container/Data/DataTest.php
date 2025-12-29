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

namespace Valkyrja\Tests\Unit\Container\Data;

use Valkyrja\Container\Data\Data;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Data service.
 *
 * @author Melech Mizrachi
 */
class DataTest extends TestCase
{
    public function testDefault(): void
    {
        $data = new Data();

        self::assertEmpty($data->aliases);
        self::assertEmpty($data->deferred);
        self::assertEmpty($data->deferredCallback);
        self::assertEmpty($data->services);
        self::assertEmpty($data->singletons);
        self::assertEmpty($data->providers);
    }
}
