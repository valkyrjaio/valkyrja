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

use Valkyrja\Http\Message\Provider\HttpMessageServiceProvider;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Message\Response\Factory\ResponseFactory;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = HttpMessageServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(ResponseFactoryContract::class, (new HttpMessageServiceProvider())->publishers());
    }

    public function testPublishResponseFactory(): void
    {
        $callback = (new HttpMessageServiceProvider())->publishers()[ResponseFactoryContract::class];
        $callback($this->container);

        self::assertInstanceOf(ResponseFactory::class, $this->container->getSingleton(ResponseFactoryContract::class));
    }
}
