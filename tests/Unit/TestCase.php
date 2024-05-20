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

namespace Valkyrja\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

use function class_exists;
use function interface_exists;
use function is_a;
use function method_exists;
use function trait_exists;

/**
 * Test case for unit tests.
 *
 * @author Melech Mizrachi
 */
class TestCase extends PHPUnitTestCase
{
    /**
     * @param class-string $expected The class inherited
     * @param class-string $actual   The class to test
     */
    protected function isA(string $expected, string $actual): void
    {
        self::assertTrue(is_a($actual, $expected, true));
    }

    protected static function assertIsA(string $expected, string $actual): void
    {
        self::assertTrue(is_a($actual, $expected, true));
    }

    /**
     * @param object|class-string $class
     * @param string              $method
     */
    protected static function assertMethodExists(object|string $class, string $method): void
    {
        self::assertTrue(method_exists($class, $method));
    }

    /**
     * @param class-string $class
     */
    protected static function assertClassExists(string $class): void
    {
        self::assertTrue(class_exists($class));
    }

    protected static function assertInterfaceExists(string $interface): void
    {
        self::assertTrue(interface_exists($interface));
    }

    protected static function assertTraitExists(string $trait): void
    {
        self::assertTrue(trait_exists($trait));
    }
}
