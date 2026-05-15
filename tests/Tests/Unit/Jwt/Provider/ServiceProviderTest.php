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
use Valkyrja\Jwt\Provider\JwtServiceProvider;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = JwtServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(JwtContract::class, (new JwtServiceProvider())->publishers());
        self::assertArrayHasKey(FirebaseJwt::class, (new JwtServiceProvider())->publishers());
        self::assertArrayHasKey(NullJwt::class, (new JwtServiceProvider())->publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishJwt(): void
    {
        $this->container->setSingleton(FirebaseJwt::class, self::createStub(FirebaseJwt::class));

        $callback = (new JwtServiceProvider())->publishers()[JwtContract::class];
        $callback($this->container);

        self::assertInstanceOf(FirebaseJwt::class, $this->container->getSingleton(JwtContract::class));
    }

    public function testPublishFirebaseJwt(): void
    {
        $callback = (new JwtServiceProvider())->publishers()[FirebaseJwt::class];
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

        $callback = (new JwtServiceProvider())->publishers()[FirebaseJwt::class];
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

        $callback = (new JwtServiceProvider())->publishers()[FirebaseJwt::class];
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

        $callback = (new JwtServiceProvider())->publishers()[FirebaseJwt::class];
        $callback($this->container);

        self::assertInstanceOf(FirebaseJwt::class, $this->container->getSingleton(FirebaseJwt::class));
    }

    public function testPublishNullJwt(): void
    {
        $callback = (new JwtServiceProvider())->publishers()[NullJwt::class];
        $callback($this->container);

        self::assertInstanceOf(NullJwt::class, $this->container->getSingleton(NullJwt::class));
    }
}
