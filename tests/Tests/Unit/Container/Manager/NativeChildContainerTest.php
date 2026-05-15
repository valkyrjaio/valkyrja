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
use Valkyrja\Tests\Classes\Container\ServiceClass;
use Valkyrja\Tests\Classes\Container\SingletonClass;
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
        $this->parent->bind(ServiceClass::class, [ServiceClass::class, 'make']);
        $this->parent->bindAlias('myAlias', ServiceClass::class);

        self::assertTrue($this->child->isAlias('myAlias'));
        self::assertFalse($this->child->isAlias('unknown'));
    }

    public function testIsAliasFromChild(): void
    {
        $this->child->bind(ServiceClass::class, [ServiceClass::class, 'make']);
        $this->child->bindAlias('childAlias', ServiceClass::class);

        self::assertTrue($this->child->isAlias('childAlias'));
        self::assertFalse($this->parent->isAlias('childAlias'));
    }

    // -----------------------------------------------------------------------
    // isService
    // -----------------------------------------------------------------------

    public function testIsServiceFromParent(): void
    {
        $this->parent->bind(ServiceClass::class, [ServiceClass::class, 'make']);

        self::assertTrue($this->child->isService(ServiceClass::class));
        self::assertFalse($this->child->isService(SingletonClass::class));
    }

    public function testIsServiceFromChild(): void
    {
        $this->child->bind(ServiceClass::class, [ServiceClass::class, 'make']);

        self::assertTrue($this->child->isService(ServiceClass::class));
        self::assertFalse($this->parent->isService(ServiceClass::class));
    }

    // -----------------------------------------------------------------------
    // isSingleton / isSingletonBinding / isSingletonInstance
    // -----------------------------------------------------------------------

    public function testIsSingletonBindingFromParent(): void
    {
        $this->parent->bindSingleton(SingletonClass::class, [SingletonClass::class, 'make']);

        self::assertTrue($this->child->isSingletonBinding(SingletonClass::class));
        self::assertTrue($this->child->isSingleton(SingletonClass::class));
        self::assertFalse($this->child->isSingletonInstance(SingletonClass::class));
    }

    public function testIsSingletonInstanceFromParent(): void
    {
        $instance = new SingletonClass();
        $this->parent->setSingleton(SingletonClass::class, $instance);

        self::assertTrue($this->child->isSingletonInstance(SingletonClass::class));
        self::assertTrue($this->child->isSingleton(SingletonClass::class));
    }

    public function testIsSingletonBindingFromChild(): void
    {
        $this->child->bindSingleton(SingletonClass::class, [SingletonClass::class, 'make']);

        self::assertTrue($this->child->isSingletonBinding(SingletonClass::class));
        self::assertFalse($this->parent->isSingletonBinding(SingletonClass::class));
    }

    public function testIsSingletonInstanceFromChild(): void
    {
        $instance = new SingletonClass();
        $this->child->setSingleton(SingletonClass::class, $instance);

        self::assertTrue($this->child->isSingletonInstance(SingletonClass::class));
        self::assertFalse($this->parent->isSingletonInstance(SingletonClass::class));
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
        $this->parent->bind(ServiceClass::class, [ServiceClass::class, 'make']);

        self::assertTrue($this->child->isPublished(ServiceClass::class));
    }

    public function testIsPublishedFromChild(): void
    {
        $this->child->bind(ServiceClass::class, [ServiceClass::class, 'make']);

        self::assertTrue($this->child->isPublished(ServiceClass::class));
        self::assertFalse($this->parent->isPublished(ServiceClass::class));
    }

    // -----------------------------------------------------------------------
    // getSingleton — parent fallback and child isolation
    // -----------------------------------------------------------------------

    public function testGetSingletonFromParentBinding(): void
    {
        $this->parent->bindSingleton(SingletonClass::class, [SingletonClass::class, 'make']);

        $instance = $this->child->getSingleton(SingletonClass::class);

        self::assertInstanceOf(SingletonClass::class, $instance);
        // Resolved instance must be cached in child, NOT in parent
        self::assertSame($instance, $this->child->getSingleton(SingletonClass::class));
        self::assertFalse($this->parent->isSingletonInstance(SingletonClass::class));
    }

    public function testGetSingletonFromParentInstance(): void
    {
        $parentInstance = new SingletonClass();
        $this->parent->setSingleton(SingletonClass::class, $parentInstance);

        $childResult = $this->child->getSingleton(SingletonClass::class);

        self::assertSame($parentInstance, $childResult);
    }

    public function testGetSingletonFromChildOverridesParent(): void
    {
        $parentInstance = new SingletonClass();
        $this->parent->setSingleton(SingletonClass::class, $parentInstance);

        $childInstance = new SingletonClass();
        $this->child->setSingleton(SingletonClass::class, $childInstance);

        self::assertSame($childInstance, $this->child->getSingleton(SingletonClass::class));
        self::assertNotSame($parentInstance, $this->child->getSingleton(SingletonClass::class));
    }

    public function testChildSingletonDoesNotPollutesParent(): void
    {
        $this->parent->bindSingleton(SingletonClass::class, [SingletonClass::class, 'make']);

        $childInstance = $this->child->getSingleton(SingletonClass::class);

        // Parent must remain unpolluted
        self::assertFalse($this->parent->isSingletonInstance(SingletonClass::class));
        self::assertNotNull($childInstance);
    }

    // -----------------------------------------------------------------------
    // getService — parent fallback
    // -----------------------------------------------------------------------

    public function testGetServiceFromParent(): void
    {
        $this->parent->bind(ServiceClass::class, [ServiceClass::class, 'make']);

        $instance = $this->child->getService(ServiceClass::class);

        self::assertInstanceOf(ServiceClass::class, $instance);
        // Services always create fresh instances; container passed should be the child
        self::assertSame($this->child, $instance->getContainer());
        self::assertNotSame($instance, $this->child->getService(ServiceClass::class));
    }

    public function testGetServiceFromChild(): void
    {
        $this->child->bind(ServiceClass::class, [ServiceClass::class, 'make']);

        $instance = $this->child->getService(ServiceClass::class);

        self::assertInstanceOf(ServiceClass::class, $instance);
        self::assertFalse($this->parent->isService(ServiceClass::class));
    }

    // -----------------------------------------------------------------------
    // getAliased — parent fallback
    // -----------------------------------------------------------------------

    public function testGetAliasedFromParent(): void
    {
        $this->parent->bind(ServiceClass::class, [ServiceClass::class, 'make']);
        $this->parent->bindAlias('svcAlias', ServiceClass::class);

        $instance = $this->child->getAliased('svcAlias');

        self::assertInstanceOf(ServiceClass::class, $instance);
    }

    // -----------------------------------------------------------------------
    // Parent immutability — parent state must not change through child operations
    // -----------------------------------------------------------------------

    public function testParentStateUnchangedAfterChildOperations(): void
    {
        // Set up parent with each registration type
        $this->parent->bind(ServiceClass::class, [ServiceClass::class, 'make']);
        $this->parent->bindAlias('svcAlias', ServiceClass::class);
        $this->parent->bindSingleton(SingletonClass::class, [SingletonClass::class, 'make']);
        $this->parent->register(new DispatchServiceProvider());

        // Snapshot parent state before any child interaction
        $dataBefore                = $this->parent->getData();
        $singletonInstanceBefore   = $this->parent->isSingletonInstance(SingletonClass::class);
        $dispatcherPublishedBefore = $this->parent->isPublished(DispatcherContract::class);

        // Perform a broad set of child operations
        $this->child->get(ServiceClass::class);
        $this->child->getService(ServiceClass::class);
        $this->child->getAliased('svcAlias');
        $this->child->getSingleton(SingletonClass::class);
        $this->child->get(DispatcherContract::class); // triggers publish in child

        // Parent data maps must be identical
        $dataAfter = $this->parent->getData();
        self::assertSame($dataBefore->aliases, $dataAfter->aliases);
        self::assertSame($dataBefore->services, $dataAfter->services);
        self::assertSame($dataBefore->singletons, $dataAfter->singletons);
        self::assertSame($dataBefore->callbacks, $dataAfter->callbacks);

        // Singleton resolved in child must not have been cached in parent
        self::assertSame($singletonInstanceBefore, $this->parent->isSingletonInstance(SingletonClass::class));

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
