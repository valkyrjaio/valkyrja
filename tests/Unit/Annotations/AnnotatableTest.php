<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Annotations;

use PHPUnit\Framework\TestCase;

/**
 * Test the Annotatable trait.
 *
 * @author Melech Mizrachi
 */
class AnnotatableTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var \Valkyrja\Tests\Unit\Annotations\AnnotatableClass
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

        $this->class = new AnnotatableClass();
    }

    /**
     * Test the getAnnotationType method's default value.
     *
     * @return void
     */
    public function testGetAnnotationTypeDefault(): void
    {
        $this->assertEquals(null, $this->class->getAnnotationType());
    }

    /**
     * Test the getAnnotationType method.
     *
     * @return void
     */
    public function testGetAnnotationType(): void
    {
        $this->class->setAnnotationType($this->value);

        $this->assertEquals($this->value, $this->class->getAnnotationType());
    }

    /**
     * Test the setAnnotationType method with null value.
     *
     * @return void
     */
    public function testSetAnnotationTypeNull(): void
    {
        $this->assertEquals(null, $this->class->setAnnotationType(null) ?? null);
    }

    /**
     * Test the setAnnotationType method.
     *
     * @return void
     */
    public function testSetAnnotationType(): void
    {
        $this->assertEquals(null, $this->class->setAnnotationType($this->value) ?? null);
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
        $this->assertEquals(null, $this->class->setClass(null) ?? null);
    }

    /**
     * Test the setClass method.
     *
     * @return void
     */
    public function testSetClass(): void
    {
        $this->assertEquals(null, $this->class->setClass($this->value) ?? null);
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
        $this->assertEquals(null, $this->class->setProperty(null) ?? null);
    }

    /**
     * Test the setProperty method.
     *
     * @return void
     */
    public function testSetProperty(): void
    {
        $this->assertEquals(null, $this->class->setProperty($this->value) ?? null);
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
        $this->assertEquals(null, $this->class->setMethod(null) ?? null);
    }

    /**
     * Test the setMethod method.
     *
     * @return void
     */
    public function testSetMethod(): void
    {
        $this->assertEquals(null, $this->class->setMethod($this->value) ?? null);
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
     * Test the setStatic method.
     *
     * @return void
     */
    public function testSetStatic(): void
    {
        $this->assertEquals(null, $this->class->setStatic(true) ?? null);
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
        $this->assertEquals(null, $this->class->setFunction(null) ?? null);
    }

    /**
     * Test the setFunction method.
     *
     * @return void
     */
    public function testSetFunction(): void
    {
        $this->assertEquals(null, $this->class->setFunction($this->value) ?? null);
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
        $this->assertEquals(null, $this->class->setMatches(null) ?? null);
    }

    /**
     * Test the setMatches method.
     *
     * @return void
     */
    public function testSetMatches(): void
    {
        $this->assertEquals(null, $this->class->setMatches([$this->value]) ?? null);
    }

    /**
     * Test the getAnnotationArguments method's default value.
     *
     * @return void
     */
    public function testGetAnnotationArgumentsDefault(): void
    {
        $this->assertEquals(null, $this->class->getAnnotationArguments());
    }

    /**
     * Test the getAnnotationArguments method.
     *
     * @return void
     */
    public function testGetAnnotationArguments(): void
    {
        $this->class->setAnnotationArguments([$this->value]);

        $this->assertEquals([$this->value], $this->class->getAnnotationArguments());
    }

    /**
     * Test the setAnnotationArguments method with null value.
     *
     * @return void
     */
    public function testSetAnnotationArgumentsNull(): void
    {
        $this->assertEquals(null, $this->class->setAnnotationArguments(null) ?? null);
    }

    /**
     * Test the setAnnotationArguments method.
     *
     * @return void
     */
    public function testSetAnnotationArguments(): void
    {
        $this->assertEquals(null, $this->class->setAnnotationArguments([$this->value]) ?? null);
    }
}
