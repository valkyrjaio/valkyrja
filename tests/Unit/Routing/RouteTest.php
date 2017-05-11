<?php

namespace Valkyrja\Tests\Unit\Routing;

use PHPUnit\Framework\TestCase;
use Valkyrja\Routing\Route;

/**
 * Test the route model.
 *
 * @author Melech Mizrachi
 */
class RouteTest extends TestCase
{
    /**
     * The route model.
     *
     * @var \Valkyrja\Routing\Route
     */
    protected $route;

    /**
     * The string value to test with.
     *
     * @var string
     */
    protected $stringValue = 'test';

    /**
     * Test the get path getter method default value.
     *
     * @return void
     */
    public function testGetPathDefault(): void
    {
        $this->assertEquals(null, $this->getRoute()->getPath());
    }

    /**
     * Get the route model to test with.
     *
     * @return \Valkyrja\Routing\Route
     */
    protected function getRoute(): Route
    {
        return $this->route ?? $this->route = new Route();
    }

    /**
     * Test the set path setter method.
     *
     * @return void
     */
    public function testSetPath(): void
    {
        $this->assertEquals(true, $this->getRoute()->setPath($this->stringValue) instanceof Route);
    }

    /**
     * Test the get path getter method.
     *
     * @return void
     */
    public function testGetPath(): void
    {
        $this->assertEquals($this->stringValue, $this->getRoute()->getPath());
    }
}
