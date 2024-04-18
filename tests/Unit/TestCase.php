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
}
