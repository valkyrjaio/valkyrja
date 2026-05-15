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

namespace Valkyrja\Tests\Unit\Container\Manager;

use Throwable;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Enum\InvalidReferenceMode;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Throwable\Exception\Abstract\ContainerInvalidArgumentException;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Dispatch\Provider\DispatchServiceProvider;
use Valkyrja\Tests\Classes\Container\ServiceClass;
use Valkyrja\Tests\Classes\Container\SingletonClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the container service.
 */
final class ContainerTest extends TestCase
{
    /**
     * The class  to test with.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function testBind(): void
    {
        $container = new Container();
        $id        = ServiceClass::class;

        $container->bind($id, [ServiceClass::class, 'make']);

        self::assertTrue($container->has($id));
        self::assertTrue($container->isService($id));
        self::assertTrue($container->isPublished($id));

        self::assertFalse($container->isAlias($id));
        self::assertFalse($container->isSingleton($id));

        self::assertInstanceOf($id, $service = $container->get($id));
        // A bound service should return a new instance each time it is retrieved
        self::assertNotSame($service, $container->get($id));
        self::assertNotSame($service, $container->getService($id));
    }

    public function testBindAlias(): void
    {
        $container = $this->container;
        $id        = ServiceClass::class;
        $alias     = 'alias';

        $container->bind($id, [ServiceClass::class, 'make']);
        $container->bindAlias($alias, $id);

        self::assertTrue($container->has($alias));
        self::assertTrue($container->isAlias($alias));
        self::assertTrue($container->isPublished($id));
        self::assertFalse($container->isService($alias));
        self::assertFalse($container->isSingleton($id));

        self::assertInstanceOf($id, $service = $container->get($alias));
        // A bound service should return a new instance each time it is retrieved
        self::assertNotSame($service, $container->get($alias));

        self::assertInstanceOf($id, $container->getAliased($alias));
        self::assertNotSame($service, $container->getAliased($alias));
    }

    public function testBindSingleton(): void
    {
        $container = $this->container;
        $id        = SingletonClass::class;

        $container->bindSingleton($id, [SingletonClass::class, 'make']);

        self::assertTrue($container->has($id));
        self::assertTrue($container->isSingleton($id));
        // A singleton is a service when bound
        self::assertTrue($container->isService($id));
        self::assertTrue($container->isPublished($id));

        self::assertFalse($container->isAlias($id));

        self::assertInstanceOf($id, $service = $container->get($id));
        // A bound singleton should return the same instance each time it is retrieved
        self::assertSame($service, $container->get($id));
        self::assertSame($service, $container->getSingleton($id));
    }

    public function testProvided(): void
    {
        $container = $this->container;

        $container->register(new DispatchServiceProvider());

        self::assertTrue($container->has(DispatcherContract::class));
    }

    public function testGetNonExistent(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $this->container->get(ApplicationContract::class);
    }

    public function testGetNonExistentSingleton(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $this->container->getSingleton(ServiceClass::class);
    }

    public function testGetNonExistentInvalidSingleton(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $this->container->getSingleton(self::class);
    }

    public function testGetNonExistentAliased(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $this->container->getAliased(self::class);
    }

    public function testGetNonExistentService(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $this->container->getService(ServiceClass::class);
    }

    public function testGetNonExistentInvalidService(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $this->container->getService(self::class);
    }

    public function testGetData(): void
    {
        $container = $this->container;

        $container->register(new DispatchServiceProvider());

        self::assertTrue($container->has(DispatcherContract::class));

        $data = $this->container->getData();

        self::assertSame(
            [
                DispatcherContract::class => [DispatchServiceProvider::class, 'publishDispatcher'],
            ],
            $data->callbacks
        );

        self::assertEmpty($data->aliases);
        self::assertEmpty($data->services);
        self::assertEmpty($data->singletons);
    }

    public function testSetFromData(): void
    {
        $container = $this->container;

        $container->register(new DispatchServiceProvider());

        self::assertTrue($container->has(DispatcherContract::class));

        $data = $this->container->getData();

        $container2 = new Container();

        self::assertFalse($container2->has(DispatcherContract::class));

        $container2->setFromData($data);

        self::assertTrue($container2->has(DispatcherContract::class));

        $newData = $container2->getData();

        self::assertSame(
            [
                DispatcherContract::class => [DispatchServiceProvider::class, 'publishDispatcher'],
            ],
            $newData->callbacks
        );
    }

    public function testConstructWithData(): void
    {
        $container = $this->container;

        $container->register(new DispatchServiceProvider());

        self::assertTrue($container->has(DispatcherContract::class));

        $data = $this->container->getData();

        $container2 = new Container($data);

        self::assertTrue($container2->has(DispatcherContract::class));

        $newData = $container2->getData();

        self::assertSame(
            [
                DispatcherContract::class => [DispatchServiceProvider::class, 'publishDispatcher'],
            ],
            $newData->callbacks
        );
    }

    public function testNewInstanceOrThrowInvalidReferenceMode(): void
    {
        $container = new Container();

        $object = $container->get(SingletonClass::class, mode: InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION);

        self::assertInstanceOf(SingletonClass::class, $object);
    }

    public function testNewInstanceOrThrowInvalidReferenceModeWithCaughtThrowable(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $container = new Container();

        // Will fail because this requires the container as the first argument, but no arguments passed
        $container->get(ServiceClass::class, mode: InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION);
    }

    /**
     * This mode should always throw an exception if the service isn't found in the container.
     */
    public function testThrowExceptionInvalidReferenceMode(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $container = new Container();

        $container->get(ServiceClass::class, mode: InvalidReferenceMode::THROW_EXCEPTION);
    }

    public function testNewInstanceThrowInvalidReferenceMode(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $container = new Container();

        $container->get(Throwable::class, mode: InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION);
    }
}
