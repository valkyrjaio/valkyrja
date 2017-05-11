<?php

namespace Valkyrja\Tests\Functional;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Valkyrja\Application;
use Valkyrja\Config\Config;
use Valkyrja\Config\Env;
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
     * @var \Valkyrja\Application
     */
    protected $app;

    /**
     * Setup functional tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        Directory::$BASE_PATH = realpath(__DIR__ . '/../App/');

        $this->app = new Application(new Config(new Env()));
    }
}
