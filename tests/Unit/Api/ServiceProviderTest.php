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

namespace Valkyrja\Tests\Unit\Api;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Api\Api as Contract;
use Valkyrja\Api\Apis\Api;
use Valkyrja\Api\Providers\ServiceProvider;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Tests\Unit\Container\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    /**
     * @throws Exception
     */
    public function testPublishApi(): void
    {
        $this->container->setSingleton(ResponseFactory::class, $this->createMock(ResponseFactory::class));

        ServiceProvider::publishApi($this->container);

        self::assertInstanceOf(Api::class, $this->container->getSingleton(Contract::class));
    }
}
