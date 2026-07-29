<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Container\Manager;

use Override;
use Throwable;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Enum\InvalidReferenceMode;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Throwable\Exception\Abstract\ContainerInvalidArgumentException;
use Valkyrja\Container\Throwable\Exception\ContainerInvalidReferenceException;
use Valkyrja\Tests\Fixtures\Container\Provider\ProvidedFixture;
use Valkyrja\Tests\Fixtures\Container\Provider\PublishingProviderFixture;
use Valkyrja\Tests\Fixtures\Container\Contract\ServiceFixtureContract;
use Valkyrja\Tests\Fixtures\Container\NonObjectServiceFactoryFixture;
use Valkyrja\Tests\Fixtures\Container\ServiceFixture;
use Valkyrja\Tests\Fixtures\Container\SingletonFixture;
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
    #[Override]
    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function testBind(): void
    {
        $container = new Container();
        $id        = ServiceFixture::class;

        $container->bind($id, [ServiceFixture::class, 'make']);

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
        $id        = ServiceFixture::class;
        $alias     = ServiceFixtureContract::class;

        $container->bind($id, [ServiceFixture::class, 'make']);
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
        $id        = SingletonFixture::class;

        $container->bindSingleton($id, [SingletonFixture::class, 'make']);

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

    /**
     * A singleton binding whose callable does not produce an object must not be cached as
     * an instance; the resolution yields null and getSingleton() reports the service as
     * not found. Reaching this defensive guard needs a synthetic factory, since a
     * well-behaved one always returns an object.
     */
    public function testGetSingletonWithNonObjectFromBindingThrows(): void
    {
        $container = $this->container;
        $id        = SingletonFixture::class;

        $container->bindSingleton($id, NonObjectServiceFactoryFixture::create());

        self::assertTrue($container->isSingletonBinding($id));
        // Nothing was cached as an instance, so the singleton is still unresolved.
        self::assertFalse($container->isSingletonInstance($id));

        $this->expectException(ContainerInvalidReferenceException::class);

        $container->getSingleton($id);
    }

    public function testSetSingleton(): void
    {
        $container = $this->container;
        $id        = SingletonFixture::class;
        $singleton = new SingletonFixture();

        $result = $container->setSingleton($id, $singleton);

        self::assertSame($container, $result);
        self::assertTrue($container->isSingleton($id));
        self::assertSame($singleton, $container->getSingleton($id));
    }

    public function testProvided(): void
    {
        $container = $this->container;

        $container->register(new PublishingProviderFixture());

        self::assertTrue($container->has(ProvidedFixture::class));
    }

    public function testGetNonExistent(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $this->container->get(ApplicationContract::class);
    }

    public function testGetNonExistentSingleton(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $this->container->getSingleton(ServiceFixture::class);
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

        $this->container->getService(ServiceFixture::class);
    }

    public function testGetNonExistentInvalidService(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $this->container->getService(self::class);
    }

    public function testGetData(): void
    {
        $container = $this->container;

        $container->register(new PublishingProviderFixture());

        self::assertTrue($container->has(ProvidedFixture::class));

        $data = $this->container->getData();

        self::assertSame(
            [
                ProvidedFixture::class => [PublishingProviderFixture::class, 'publishProvided'],
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

        $container->register(new PublishingProviderFixture());

        self::assertTrue($container->has(ProvidedFixture::class));

        $data = $this->container->getData();

        $container2 = new Container();

        self::assertFalse($container2->has(ProvidedFixture::class));

        $container2->setFromData($data);

        self::assertTrue($container2->has(ProvidedFixture::class));

        $newData = $container2->getData();

        self::assertSame(
            [
                ProvidedFixture::class => [PublishingProviderFixture::class, 'publishProvided'],
            ],
            $newData->callbacks
        );
    }

    public function testConstructWithData(): void
    {
        $container = $this->container;

        $container->register(new PublishingProviderFixture());

        self::assertTrue($container->has(ProvidedFixture::class));

        $data = $this->container->getData();

        $container2 = new Container($data);

        self::assertTrue($container2->has(ProvidedFixture::class));

        $newData = $container2->getData();

        self::assertSame(
            [
                ProvidedFixture::class => [PublishingProviderFixture::class, 'publishProvided'],
            ],
            $newData->callbacks
        );
    }

    public function testNewInstanceOrThrowInvalidReferenceMode(): void
    {
        $container = new Container();

        $object = $container->get(SingletonFixture::class, mode: InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION);

        self::assertInstanceOf(SingletonFixture::class, $object);
    }

    public function testNewInstanceOrThrowInvalidReferenceModeWithCaughtThrowable(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $container = new Container();

        // Will fail because this requires the container as the first argument, but no arguments passed
        $container->get(ServiceFixture::class, mode: InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION);
    }

    /**
     * This mode should always throw an exception if the service isn't found in the container.
     */
    public function testThrowExceptionInvalidReferenceMode(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $container = new Container();

        $container->get(ServiceFixture::class, mode: InvalidReferenceMode::THROW_EXCEPTION);
    }

    public function testNewInstanceThrowInvalidReferenceMode(): void
    {
        $this->expectException(ContainerInvalidArgumentException::class);

        $container = new Container();

        $container->get(Throwable::class, mode: InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION);
    }
}
