<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Functional;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Env\EnvTest;
use Valkyrja\Support\Directory;

/**
 * Test case for functional tests.
 *
 * @author Melech Mizrachi
 */
class TestCase extends PHPUnitTestCase
{
    /**
     * The application.
     *
     * @var Valkyrja
     */
    protected Valkyrja $app;

    /**
     * Setup functional tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        Directory::$BASE_PATH = __DIR__ . '/../../';

        Valkyrja::setEnv(EnvTest::class);

        $this->app = new Valkyrja();
    }
}
