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

namespace Valkyrja\Tests\Unit\Http\Message\Provider;

use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as ResponseFactoryContract;
use Valkyrja\Http\Message\Factory\ResponseFactory;
use Valkyrja\Http\Message\Provider\ServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testPublishResponseFactory(): void
    {
        ServiceProvider::publishResponseFactory($this->container);

        self::assertInstanceOf(ResponseFactory::class, $this->container->getSingleton(ResponseFactoryContract::class));
    }
}
