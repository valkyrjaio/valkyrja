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

namespace Valkyrja\Tests\Unit\Cli\Routing;

use Valkyrja\Cli\Routing\Data;
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

        self::assertEmpty($data->commands);
    }
}
