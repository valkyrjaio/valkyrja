<?php

namespace Valkyrja\Tests\Unit\Events;

use PHPUnit\Framework\TestCase;
use Valkyrja\Container\ServiceContext;

/**
 * Test the service context model.
 *
 * @author Melech Mizrachi
 */
class ServiceContextTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var \Valkyrja\Container\ServiceContext
     */
    protected $class;

    /**
     * The value to test with.
     *
     * @var string
     */
    protected $value = 'test';

    /**
     * Setup the test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->class = new ServiceContext();
    }

    /**
     * Test the getContextClass method's default value.
     *
     * @return void
     */
    public function testGetContextClassDefault(): void
    {
        $this->assertEquals(null, $this->class->getContextClass());
    }

    /**
     * Test the getContextClass method.
     *
     * @return void
     */
    public function testGetContextClass(): void
    {
        $this->class->setContextClass($this->value);

        $this->assertEquals($this->value, $this->class->getContextClass());
    }

    /**
     * Test the setContextClass method.
     *
     * @return void
     */
    public function testSetContextClass(): void
    {
        $set = $this->class->setContextClass($this->value);

        $this->assertEquals(true, $set instanceof ServiceContext);
    }

    /**
     * Test the getContextProperty method's default value.
     *
     * @return void
     */
    public function testGetContextPropertyDefault(): void
    {
        $this->assertEquals(null, $this->class->getContextProperty());
    }

    /**
     * Test the getContextProperty method.
     *
     * @return void
     */
    public function testGetContextProperty(): void
    {
        $this->class->setContextProperty($this->value);

        $this->assertEquals($this->value, $this->class->getContextProperty());
    }

    /**
     * Test the setContextProperty method.
     *
     * @return void
     */
    public function testSetContextProperty(): void
    {
        $set = $this->class->setContextProperty($this->value);

        $this->assertEquals(true, $set instanceof ServiceContext);
    }

    /**
     * Test the getContextMethod method's default value.
     *
     * @return void
     */
    public function testGetContextMethodDefault(): void
    {
        $this->assertEquals(null, $this->class->getContextMethod());
    }

    /**
     * Test the getContextMethod method.
     *
     * @return void
     */
    public function testGetContextMethod(): void
    {
        $this->class->setContextMethod($this->value);

        $this->assertEquals($this->value, $this->class->getContextMethod());
    }

    /**
     * Test the setContextMethod method.
     *
     * @return void
     */
    public function testSetContextMethod(): void
    {
        $set = $this->class->setContextMethod($this->value);

        $this->assertEquals(true, $set instanceof ServiceContext);
    }

    /**
     * Test the getContextFunction method's default value.
     *
     * @return void
     */
    public function testGetContextFunctionDefault(): void
    {
        $this->assertEquals(null, $this->class->getContextFunction());
    }

    /**
     * Test the getContextFunction method.
     *
     * @return void
     */
    public function testGetContextFunction(): void
    {
        $this->class->setContextFunction($this->value);

        $this->assertEquals($this->value, $this->class->getContextFunction());
    }

    /**
     * Test the setContextFunction method.
     *
     * @return void
     */
    public function testSetContextFunction(): void
    {
        $set = $this->class->setContextFunction($this->value);

        $this->assertEquals(true, $set instanceof ServiceContext);
    }

    /**
     * Test the getContextClosure method's default value.
     *
     * @return void
     */
    public function testGetContextClosureDefault(): void
    {
        $this->assertEquals(null, $this->class->getContextClosure());
    }

    /**
     * Test the getContextClosure method.
     *
     * @return void
     */
    public function testGetContextClosure(): void
    {
        $value = function () {
        };
        $this->class->setContextClosure($value);

        $this->assertEquals($value, $this->class->getContextClosure());
    }

    /**
     * Test the setContextClosure method.
     *
     * @return void
     */
    public function testSetContextClosure(): void
    {
        $set = $this->class->setContextClosure(
            function () {
            }
        );

        $this->assertEquals(true, $set instanceof ServiceContext);
    }
}
