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

use GuzzleHttp\Client as Guzzle;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Http\Client\Contract\Client as Contract;
use Valkyrja\Http\Client\GuzzleClient;
use Valkyrja\Http\Client\LogClient;
use Valkyrja\Http\Client\NullClient;
use Valkyrja\Http\Client\Provider\ServiceProvider;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Log\Contract\Logger;
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

    /**
     * @throws Exception
     */
    public function testPublishClient(): void
    {
        $this->container->setSingleton(GuzzleClient::class, $this->createStub(GuzzleClient::class));

        ServiceProvider::publishClient($this->container);

        self::assertInstanceOf(GuzzleClient::class, $this->container->getSingleton(Contract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishGuzzleClient(): void
    {
        $this->container->setSingleton(Guzzle::class, $this->createStub(Guzzle::class));
        $this->container->setSingleton(ResponseFactory::class, $this->createStub(ResponseFactory::class));

        ServiceProvider::publishGuzzleClient($this->container);

        self::assertInstanceOf(GuzzleClient::class, $this->container->getSingleton(GuzzleClient::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogClient(): void
    {
        $this->container->setSingleton(Logger::class, $this->createStub(Logger::class));

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

        self::assertInstanceOf(Guzzle::class, $this->container->getSingleton(Guzzle::class));
    }
}
