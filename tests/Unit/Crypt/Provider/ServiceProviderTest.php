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

namespace Valkyrja\Tests\Unit\Crypt\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Crypt\Manager\Contract\Crypt as Contract;
use Valkyrja\Crypt\Manager\NullCrypt;
use Valkyrja\Crypt\Manager\SodiumCrypt;
use Valkyrja\Crypt\Provider\ServiceProvider;
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
    public function testPublishCrypt(): void
    {
        $this->container->setSingleton(SodiumCrypt::class, self::createStub(SodiumCrypt::class));

        ServiceProvider::publishCrypt($this->container);

        self::assertInstanceOf(SodiumCrypt::class, $this->container->getSingleton(Contract::class));
    }

    public function testPublishSodiumCrypt(): void
    {
        ServiceProvider::publishSodiumCrypt($this->container);

        self::assertInstanceOf(SodiumCrypt::class, $this->container->getSingleton(SodiumCrypt::class));
    }

    public function testPublishNullCrypt(): void
    {
        ServiceProvider::publishNullCrypt($this->container);

        self::assertInstanceOf(NullCrypt::class, $this->container->getSingleton(NullCrypt::class));
    }
}
