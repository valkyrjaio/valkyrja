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

namespace Valkyrja\Tests\Unit\Jwt\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Env\Env;
use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Jwt\Manager\FirebaseJwt;
use Valkyrja\Jwt\Manager\NullJwt;
use Valkyrja\Jwt\Provider\ServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(JwtContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(FirebaseJwt::class, ServiceProvider::publishers());
        self::assertArrayHasKey(NullJwt::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(JwtContract::class, ServiceProvider::provides());
        self::assertContains(FirebaseJwt::class, ServiceProvider::provides());
        self::assertContains(NullJwt::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishJwt(): void
    {
        $this->container->setSingleton(FirebaseJwt::class, self::createStub(FirebaseJwt::class));

        $callback = ServiceProvider::publishers()[JwtContract::class];
        $callback($this->container);

        self::assertInstanceOf(FirebaseJwt::class, $this->container->getSingleton(JwtContract::class));
    }

    public function testPublishFirebaseJwt(): void
    {
        $callback = ServiceProvider::publishers()[FirebaseJwt::class];
        $callback($this->container);

        self::assertInstanceOf(FirebaseJwt::class, $this->container->getSingleton(FirebaseJwt::class));
    }

    public function testPublishFirebaseJwtRsAlgorithm(): void
    {
        $this->container->setSingleton(
            Env::class,
            new class extends Env {
                /** @var Algorithm */
                public const Algorithm JWT_ALGORITHM = Algorithm::RS256;
            }
        );

        $callback = ServiceProvider::publishers()[FirebaseJwt::class];
        $callback($this->container);

        self::assertInstanceOf(FirebaseJwt::class, $this->container->getSingleton(FirebaseJwt::class));
    }

    public function testPublishFirebaseJwtEdDSAAlgorithm(): void
    {
        $this->container->setSingleton(
            Env::class,
            new class extends Env {
                /** @var Algorithm */
                public const Algorithm JWT_ALGORITHM = Algorithm::EdDSA;
            }
        );

        $callback = ServiceProvider::publishers()[FirebaseJwt::class];
        $callback($this->container);

        self::assertInstanceOf(FirebaseJwt::class, $this->container->getSingleton(FirebaseJwt::class));
    }

    public function testPublishFirebaseJwtDefault(): void
    {
        $this->container->setSingleton(
            Env::class,
            new class extends Env {
                /** @var Algorithm */
                public const Algorithm JWT_ALGORITHM = Algorithm::PS256;
            }
        );

        $callback = ServiceProvider::publishers()[FirebaseJwt::class];
        $callback($this->container);

        self::assertInstanceOf(FirebaseJwt::class, $this->container->getSingleton(FirebaseJwt::class));
    }

    public function testPublishNullJwt(): void
    {
        $callback = ServiceProvider::publishers()[NullJwt::class];
        $callback($this->container);

        self::assertInstanceOf(NullJwt::class, $this->container->getSingleton(NullJwt::class));
    }
}
