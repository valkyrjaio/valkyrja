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
use Valkyrja\Http\Client\Provider\HttpClientServiceProvider;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = HttpClientServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(ClientContract::class, new HttpClientServiceProvider()->publishers());
        self::assertArrayHasKey(GuzzleClient::class, new HttpClientServiceProvider()->publishers());
        self::assertArrayHasKey(Client::class, new HttpClientServiceProvider()->publishers());
        self::assertArrayHasKey(LogClient::class, new HttpClientServiceProvider()->publishers());
        self::assertArrayHasKey(NullClient::class, new HttpClientServiceProvider()->publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishClient(): void
    {
        $this->container->setSingleton(GuzzleClient::class, self::createStub(GuzzleClient::class));

        $callback = new HttpClientServiceProvider()->publishers()[ClientContract::class];
        $callback($this->container);

        self::assertInstanceOf(GuzzleClient::class, $this->container->getSingleton(ClientContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishGuzzleClient(): void
    {
        $this->container->setSingleton(Client::class, self::createStub(Client::class));
        $this->container->setSingleton(ResponseFactoryContract::class, self::createStub(ResponseFactoryContract::class));

        $callback = new HttpClientServiceProvider()->publishers()[GuzzleClient::class];
        $callback($this->container);

        self::assertInstanceOf(GuzzleClient::class, $this->container->getSingleton(GuzzleClient::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogClient(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = new HttpClientServiceProvider()->publishers()[LogClient::class];
        $callback($this->container);

        self::assertInstanceOf(LogClient::class, $this->container->getSingleton(LogClient::class));
    }

    public function testPublishNullClient(): void
    {
        $callback = new HttpClientServiceProvider()->publishers()[NullClient::class];
        $callback($this->container);

        self::assertInstanceOf(NullClient::class, $this->container->getSingleton(NullClient::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishGuzzle(): void
    {
        $callback = new HttpClientServiceProvider()->publishers()[Client::class];
        $callback($this->container);

        self::assertInstanceOf(Client::class, $this->container->getSingleton(Client::class));
    }
}
