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

namespace Valkyrja\Tests\Unit\Container\Collector;

use Valkyrja\Container\Attribute\Alias;
use Valkyrja\Container\Attribute\Service;
use Valkyrja\Container\Collector\AttributeCollector;
use Valkyrja\Tests\Classes\Container\Service2Class;
use Valkyrja\Tests\Classes\Container\ServiceClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the attribute collector service.
 */
class AttributeCollectorTest extends TestCase
{
    public function testGetServices(): void
    {
        $collector = new AttributeCollector();

        $services = $collector->getServices(ServiceClass::class);

        self::assertCount(1, $services);
        self::assertInstanceOf(Service::class, $services[0]);
    }

    public function testGetAliases(): void
    {
        $collector = new AttributeCollector();

        $aliases = $collector->getAliases(Service2Class::class);

        self::assertCount(1, $aliases);
        self::assertInstanceOf(Alias::class, $aliases[0]);
    }
}
