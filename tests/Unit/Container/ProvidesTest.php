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

use ReflectionClass;
use Valkyrja\Container\Container;
use Valkyrja\Tests\Classes\Container\Provides;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Provider class and Provides trait.
 *
 * @author Melech Mizrachi
 */
class ProvidesTest extends TestCase
{
    public function testDeferred(): void
    {
        self::assertTrue(Provides::deferred());
    }

    public function testPublishers(): void
    {
        self::assertEmpty(Provides::publishers());
    }

    public function testProvides(): void
    {
        self::assertEmpty(Provides::provides());
    }

    public function testPublish(): void
    {
        $container = new Container();

        Provides::publish($container);

        $reflection = new ReflectionClass($container);

        self::assertEmpty($reflection->getProperty('aliases')->getValue($container));
        self::assertEmpty($reflection->getProperty('instances')->getValue($container));
        self::assertEmpty($reflection->getProperty('services')->getValue($container));
        self::assertEmpty($reflection->getProperty('closures')->getValue($container));
        self::assertEmpty($reflection->getProperty('singletons')->getValue($container));
    }
}
