<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Functional;

use Valkyrja\Application\Constant\ApplicationInfo;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Tests\Functional\Abstract\TestCase;

/**
 * Test the functionality of the Application.
 */
final class ApplicationTest extends TestCase
{
    /**
     * Test the container() helper method.
     */
    public function testContainer(): void
    {
        self::assertInstanceOf(Container::class, $this->app->getContainer());
    }

    /**
     * Test the version() helper method.
     */
    public function testVersion(): void
    {
        self::assertSame(ApplicationInfo::VERSION, $this->app->getVersion());
    }

    /**
     * Test the environment() helper method.
     */
    public function testEnvironment(): void
    {
        self::assertSame($this->config->environment, $this->app->getEnvironment());
    }

    /**
     * Test the debug() helper method.
     */
    public function testDebug(): void
    {
        self::assertSame($this->config->debugMode, $this->app->getDebugMode());
    }
}
