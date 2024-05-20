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

namespace Valkyrja\Tests\Unit\Container;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Annotation\Filter;
use Valkyrja\Container\Annotator as Contract;
use Valkyrja\Container\Annotators\Annotator;
use Valkyrja\Container\Providers\ServiceProvider;
use Valkyrja\Reflection\Reflector;
use Valkyrja\Tests\Unit\Provider\ServiceProviderTestCase;

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
    public function testPublishAnnotator(): void
    {
        $this->container->setSingleton(Reflector::class, $this->createMock(Reflector::class));
        $this->container->setSingleton(Filter::class, $this->createMock(Filter::class));

        ServiceProvider::publishAnnotator($this->container);

        self::assertInstanceOf(Annotator::class, $this->container->getSingleton(Contract::class));
    }
}
