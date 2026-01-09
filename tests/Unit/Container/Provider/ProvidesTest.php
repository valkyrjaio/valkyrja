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

namespace Valkyrja\Tests\Unit\Container\Provider;

use ReflectionClass;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Tests\Classes\Container\ProvidesClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Provider class and Provides trait.
 */
class ProvidesTest extends TestCase
{
    public function testDeferred(): void
    {
        self::assertTrue(ProvidesClass::deferred());
    }

    public function testPublishers(): void
    {
        self::assertEmpty(ProvidesClass::publishers());
    }

    public function testProvides(): void
    {
        self::assertEmpty(ProvidesClass::provides());
    }

    public function testPublish(): void
    {
        $container = new Container();

        ProvidesClass::publish($container);

        $reflection = new ReflectionClass($container);

        self::assertEmpty($reflection->getProperty('aliases')->getValue($container));
        self::assertEmpty($reflection->getProperty('instances')->getValue($container));
        self::assertEmpty($reflection->getProperty('services')->getValue($container));
        self::assertEmpty($reflection->getProperty('callables')->getValue($container));
        self::assertEmpty($reflection->getProperty('singletons')->getValue($container));
    }
}
