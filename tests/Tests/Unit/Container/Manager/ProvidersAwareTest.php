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
use Valkyrja\Tests\Fixtures\Container\Manager\ProvidersAwareFixture;
use Valkyrja\Tests\Fixtures\Container\Provider\InvalidDeferredProviderFixture;
use Valkyrja\Tests\Fixtures\Container\Provider\ProvidedFixture;
use Valkyrja\Tests\Fixtures\Container\Provider\ProvidedSecondaryFixture;
use Valkyrja\Tests\Fixtures\Container\Provider\ProviderFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ProvidersAware support class.
 */
final class ProvidersAwareTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        ProviderFixture::$publishCalled          = false;
        ProviderFixture::$publishSecondaryCalled = false;
    }

    public function testRegister(): void
    {
        $providersAware = new ProvidersAwareFixture();

        self::assertFalse(ProviderFixture::$publishCalled);
        self::assertFalse(ProviderFixture::$publishSecondaryCalled);

        self::assertFalse($providersAware->isPublished(ProvidedFixture::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryFixture::class));

        $providersAware->register(new ProviderFixture());
        // Registering the same provider again just overwrites the callbacks
        $providersAware->register(new ProviderFixture());

        self::assertFalse(ProviderFixture::$publishCalled);
        self::assertFalse(ProviderFixture::$publishSecondaryCalled);

        self::assertFalse($providersAware->isPublished(ProvidedFixture::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryFixture::class));

        $providersAware->publish(ProvidedFixture::class);

        self::assertTrue(ProviderFixture::$publishCalled);
        self::assertFalse(ProviderFixture::$publishSecondaryCalled);

        self::assertTrue($providersAware->isPublished(ProvidedFixture::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryFixture::class));

        $providersAware->publish(ProvidedSecondaryFixture::class);

        self::assertTrue(ProviderFixture::$publishCalled);
        self::assertTrue(ProviderFixture::$publishSecondaryCalled);

        self::assertTrue($providersAware->isPublished(ProvidedFixture::class));
        self::assertTrue($providersAware->isPublished(ProvidedSecondaryFixture::class));
    }

    public function testPublishBeforeRegisterIsNoOp(): void
    {
        $providersAware = new ProvidersAwareFixture();

        $providersAware->publish(ProvidedFixture::class);

        self::assertFalse($providersAware->isPublished(ProvidedFixture::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryFixture::class));

        $providersAware->register(new ProviderFixture());

        $providersAware->publish(ProvidedFixture::class);

        self::assertTrue(ProviderFixture::$publishCalled);
        self::assertFalse(ProviderFixture::$publishSecondaryCalled);

        self::assertTrue($providersAware->isPublished(ProvidedFixture::class));
        self::assertFalse($providersAware->isPublished(ProvidedSecondaryFixture::class));

        $providersAware->publish(ProvidedSecondaryFixture::class);

        self::assertTrue(ProviderFixture::$publishCalled);
        self::assertTrue(ProviderFixture::$publishSecondaryCalled);

        self::assertTrue($providersAware->isPublished(ProvidedFixture::class));
        self::assertTrue($providersAware->isPublished(ProvidedSecondaryFixture::class));
    }

    public function testRegisterInvalidCallable(): void
    {
        $this->expectException(ContainerInvalidPublishCallbackException::class);

        $providersAware = new ProvidersAwareFixture();

        $providersAware->register(new InvalidDeferredProviderFixture());
    }

    public function testPublishUnpublishedProvidedPublishesDeferredCallback(): void
    {
        $providersAware = new ProvidersAwareFixture();
        $providersAware->register(new ProviderFixture());

        self::assertFalse($providersAware->isPublished(ProvidedFixture::class));

        $providersAware->callPublishUnpublishedProvided(ProvidedFixture::class);

        self::assertTrue($providersAware->isPublished(ProvidedFixture::class));
    }

    public function testPublishUnpublishedProvidedSkipsWhenNoCallbackRegistered(): void
    {
        $providersAware = new ProvidersAwareFixture();

        $providersAware->callPublishUnpublishedProvided(ProvidedFixture::class);

        self::assertFalse($providersAware->isPublished(ProvidedFixture::class));
    }
}
