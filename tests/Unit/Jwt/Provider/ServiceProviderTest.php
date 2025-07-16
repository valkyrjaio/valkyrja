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
use Valkyrja\Application\Env;
use Valkyrja\Jwt\Contract\Jwt as Contract;
use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\FirebaseJwt;
use Valkyrja\Jwt\NullJwt;
use Valkyrja\Jwt\Provider\ServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    /**
     * @throws Exception
     */
    public function testPublishJwt(): void
    {
        $this->container->setSingleton(FirebaseJwt::class, $this->createMock(FirebaseJwt::class));

        ServiceProvider::publishJwt($this->container);

        self::assertInstanceOf(FirebaseJwt::class, $this->container->getSingleton(Contract::class));
    }

    public function testPublishFirebaseJwt(): void
    {
        ServiceProvider::publishFirebaseJwt($this->container);

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

        ServiceProvider::publishFirebaseJwt($this->container);

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

        ServiceProvider::publishFirebaseJwt($this->container);

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

        ServiceProvider::publishFirebaseJwt($this->container);

        self::assertInstanceOf(FirebaseJwt::class, $this->container->getSingleton(FirebaseJwt::class));
    }

    public function testPublishNullJwt(): void
    {
        ServiceProvider::publishNullJwt($this->container);

        self::assertInstanceOf(NullJwt::class, $this->container->getSingleton(NullJwt::class));
    }
}
