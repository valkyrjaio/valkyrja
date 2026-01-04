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

namespace Valkyrja\Tests\Unit\Http\Client\Provider;

use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Http\Client\Manager\Contract\ClientContract;
use Valkyrja\Http\Client\Manager\GuzzleClient;
use Valkyrja\Http\Client\Manager\LogClient;
use Valkyrja\Http\Client\Manager\NullClient;
use Valkyrja\Http\Client\Provider\ServiceProvider;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    /**
     * @throws Exception
     */
    public function testPublishClient(): void
    {
        $this->container->setSingleton(GuzzleClient::class, self::createStub(GuzzleClient::class));

        ServiceProvider::publishClient($this->container);

        self::assertInstanceOf(GuzzleClient::class, $this->container->getSingleton(ClientContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishGuzzleClient(): void
    {
        $this->container->setSingleton(Client::class, self::createStub(Client::class));
        $this->container->setSingleton(ResponseFactoryContract::class, self::createStub(ResponseFactoryContract::class));

        ServiceProvider::publishGuzzleClient($this->container);

        self::assertInstanceOf(GuzzleClient::class, $this->container->getSingleton(GuzzleClient::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogClient(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        ServiceProvider::publishLogClient($this->container);

        self::assertInstanceOf(LogClient::class, $this->container->getSingleton(LogClient::class));
    }

    public function testPublishNullClient(): void
    {
        ServiceProvider::publishNullClient($this->container);

        self::assertInstanceOf(NullClient::class, $this->container->getSingleton(NullClient::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishGuzzle(): void
    {
        ServiceProvider::publishGuzzle($this->container);

        self::assertInstanceOf(Client::class, $this->container->getSingleton(Client::class));
    }
}
