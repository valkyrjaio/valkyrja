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
use Valkyrja\Env\EnvTest;
use Valkyrja\Support\Directory;
use Valkyrja\Valkyrja;

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
     * @var \Valkyrja\Valkyrja
     */
    protected $app;

    /**
     * Setup functional tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        Directory::$BASE_PATH = __DIR__ . '/../../vendor/valkyrja/valkyrja-app/';

        Valkyrja::setEnv(EnvTest::class);

        $this->app = new Valkyrja();
    }
}
