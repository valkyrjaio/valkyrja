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
use Valkyrja\Application\Data\Config;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Collector\AttributeCollector;
use Valkyrja\Cli\Routing\Collector\Contract\CollectorContract;
use Valkyrja\Cli\Routing\Data\Data;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Cli\Routing\Provider\ServiceProvider;
use Valkyrja\Dispatch\Data\MethodDispatch as DefaultDispatch;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
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
    public function testPublishAttributeCollector(): void
    {
        $this->container->setSingleton(AttributeCollectorContract::class, self::createStub(AttributeCollectorContract::class));
        $this->container->setSingleton(ReflectorContract::class, self::createStub(ReflectorContract::class));

        ServiceProvider::publishAttributeCollector($this->container);

        self::assertInstanceOf(AttributeCollector::class, $this->container->getSingleton(CollectorContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishRouter(): void
    {
        $this->container->setSingleton(ThrowableCaughtHandlerContract::class, self::createStub(ThrowableCaughtHandlerContract::class));
        $this->container->setSingleton(CommandMatchedHandlerContract::class, self::createStub(CommandMatchedHandlerContract::class));
        $this->container->setSingleton(CommandNotMatchedHandlerContract::class, self::createStub(CommandNotMatchedHandlerContract::class));
        $this->container->setSingleton(CommandDispatchedHandlerContract::class, self::createStub(CommandDispatchedHandlerContract::class));
        $this->container->setSingleton(ExitedHandlerContract::class, self::createStub(ExitedHandlerContract::class));
        $this->container->setSingleton(DispatcherContract::class, self::createStub(DispatcherContract::class));
        $this->container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));
        $this->container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

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
        $this->container->setSingleton(CollectorContract::class, $collector = self::createStub(CollectorContract::class));

        $command = new Route(
            name: 'test',
            description: 'test',
            helpText: new Message('test'),
            dispatch: new DefaultDispatch(self::class, 'dispatch')
        );
        $collector->method('getRoutes')->willReturn([$command]);

        ServiceProvider::publishCollection($this->container);

        self::assertInstanceOf(Collection::class, $collection = $this->container->getSingleton(CollectionContract::class));
        self::assertNotNull($collection->get('test'));
    }
}
