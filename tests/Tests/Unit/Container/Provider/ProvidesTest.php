<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Container\Provider;

use ReflectionClass;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Tests\Fixtures\Container\Provider\ProvidesFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Provider class  and Provides trait.
 */
final class ProvidesTest extends TestCase
{
    public function testPublishers(): void
    {
        self::assertEmpty(new ProvidesFixture()->publishers());
    }

    public function testPublish(): void
    {
        $container = new Container();

        ProvidesFixture::publish($container);

        $reflection = new ReflectionClass($container);

        self::assertEmpty($reflection->getProperty('aliases')->getValue($container));
        self::assertEmpty($reflection->getProperty('instances')->getValue($container));
        self::assertEmpty($reflection->getProperty('services')->getValue($container));
        self::assertEmpty($reflection->getProperty('singletons')->getValue($container));
    }
}
