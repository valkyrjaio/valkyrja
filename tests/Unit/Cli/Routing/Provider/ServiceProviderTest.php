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

namespace Valkyrja\Tests\Unit\Cli\Routing\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Config;
use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Collection\Contract\Collection as CollectionContract;
use Valkyrja\Cli\Routing\Collector\AttributeCollector;
use Valkyrja\Cli\Routing\Collector\Contract\Collector;
use Valkyrja\Cli\Routing\Contract\Router as RouterContract;
use Valkyrja\Cli\Routing\Data;
use Valkyrja\Cli\Routing\Provider\ServiceProvider;
use Valkyrja\Cli\Routing\Router;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Reflection\Contract\Reflection;
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
    public function testPublishAttributeCollector(): void
    {
        $this->container->setSingleton(Attributes::class, $this->createStub(Attributes::class));
        $this->container->setSingleton(Reflection::class, $this->createStub(Reflection::class));

        ServiceProvider::publishAttributeCollector($this->container);

        self::assertInstanceOf(AttributeCollector::class, $this->container->getSingleton(Collector::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishRouter(): void
    {
        $this->container->setSingleton(ThrowableCaughtHandler::class, $this->createStub(ThrowableCaughtHandler::class));
        $this->container->setSingleton(CommandMatchedHandler::class, $this->createStub(CommandMatchedHandler::class));
        $this->container->setSingleton(CommandNotMatchedHandler::class, $this->createStub(CommandNotMatchedHandler::class));
        $this->container->setSingleton(CommandDispatchedHandler::class, $this->createStub(CommandDispatchedHandler::class));
        $this->container->setSingleton(ExitedHandler::class, $this->createStub(ExitedHandler::class));
        $this->container->setSingleton(Dispatcher::class, $this->createStub(Dispatcher::class));
        $this->container->setSingleton(CollectionContract::class, $this->createStub(CollectionContract::class));
        $this->container->setSingleton(OutputFactory::class, $this->createStub(OutputFactory::class));

        ServiceProvider::publishRouter($this->container);

        self::assertInstanceOf(Router::class, $this->container->getSingleton(RouterContract::class));
    }

    public function testPublishCollectionWithData(): void
    {
        $this->container->setSingleton(Data::class, new Data());

        ServiceProvider::publishCollection($this->container);

        self::assertInstanceOf(Collection::class, $this->container->getSingleton(CollectionContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCollectionWithConfig(): void
    {
        $this->container->setSingleton(Config::class, new Config());
        $this->container->setSingleton(Collector::class, $collector = $this->createStub(Collector::class));

        $command = new Data\Command(name: 'test', description: 'test', helpText: new Message('test'));
        $collector->method('getCommands')->willReturn([$command]);

        ServiceProvider::publishCollection($this->container);

        self::assertInstanceOf(Collection::class, $collection = $this->container->getSingleton(CollectionContract::class));
        self::assertNotNull($collection->get('test'));
    }
}
