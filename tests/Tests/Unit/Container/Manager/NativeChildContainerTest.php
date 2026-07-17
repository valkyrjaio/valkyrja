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

use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\NativeChildContainer;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Dispatch\Provider\DispatchServiceProvider;
use Valkyrja\Tests\Fixtures\Container\ServiceFixture;
use Valkyrja\Tests\Fixtures\Container\SingletonFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the NativeChildContainer: child-first reads with parent fallback via direct field access.
 */
final class NativeChildContainerTest extends TestCase
{
    private Container $parent;
    private NativeChildContainer $child;

    protected function setUp(): void
    {
        $this->parent = new Container();
        $this->child  = new NativeChildContainer($this->parent);
    }

    // -----------------------------------------------------------------------
    // isAlias
    // -----------------------------------------------------------------------

    public function testIsAliasFromParent(): void
    {
        $this->parent->bind(ServiceFixture::class, [ServiceFixture::class, 'make']);
        $this->parent->bindAlias('myAlias', ServiceFixture::class);

        self::assertTrue($this->child->isAlias('myAlias'));
        self::assertFalse($this->child->isAlias('unknown'));
    }

    public function testIsAliasFromChild(): void
    {
        $this->child->bind(ServiceFixture::class, [ServiceFixture::class, 'make']);
        $this->child->bindAlias('childAlias', ServiceFixture::class);

        self::assertTrue($this->child->isAlias('childAlias'));
        self::assertFalse($this->parent->isAlias('childAlias'));
    }

    // -----------------------------------------------------------------------
    // isService
    // -----------------------------------------------------------------------

    public function testIsServiceFromParent(): void
    {
        $this->parent->bind(ServiceFixture::class, [ServiceFixture::class, 'make']);

        self::assertTrue($this->child->isService(ServiceFixture::class));
        self::assertFalse($this->child->isService(SingletonFixture::class));
    }

    public function testIsServiceFromChild(): void
    {
        $this->child->bind(ServiceFixture::class, [ServiceFixture::class, 'make']);

        self::assertTrue($this->child->isService(ServiceFixture::class));
        self::assertFalse($this->parent->isService(ServiceFixture::class));
    }

    // -----------------------------------------------------------------------
    // isSingleton / isSingletonBinding / isSingletonInstance
    // -----------------------------------------------------------------------

    public function testIsSingletonBindingFromParent(): void
    {
        $this->parent->bindSingleton(SingletonFixture::class, [SingletonFixture::class, 'make']);

        self::assertTrue($this->child->isSingletonBinding(SingletonFixture::class));
        self::assertTrue($this->child->isSingleton(SingletonFixture::class));
        self::assertFalse($this->child->isSingletonInstance(SingletonFixture::class));
    }

    public function testIsSingletonInstanceFromParent(): void
    {
        $instance = new SingletonFixture();
        $this->parent->setSingleton(SingletonFixture::class, $instance);

        self::assertTrue($this->child->isSingletonInstance(SingletonFixture::class));
        self::assertTrue($this->child->isSingleton(SingletonFixture::class));
    }

    public function testIsSingletonBindingFromChild(): void
    {
        $this->child->bindSingleton(SingletonFixture::class, [SingletonFixture::class, 'make']);

        self::assertTrue($this->child->isSingletonBinding(SingletonFixture::class));
        self::assertFalse($this->parent->isSingletonBinding(SingletonFixture::class));
    }

    public function testIsSingletonInstanceFromChild(): void
    {
        $instance = new SingletonFixture();
        $this->child->setSingleton(SingletonFixture::class, $instance);

        self::assertTrue($this->child->isSingletonInstance(SingletonFixture::class));
        self::assertFalse($this->parent->isSingletonInstance(SingletonFixture::class));
    }

    // -----------------------------------------------------------------------
    // has (registered via provider) / isPublished
    // -----------------------------------------------------------------------

    public function testHasFromParentWhenRegisteredInParent(): void
    {
        $this->parent->register(new DispatchServiceProvider());

        self::assertTrue($this->child->has(DispatcherContract::class));
    }

    public function testHasFromChildWhenRegisteredInChild(): void
    {
        $this->child->register(new DispatchServiceProvider());

        self::assertTrue($this->child->has(DispatcherContract::class));
        self::assertFalse($this->parent->has(DispatcherContract::class));
    }

    public function testIsPublishedFromParent(): void
    {
        $this->parent->bind(ServiceFixture::class, [ServiceFixture::class, 'make']);

        self::assertTrue($this->child->isPublished(ServiceFixture::class));
    }

    public function testIsPublishedFromChild(): void
    {
        $this->child->bind(ServiceFixture::class, [ServiceFixture::class, 'make']);

        self::assertTrue($this->child->isPublished(ServiceFixture::class));
        self::assertFalse($this->parent->isPublished(ServiceFixture::class));
    }

    // -----------------------------------------------------------------------
    // getSingleton — parent fallback and child isolation
    // -----------------------------------------------------------------------

    public function testGetSingletonFromParentBinding(): void
    {
        $this->parent->bindSingleton(SingletonFixture::class, [SingletonFixture::class, 'make']);

        $instance = $this->child->getSingleton(SingletonFixture::class);

        self::assertInstanceOf(SingletonFixture::class, $instance);
        // Resolved instance must be cached in child, NOT in parent
        self::assertSame($instance, $this->child->getSingleton(SingletonFixture::class));
        self::assertFalse($this->parent->isSingletonInstance(SingletonFixture::class));
    }

    public function testGetSingletonFromParentInstance(): void
    {
        $parentInstance = new SingletonFixture();
        $this->parent->setSingleton(SingletonFixture::class, $parentInstance);

        $childResult = $this->child->getSingleton(SingletonFixture::class);

        self::assertSame($parentInstance, $childResult);
    }

    public function testGetSingletonFromChildOverridesParent(): void
    {
        $parentInstance = new SingletonFixture();
        $this->parent->setSingleton(SingletonFixture::class, $parentInstance);

        $childInstance = new SingletonFixture();
        $this->child->setSingleton(SingletonFixture::class, $childInstance);

        self::assertSame($childInstance, $this->child->getSingleton(SingletonFixture::class));
        self::assertNotSame($parentInstance, $this->child->getSingleton(SingletonFixture::class));
    }

    public function testChildSingletonDoesNotPollutesParent(): void
    {
        $this->parent->bindSingleton(SingletonFixture::class, [SingletonFixture::class, 'make']);

        $childInstance = $this->child->getSingleton(SingletonFixture::class);

        // Parent must remain unpolluted
        self::assertFalse($this->parent->isSingletonInstance(SingletonFixture::class));
        self::assertNotNull($childInstance);
    }

    // -----------------------------------------------------------------------
    // getService — parent fallback
    // -----------------------------------------------------------------------

    public function testGetServiceFromParent(): void
    {
        $this->parent->bind(ServiceFixture::class, [ServiceFixture::class, 'make']);

        $instance = $this->child->getService(ServiceFixture::class);

        self::assertInstanceOf(ServiceFixture::class, $instance);
        // Services always create fresh instances; container passed should be the child
        self::assertSame($this->child, $instance->getContainer());
        self::assertNotSame($instance, $this->child->getService(ServiceFixture::class));
    }

    public function testGetServiceFromChild(): void
    {
        $this->child->bind(ServiceFixture::class, [ServiceFixture::class, 'make']);

        $instance = $this->child->getService(ServiceFixture::class);

        self::assertInstanceOf(ServiceFixture::class, $instance);
        self::assertFalse($this->parent->isService(ServiceFixture::class));
    }

    // -----------------------------------------------------------------------
    // getAliased — parent fallback
    // -----------------------------------------------------------------------

    public function testGetAliasedFromParent(): void
    {
        $this->parent->bind(ServiceFixture::class, [ServiceFixture::class, 'make']);
        $this->parent->bindAlias('svcAlias', ServiceFixture::class);

        $instance = $this->child->getAliased('svcAlias');

        self::assertInstanceOf(ServiceFixture::class, $instance);
    }

    // -----------------------------------------------------------------------
    // Parent immutability — parent state must not change through child operations
    // -----------------------------------------------------------------------

    public function testParentStateUnchangedAfterChildOperations(): void
    {
        // Set up parent with each registration type
        $this->parent->bind(ServiceFixture::class, [ServiceFixture::class, 'make']);
        $this->parent->bindAlias('svcAlias', ServiceFixture::class);
        $this->parent->bindSingleton(SingletonFixture::class, [SingletonFixture::class, 'make']);
        $this->parent->register(new DispatchServiceProvider());

        // Snapshot parent state before any child interaction
        $dataBefore                = $this->parent->getData();
        $singletonInstanceBefore   = $this->parent->isSingletonInstance(SingletonFixture::class);
        $dispatcherPublishedBefore = $this->parent->isPublished(DispatcherContract::class);

        // Perform a broad set of child operations
        $this->child->get(ServiceFixture::class);
        $this->child->getService(ServiceFixture::class);
        $this->child->getAliased('svcAlias');
        $this->child->getSingleton(SingletonFixture::class);
        $this->child->get(DispatcherContract::class); // triggers publish in child

        // Parent data maps must be identical
        $dataAfter = $this->parent->getData();
        self::assertSame($dataBefore->aliases, $dataAfter->aliases);
        self::assertSame($dataBefore->services, $dataAfter->services);
        self::assertSame($dataBefore->singletons, $dataAfter->singletons);
        self::assertSame($dataBefore->callbacks, $dataAfter->callbacks);

        // Singleton resolved in child must not have been cached in parent
        self::assertSame($singletonInstanceBefore, $this->parent->isSingletonInstance(SingletonFixture::class));

        // Service published in child must not mark parent as published
        self::assertSame($dispatcherPublishedBefore, $this->parent->isPublished(DispatcherContract::class));
    }

    // -----------------------------------------------------------------------
    // Provider — published in child context
    // -----------------------------------------------------------------------

    public function testProviderFromChildPublishedInChild(): void
    {
        $this->child->register(new DispatchServiceProvider());

        self::assertTrue($this->child->has(DispatcherContract::class));

        $dispatcher = $this->child->get(DispatcherContract::class);
        self::assertInstanceOf(DispatcherContract::class, $dispatcher);

        self::assertFalse($this->parent->isPublished(DispatcherContract::class));
    }

    public function testProviderFromParentPublishedInChild(): void
    {
        $this->parent->register(new DispatchServiceProvider());

        // has() should see the service from parent
        self::assertTrue($this->child->has(DispatcherContract::class));

        // get() should trigger publish in child and return the service
        $dispatcher = $this->child->get(DispatcherContract::class);
        self::assertInstanceOf(DispatcherContract::class, $dispatcher);

        // Publishing in child must not pollute parent
        self::assertFalse($this->parent->isPublished(DispatcherContract::class));
    }
}
