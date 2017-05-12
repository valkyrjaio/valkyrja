<?php

namespace Valkyrja\Tests\Unit\Http;

use PHPUnit\Framework\TestCase;
use Valkyrja\Routing\Route;

/**
 * Test the controller class.
 *
 * @author Melech Mizrachi
 */
class ControllerTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var \Valkyrja\Tests\Unit\Http\ControllerClass
     */
    protected $class;

    /**
     * Setup the test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->class = new ControllerClass();
    }

    /**
     * Test the before method.
     *
     * @return void
     */
    public function testBefore(): void
    {
        $this->assertEquals(null, $this->class->before('test', new Route()) ?? null);
    }

    /**
     * Test the after method.
     *
     * @return void
     */
    public function testAfter(): void
    {
        $var = 'a dispatch';

        $this->assertEquals(null, $this->class->after('test', $var) ?? null);
    }
}
