<?php

namespace tests;

use config\Config;
use config\EnvTest;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

use tests\traits\TestRequest;

use Valkyrja\Application;
use Valkyrja\Container\Container;
use Valkyrja\Events\Events;
use Valkyrja\Support\Directory;

/**
 * Class TestCase
 *
 * @package tests
 */
class TestCase extends PHPUnitTestCase
{
    use TestRequest;

    /**
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        Directory::$BASE_PATH = realpath(__DIR__ . '/../');

        $this->app = new Application(
        // Set the container
            new Container(),
            // Set the events
            new Events(),
            // Set the config
            new Config(
            // With environment variables
                new EnvTest()
            )
        );
    }
}
