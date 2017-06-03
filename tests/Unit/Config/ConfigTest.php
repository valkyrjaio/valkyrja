<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Config;

use PHPUnit\Framework\TestCase;

/**
 * Test the default config class.
 *
 * @author Melech Mizrachi
 */
class ConfigTest extends TestCase
{
    /**
     * The config.
     *
     * @var array
     */
    protected $config;

    /**
     * Get the config to test with.
     *
     * @return array
     */
    protected function getConfig(): array
    {
        return $this->config ?? $this->config = require __DIR__ . '/../../../src/Valkyrja/Config/config.php';
    }

    /**
     * Test the construction of a new Config instance.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertEquals(true, is_array($this->getConfig()));
    }

    /**
     * Test to ensure the annotations config is set in the default config instance.
     *
     * @return void
     */
    public function testAnnotationsConfig(): void
    {
        $this->assertEquals(true, isset($this->getConfig()['annotations']));
    }

    /**
     * Test to ensure the app config is set in the default config instance.
     *
     * @return void
     */
    public function testAppConfig(): void
    {
        $this->assertEquals(true, isset($this->getConfig()['app']));
    }

    /**
     * Test to ensure the container config is set in the default config instance.
     *
     * @return void
     */
    public function testContainerConfig(): void
    {
        $this->assertEquals(true, isset($this->getConfig()['container']));
    }

    /**
     * Test to ensure the console config is set in the default config instance.
     *
     * @return void
     */
    public function testConsoleConfig(): void
    {
        $this->assertEquals(true, isset($this->getConfig()['console']));
    }

    /**
     * Test to ensure the events config is set in the default config instance.
     *
     * @return void
     */
    public function testEventsConfig(): void
    {
        $this->assertEquals(true, isset($this->getConfig()['events']));
    }

    /**
     * Test to ensure the logger config is set in the default config instance.
     *
     * @return void
     */
    public function testLoggerConfig(): void
    {
        $this->assertEquals(true, isset($this->getConfig()['logger']));
    }

    /**
     * Test to ensure the routing config is set in the default config instance.
     *
     * @return void
     */
    public function testRoutingConfig(): void
    {
        $this->assertEquals(true, isset($this->getConfig()['routing']));
    }

    /**
     * Test to ensure the session config is set in the default config instance.
     *
     * @return void
     */
    public function testSessionConfig(): void
    {
        $this->assertEquals(true, isset($this->getConfig()['session']));
    }

    /**
     * Test to ensure the views config is set in the default config instance.
     *
     * @return void
     */
    public function testViewsConfig(): void
    {
        $this->assertEquals(true, isset($this->getConfig()['views']));
    }
}
