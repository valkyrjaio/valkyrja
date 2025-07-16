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

namespace Valkyrja\Tests\Unit\Reflection\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Reflection\Contract\Reflection as Contract;
use Valkyrja\Reflection\Provider\ServiceProvider;
use Valkyrja\Reflection\Reflection;
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
    public function testPublishApi(): void
    {
        $this->container->setSingleton(ResponseFactory::class, $this->createMock(ResponseFactory::class));

        ServiceProvider::publishReflection($this->container);

        self::assertInstanceOf(Reflection::class, $this->container->getSingleton(Contract::class));
    }
}
