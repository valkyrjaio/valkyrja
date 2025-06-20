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

namespace Valkyrja\Tests\Unit\Config\Support;

use Valkyrja\Application\Config\Valkyrja;
use Valkyrja\Tests\Classes\Config\ProviderClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Provider support class.
 *
 * @author Melech Mizrachi
 */
class ProviderTest extends TestCase
{
    /**
     * Test the service provider.
     *
     * @return void
     */
    public function testServiceProvider(): void
    {
        /** @var Valkyrja $config */
        $config = $this->createMock(Valkyrja::class);

        self::assertNull(ProviderClass::publish($config) ?? null);
    }
}
