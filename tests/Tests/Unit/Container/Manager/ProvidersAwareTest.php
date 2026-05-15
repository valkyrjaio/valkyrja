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

use Override;
use Valkyrja\Container\Throwable\Exception\ContainerInvalidPublishCallbackException;
use Valkyrja\Tests\Classes\Container\Manager\ProvidersAwareClass;
use Valkyrja\Tests\Classes\Container\Provider\InvalidDeferredProviderClass;
use Valkyrja\Tests\Classes\Container\Provider\ProvidedClass;
use Valkyrja\Tests\Classes\Container\Provider\ProvidedSecondaryClass;
use Valkyrja\Tests\Classes\Container\Provider\ProviderClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ProvidersAware support class.
 */
final class ProvidersAwareTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        ProviderClass::$publishCalled          = false;
        ProviderClass::$publishSecondaryCalled = false;
    }

    public function testRegister(): void
    {
        $providersAware = new ProvidersAwareClass();

        self::assertFalse(ProviderClass::$publishCalled);
        self::assertFalse(ProviderClass::$publishSecondaryCalled);

        self::assertFalse($providersAware->isPublished(ProvidedClass::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryClass::class));

        $providersAware->register(new ProviderClass());
        // Registering the same provider again just overwrites the callbacks
        $providersAware->register(new ProviderClass());

        self::assertFalse(ProviderClass::$publishCalled);
        self::assertFalse(ProviderClass::$publishSecondaryCalled);

        self::assertFalse($providersAware->isPublished(ProvidedClass::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryClass::class));

        $providersAware->publish(ProvidedClass::class);

        self::assertTrue(ProviderClass::$publishCalled);
        self::assertFalse(ProviderClass::$publishSecondaryCalled);

        self::assertTrue($providersAware->isPublished(ProvidedClass::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryClass::class));

        $providersAware->publish(ProvidedSecondaryClass::class);

        self::assertTrue(ProviderClass::$publishCalled);
        self::assertTrue(ProviderClass::$publishSecondaryCalled);

        self::assertTrue($providersAware->isPublished(ProvidedClass::class));
        self::assertTrue($providersAware->isPublished(ProvidedSecondaryClass::class));
    }

    public function testPublishBeforeRegisterIsNoOp(): void
    {
        $providersAware = new ProvidersAwareClass();

        $providersAware->publish(ProvidedClass::class);

        self::assertFalse($providersAware->isPublished(ProvidedClass::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryClass::class));

        $providersAware->register(new ProviderClass());

        $providersAware->publish(ProvidedClass::class);

        self::assertTrue(ProviderClass::$publishCalled);
        self::assertFalse(ProviderClass::$publishSecondaryCalled);

        self::assertTrue($providersAware->isPublished(ProvidedClass::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryClass::class));

        $providersAware->publish(ProvidedSecondaryClass::class);

        self::assertTrue(ProviderClass::$publishCalled);
        self::assertTrue(ProviderClass::$publishSecondaryCalled);

        self::assertTrue($providersAware->isPublished(ProvidedClass::class));
        self::assertTrue($providersAware->isPublished(ProvidedSecondaryClass::class));
    }

    public function testRegisterInvalidCallable(): void
    {
        $this->expectException(ContainerInvalidPublishCallbackException::class);

        $providersAware = new ProvidersAwareClass();

        $providersAware->register(new InvalidDeferredProviderClass());
    }
}
