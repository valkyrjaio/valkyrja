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

namespace Valkyrja\Tests\Unit\Event\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract as DispatchDispatcherContract;
use Valkyrja\Event\Collection\Collection;
use Valkyrja\Event\Collection\Contract\CollectionContract;
use Valkyrja\Event\Collector\AttributeCollector;
use Valkyrja\Event\Collector\Contract\CollectorContract;
use Valkyrja\Event\Data\Data;
use Valkyrja\Event\Data\Listener;
use Valkyrja\Event\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Event\Dispatcher\Dispatcher;
use Valkyrja\Event\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Event\Generator\DataFileGenerator;
use Valkyrja\Event\Provider\ServiceProvider;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Support\Generator\Enum\GenerateStatus;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(CollectorContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(DispatcherContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CollectionContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(DataFileGeneratorContract::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(CollectorContract::class, ServiceProvider::provides());
        self::assertContains(DispatcherContract::class, ServiceProvider::provides());
        self::assertContains(CollectionContract::class, ServiceProvider::provides());
        self::assertContains(DataFileGeneratorContract::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishAttributesCollector(): void
    {
        $this->container->setSingleton(AttributeCollectorContract::class, self::createStub(AttributeCollectorContract::class));
        $this->container->setSingleton(ReflectorContract::class, self::createStub(ReflectorContract::class));

        $callback = ServiceProvider::publishers()[CollectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(AttributeCollector::class, $this->container->getSingleton(CollectorContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishDispatcher(): void
    {
        $this->container->setSingleton(DispatchDispatcherContract::class, self::createStub(DispatchDispatcherContract::class));
        $this->container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));

        $callback = ServiceProvider::publishers()[DispatcherContract::class];
        $callback($this->container);

        self::assertInstanceOf(Dispatcher::class, $this->container->getSingleton(DispatcherContract::class));
    }

    public function testPublishCollectionWithCustomDataProvided(): void
    {
        $this->container->setSingleton(Data::class, new Data());

        $callback = ServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $this->container->getSingleton(CollectionContract::class));
    }

    public function testPublishCollectionWithData(): void
    {
        $this->container->setSingleton(ApplicationContract::class, self::createStub(ApplicationContract::class));
        $this->container->setSingleton(Env::class, new class extends Env {
            public const bool EVENT_COLLECTION_USE_DATA         = true;
            public const string EVENT_COLLECTION_DATA_FILE_PATH = 'testPublishCollectionWithData-events.php';
        });

        $filePath  = EnvClass::APP_DIR . '/data/testPublishCollectionWithData-events.php';
        $generator = new DataFileGenerator($filePath, new Data());
        $generator->generateFile();

        self::assertFalse($this->container->has(CollectionContract::class));

        $callback = ServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $this->container->getSingleton(CollectionContract::class));
        self::assertFalse($this->container->has(Data::class));

        @unlink($filePath);
    }

    /**
     * @throws Exception
     */
    public function testPublishCollectionWithoutData(): void
    {
        $this->container->setSingleton(ApplicationContract::class, self::createStub(ApplicationContract::class));
        $this->container->setSingleton(CollectorContract::class, $collector = self::createStub(CollectorContract::class));
        $this->container->setSingleton(DataFileGeneratorContract::class, $generator = self::createStub(DataFileGeneratorContract::class));

        $eventId      = self::class;
        $listenerName = 'listener-name';
        $listener     = new Listener(eventId: $eventId, name: $listenerName);

        $collector->method('getListeners')->willReturn([$listener]);
        $generator->method('generateFile')->willReturn(GenerateStatus::SUCCESS);

        self::assertFalse($this->container->has(Data::class));

        $callback = ServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $collection = $this->container->getSingleton(CollectionContract::class));
        self::assertTrue($this->container->has(Data::class));
        self::assertContains($eventId, $collection->getEvents());
        self::assertTrue($collection->hasListenerById($listenerName));
        self::assertTrue($collection->hasListener($listener));
        self::assertTrue($collection->hasListenersForEventById($eventId));
    }

    public function testPublishDataFileGenerator(): void
    {
        $container = $this->container;

        $container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));

        self::assertFalse($container->has(CollectorContract::class));

        $callback = ServiceProvider::publishers()[DataFileGeneratorContract::class];
        $callback($this->container);

        self::assertTrue($container->has(DataFileGeneratorContract::class));
        self::assertTrue($container->isSingleton(DataFileGeneratorContract::class));
        self::assertInstanceOf(DataFileGenerator::class, $container->getSingleton(DataFileGeneratorContract::class));
    }
}
