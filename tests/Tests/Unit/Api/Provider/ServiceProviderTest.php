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

namespace Valkyrja\Tests\Unit\Api\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Api\Manager\Api;
use Valkyrja\Api\Manager\Contract\ApiContract;
use Valkyrja\Api\Provider\ApiServiceProvider;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ApiServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(ApiContract::class, ApiServiceProvider::publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishApi(): void
    {
        $this->container->setSingleton(ResponseFactoryContract::class, self::createStub(ResponseFactoryContract::class));

        $callback = ApiServiceProvider::publishers()[ApiContract::class];
        $callback($this->container);

        self::assertInstanceOf(Api::class, $this->container->getSingleton(ApiContract::class));
    }
}
