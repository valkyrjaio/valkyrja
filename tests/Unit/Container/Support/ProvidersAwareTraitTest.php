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

namespace Valkyrja\Tests\Unit\Container\Support;

use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Tests\Classes\Support\DeferredProviderClass;
use Valkyrja\Tests\Classes\Support\InvalidDeferredProviderClass;
use Valkyrja\Tests\Classes\Support\ProvidedClass;
use Valkyrja\Tests\Classes\Support\ProvidedSecondaryClass;
use Valkyrja\Tests\Classes\Support\ProviderClass;
use Valkyrja\Tests\Classes\Support\ProvidersAwareClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the ProvidersAwareTrait support class.
 *
 * @author Melech Mizrachi
 */
class ProvidersAwareTraitTest extends TestCase
{
    public function testRegister(): void
    {
        $providersAware = new ProvidersAwareClass();

        self::assertFalse(ProviderClass::$publishCalled);
        self::assertFalse(ProviderClass::$publishSecondaryCalled);

        self::assertFalse($providersAware->isDeferred(ProvidedClass::class));
        self::assertFalse($providersAware->isDeferred(ProvidedSecondaryClass::class));
        self::assertFalse($providersAware->isPublished(ProvidedClass::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryClass::class));
        self::assertFalse($providersAware->isRegistered(ProviderClass::class));

        $providersAware->register(ProviderClass::class);
        // Testing the fact a registered provider isn't registered more than once
        $providersAware->register(ProviderClass::class);

        self::assertTrue(ProviderClass::$publishCalled);
        self::assertFalse(ProviderClass::$publishSecondaryCalled);

        // Since this provider is immediately published
        self::assertFalse($providersAware->isDeferred(ProvidedClass::class));
        self::assertFalse($providersAware->isDeferred(ProvidedSecondaryClass::class));
        // Since this provider is immediately published
        self::assertTrue($providersAware->isPublished(ProvidedClass::class));
        self::assertTrue($providersAware->isPublished(ProvidedSecondaryClass::class));

        self::assertTrue($providersAware->isRegistered(ProviderClass::class));

        $providersAware->publishProvided(ProvidedClass::class);

        self::assertTrue(ProviderClass::$publishCalled);
        self::assertFalse(ProviderClass::$publishSecondaryCalled);

        self::assertFalse($providersAware->isDeferred(ProvidedClass::class));
        self::assertFalse($providersAware->isDeferred(ProvidedSecondaryClass::class));
        self::assertTrue($providersAware->isPublished(ProvidedClass::class));
        self::assertTrue($providersAware->isPublished(ProvidedSecondaryClass::class));

        $providersAware->publishProvided(ProvidedSecondaryClass::class);

        self::assertTrue(ProviderClass::$publishCalled);
        self::assertFalse(ProviderClass::$publishSecondaryCalled);

        self::assertFalse($providersAware->isDeferred(ProvidedClass::class));
        self::assertFalse($providersAware->isDeferred(ProvidedSecondaryClass::class));
        self::assertTrue($providersAware->isPublished(ProvidedClass::class));
        self::assertTrue($providersAware->isPublished(ProvidedSecondaryClass::class));
    }

    public function testRegisterDeferred(): void
    {
        $providersAware = new ProvidersAwareClass();

        self::assertFalse(DeferredProviderClass::$publishCalled);
        self::assertFalse(DeferredProviderClass::$publishSecondaryCalled);

        self::assertFalse($providersAware->isDeferred(ProvidedClass::class));
        self::assertFalse($providersAware->isDeferred(ProvidedSecondaryClass::class));
        self::assertFalse($providersAware->isPublished(ProvidedClass::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryClass::class));
        self::assertFalse($providersAware->isRegistered(DeferredProviderClass::class));

        $providersAware->register(DeferredProviderClass::class);

        self::assertFalse(DeferredProviderClass::$publishCalled);
        self::assertFalse(DeferredProviderClass::$publishSecondaryCalled);

        // Since this provider is deferred
        self::assertTrue($providersAware->isDeferred(ProvidedClass::class));
        self::assertTrue($providersAware->isDeferred(ProvidedSecondaryClass::class));
        // Since this provider is deferred
        self::assertFalse($providersAware->isPublished(ProvidedClass::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryClass::class));

        self::assertTrue($providersAware->isRegistered(DeferredProviderClass::class));

        $providersAware->publishProvided(ProvidedClass::class);

        self::assertTrue(DeferredProviderClass::$publishCalled);
        self::assertFalse(DeferredProviderClass::$publishSecondaryCalled);

        self::assertTrue($providersAware->isDeferred(ProvidedClass::class));
        self::assertTrue($providersAware->isDeferred(ProvidedSecondaryClass::class));
        self::assertTrue($providersAware->isPublished(ProvidedClass::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryClass::class));

        $providersAware->publishProvided(ProvidedSecondaryClass::class);

        self::assertTrue(DeferredProviderClass::$publishCalled);
        self::assertTrue(DeferredProviderClass::$publishSecondaryCalled);

        self::assertTrue($providersAware->isDeferred(ProvidedClass::class));
        self::assertTrue($providersAware->isDeferred(ProvidedSecondaryClass::class));
        self::assertTrue($providersAware->isPublished(ProvidedClass::class));
        self::assertTrue($providersAware->isPublished(ProvidedSecondaryClass::class));
    }

    public function testRegisterDeferredInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $providersAware = new ProvidersAwareClass();

        $providersAware->register(InvalidDeferredProviderClass::class);
    }
}
