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

namespace Valkyrja\Tests\Unit\Abstract;

use Override;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Valkyrja\Tests\EnvClass;

use function class_exists;
use function interface_exists;
use function is_a;
use function method_exists;
use function trait_exists;

/**
 * Test case for unit tests.
 */
abstract class TestCase extends PHPUnitTestCase
{
    /**
     * Assert if a class is of expected class or has the expected class as one of its parents.
     *
     * @param class-string $expected The expected class
     * @param class-string $actual   The actual string
     */
    protected static function assertIsA(string $expected, string $actual): void
    {
        self::assertTrue(is_a($actual, $expected, true));
    }

    /**
     * @param object|class-string $class
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

    /**
     * @param class-string $expected The class inherited
     * @param class-string $actual   The class to test
     */
    protected static function isA(string $expected, string $actual): void
    {
        self::assertTrue(is_a($actual, $expected, true));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function tearDown(): void
    {
        $dir = EnvClass::APP_DIR . '/storage';

        /** @var string[] $files */
        $files = scandir($dir);

        foreach ($files as $file) {
            $filepath = $dir . '/' . $file;

            if ($file !== '.gitignore' && ! is_dir($filepath)) {
                @unlink($filepath);
            }
        }
    }
}
