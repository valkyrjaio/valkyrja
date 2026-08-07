<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Client\Provider;

use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Client\Data\Contract\HttpClientConfigContract;
use Valkyrja\Http\Client\Data\HttpClientConfig;
use Valkyrja\Http\Client\Manager\Contract\ClientContract;
use Valkyrja\Http\Client\Manager\GuzzleClient;
use Valkyrja\Http\Client\Manager\LogClient;
use Valkyrja\Http\Client\Manager\NullClient;
use Valkyrja\Http\Client\Provider\HttpClientServiceProvider;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\Fixtures\Http\Client\Data\HttpClientConfigFixture;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /**
     * @inheritDoc
     *
     * @var class-string<ServiceProviderContract>
     */
    protected static string $provider = HttpClientServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(HttpClientConfigContract::class, new HttpClientServiceProvider()->publishers());
        self::assertArrayHasKey(ClientContract::class, new HttpClientServiceProvider()->publishers());
        self::assertArrayHasKey(GuzzleClient::class, new HttpClientServiceProvider()->publishers());
        self::assertArrayHasKey(Client::class, new HttpClientServiceProvider()->publishers());
        self::assertArrayHasKey(LogClient::class, new HttpClientServiceProvider()->publishers());
        self::assertArrayHasKey(NullClient::class, new HttpClientServiceProvider()->publishers());
    }

    public function testPublishConfig(): void
    {
        $callback = new HttpClientServiceProvider()->publishers()[HttpClientConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(HttpClientConfigContract::class, $config = $this->container->getSingleton(HttpClientConfigContract::class));
        self::assertSame(GuzzleClient::class, $config->defaultClient);
    }

    public function testPublishConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new HttpClientConfigFixture());

        $callback = new HttpClientServiceProvider()->publishers()[HttpClientConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(HttpClientConfigContract::class, $config = $this->container->getSingleton(HttpClientConfigContract::class));
        self::assertSame(NullClient::class, $config->defaultClient);
    }

    /**
     * @throws Exception
     */
    public function testPublishClient(): void
    {
        $this->container->setSingleton(HttpClientConfigContract::class, new HttpClientConfig());
        $this->container->setSingleton(GuzzleClient::class, self::createStub(GuzzleClient::class));

        $callback = new HttpClientServiceProvider()->publishers()[ClientContract::class];
        $callback($this->container);

        self::assertInstanceOf(GuzzleClient::class, $this->container->getSingleton(ClientContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishClientWithConfiguredDefault(): void
    {
        $this->container->setSingleton(HttpClientConfigContract::class, new HttpClientConfig(defaultClient: NullClient::class));
        $this->container->setSingleton(NullClient::class, self::createStub(NullClient::class));

        $callback = new HttpClientServiceProvider()->publishers()[ClientContract::class];
        $callback($this->container);

        self::assertInstanceOf(NullClient::class, $this->container->getSingleton(ClientContract::class));
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
