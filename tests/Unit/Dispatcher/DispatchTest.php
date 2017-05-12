<?php

namespace Valkyrja\Tests\Unit\Dispatcher;

use PHPUnit\Framework\TestCase;
use Valkyrja\Dispatcher\Dispatch;

/**
 * Test the dispatch model.
 *
 * @author Melech Mizrachi
 */
class DispatchTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var \Valkyrja\Dispatcher\Dispatch
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

        $this->class = new Dispatch();
    }

    /**
     * Test the getId method's default value.
     *
     * @return void
     */
    public function testGetIdDefault(): void
    {
        $this->assertEquals(null, $this->class->getId());
    }

    /**
     * Test the getId method.
     *
     * @return void
     */
    public function testGetId(): void
    {
        $this->class->setId($this->value);

        $this->assertEquals($this->value, $this->class->getId());
    }

    /**
     * Test the setId method with null value.
     *
     * @return void
     */
    public function testSetIdNull(): void
    {
        $set = $this->class->setId(null);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the setId method.
     *
     * @return void
     */
    public function testSetId(): void
    {
        $set = $this->class->setId($this->value);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the getName method's default value.
     *
     * @return void
     */
    public function testGetNameDefault(): void
    {
        $this->assertEquals(null, $this->class->getName());
    }

    /**
     * Test the getName method.
     *
     * @return void
     */
    public function testGetName(): void
    {
        $this->class->setName($this->value);

        $this->assertEquals($this->value, $this->class->getName());
    }

    /**
     * Test the setName method with null value.
     *
     * @return void
     */
    public function testSetNameNull(): void
    {
        $set = $this->class->setName(null);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the setName method.
     *
     * @return void
     */
    public function testSetName(): void
    {
        $set = $this->class->setName($this->value);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the getClass method's default value.
     *
     * @return void
     */
    public function testGetClassDefault(): void
    {
        $this->assertEquals(null, $this->class->getClass());
    }

    /**
     * Test the getClass method.
     *
     * @return void
     */
    public function testGetClass(): void
    {
        $this->class->setClass($this->value);

        $this->assertEquals($this->value, $this->class->getClass());
    }

    /**
     * Test the setClass method with null value.
     *
     * @return void
     */
    public function testSetClassNull(): void
    {
        $set = $this->class->setClass(null);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the setClass method.
     *
     * @return void
     */
    public function testSetClass(): void
    {
        $set = $this->class->setClass($this->value);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the getProperty method's default value.
     *
     * @return void
     */
    public function testGetPropertyDefault(): void
    {
        $this->assertEquals(null, $this->class->getProperty());
    }

    /**
     * Test the getProperty method.
     *
     * @return void
     */
    public function testGetProperty(): void
    {
        $this->class->setProperty($this->value);

        $this->assertEquals($this->value, $this->class->getProperty());
    }

    /**
     * Test the setProperty method with null value.
     *
     * @return void
     */
    public function testSetPropertyNull(): void
    {
        $set = $this->class->setProperty(null);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the setProperty method.
     *
     * @return void
     */
    public function testSetProperty(): void
    {
        $set = $this->class->setProperty($this->value);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the getMethod method's default value.
     *
     * @return void
     */
    public function testGetMethodDefault(): void
    {
        $this->assertEquals(null, $this->class->getMethod());
    }

    /**
     * Test the getMethod method.
     *
     * @return void
     */
    public function testGetMethod(): void
    {
        $this->class->setMethod($this->value);

        $this->assertEquals($this->value, $this->class->getMethod());
    }

    /**
     * Test the setMethod method with null value.
     *
     * @return void
     */
    public function testSetMethodNull(): void
    {
        $set = $this->class->setMethod(null);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the setMethod method.
     *
     * @return void
     */
    public function testSetMethod(): void
    {
        $set = $this->class->setMethod($this->value);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the isStatic method's default value.
     *
     * @return void
     */
    public function testIsStaticDefault(): void
    {
        $this->assertEquals(null, $this->class->isStatic());
    }

    /**
     * Test the isStatic method.
     *
     * @return void
     */
    public function testIsStatic(): void
    {
        $this->class->setStatic(true);

        $this->assertEquals(true, $this->class->isStatic());
    }

    /**
     * Test the setStatic method with null value.
     *
     * @return void
     */
    public function testSetStaticNull(): void
    {
        $set = $this->class->setStatic(null);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the setStatic method.
     *
     * @return void
     */
    public function testSetStatic(): void
    {
        $set = $this->class->setStatic(true);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the getFunction method's default value.
     *
     * @return void
     */
    public function testGetFunctionDefault(): void
    {
        $this->assertEquals(null, $this->class->getFunction());
    }

    /**
     * Test the getFunction method.
     *
     * @return void
     */
    public function testGetFunction(): void
    {
        $this->class->setFunction($this->value);

        $this->assertEquals($this->value, $this->class->getFunction());
    }

    /**
     * Test the setFunction method with null value.
     *
     * @return void
     */
    public function testSetFunctionNull(): void
    {
        $set = $this->class->setFunction(null);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the setFunction method.
     *
     * @return void
     */
    public function testSetFunction(): void
    {
        $set = $this->class->setFunction($this->value);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the getClosure method's default value.
     *
     * @return void
     */
    public function testGetClosureDefault(): void
    {
        $this->assertEquals(null, $this->class->getClosure());
    }

    /**
     * Test the getClosure method.
     *
     * @return void
     */
    public function testGetClosure(): void
    {
        $value = function () {
        };
        $this->class->setClosure($value);

        $this->assertEquals($value, $this->class->getClosure());
    }

    /**
     * Test the setClosure method with null value.
     *
     * @return void
     */
    public function testSetClosureNull(): void
    {
        $set = $this->class->setClosure(null);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the setClosure method.
     *
     * @return void
     */
    public function testSetClosure(): void
    {
        $set = $this->class->setClosure(function () {
        });

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the getMatches method's default value.
     *
     * @return void
     */
    public function testGetMatchesDefault(): void
    {
        $this->assertEquals(null, $this->class->getMatches());
    }

    /**
     * Test the getMatches method.
     *
     * @return void
     */
    public function testGetMatches(): void
    {
        $this->class->setMatches([$this->value]);

        $this->assertEquals([$this->value], $this->class->getMatches());
    }

    /**
     * Test the setMatches method with null value.
     *
     * @return void
     */
    public function testSetMatchesNull(): void
    {
        $set = $this->class->setMatches(null);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the setMatches method.
     *
     * @return void
     */
    public function testSetMatches(): void
    {
        $set = $this->class->setMatches([$this->value]);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the getDependencies method's default value.
     *
     * @return void
     */
    public function testGetDependenciesDefault(): void
    {
        $this->assertEquals(null, $this->class->getDependencies());
    }

    /**
     * Test the getDependencies method.
     *
     * @return void
     */
    public function testGetDependencies(): void
    {
        $this->class->setDependencies([$this->value]);

        $this->assertEquals([$this->value], $this->class->getDependencies());
    }

    /**
     * Test the setDependencies method with null value.
     *
     * @return void
     */
    public function testSetDependenciesNull(): void
    {
        $set = $this->class->setDependencies(null);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the setDependencies method.
     *
     * @return void
     */
    public function testSetDependencies(): void
    {
        $set = $this->class->setDependencies([$this->value]);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the getArguments method's default value.
     *
     * @return void
     */
    public function testGetArgumentsDefault(): void
    {
        $this->assertEquals(null, $this->class->getArguments());
    }

    /**
     * Test the getArguments method.
     *
     * @return void
     */
    public function testGetArguments(): void
    {
        $this->class->setArguments([$this->value]);

        $this->assertEquals([$this->value], $this->class->getArguments());
    }

    /**
     * Test the setArguments method with null value.
     *
     * @return void
     */
    public function testSetArgumentsNull(): void
    {
        $set = $this->class->setArguments(null);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the setArguments method.
     *
     * @return void
     */
    public function testSetArguments(): void
    {
        $set = $this->class->setArguments([$this->value]);

        $this->assertEquals(true, $set instanceof Dispatch);
    }

    /**
     * Test the getDispatch method.
     *
     * @return void
     */
    public function testGetDispatch(): void
    {
        $dispatch = Dispatch::getDispatch(['class' => $this->value]);

        $this->assertEquals(true, $dispatch instanceof Dispatch);
    }

    /**
     * Test the __set_state magic method.
     *
     * @return void
     */
    public function testSetState(): void
    {
        /* @noinspection ImplicitMagicMethodCallInspection */
        $dispatch = Dispatch::__set_state(['class' => $this->value]);

        $this->assertEquals(true, $dispatch instanceof Dispatch);
    }
}
