<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Annotation\Model;

use Valkyrja\Annotation\Model\Annotation;
use Valkyrja\Tests\Classes\Annotation\AnnotatableClass;
use Valkyrja\Tests\Unit\TestCase;

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
     * @var AnnotatableClass
     */
    protected $class;

    /**
     * The value to test with.
     *
     * @var string
     */
    protected string $value = 'test';

    /**
     * Setup the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->class = new Annotation();
    }

    /**
     * Test the getAnnotationType method's default value.
     *
     * @return void
     */
    public function testGetAnnotationTypeDefault(): void
    {
        self::assertNull($this->class->getType());
    }

    /**
     * Test the getAnnotationType method.
     *
     * @return void
     */
    public function testGetAnnotationType(): void
    {
        $this->class->setType($this->value);

        self::assertSame($this->value, $this->class->getType());
    }

    /**
     * Test the setAnnotationType method with null value.
     *
     * @return void
     */
    public function testSetAnnotationTypeNull(): void
    {
        self::assertSame($this->class, $this->class->setType(null));
    }

    /**
     * Test the setAnnotationType method.
     *
     * @return void
     */
    public function testSetAnnotationType(): void
    {
        self::assertSame($this->class, $this->class->setType($this->value));
    }

    /**
     * Test the getId method's default value.
     *
     * @return void
     */
    public function testGetIdDefault(): void
    {
        self::assertNull($this->class->getId());
    }

    /**
     * Test the getId method.
     *
     * @return void
     */
    public function testGetId(): void
    {
        $this->class->setId($this->value);

        self::assertSame($this->value, $this->class->getId());
    }

    /**
     * Test the setId method with null value.
     *
     * @return void
     */
    public function testSetIdNull(): void
    {
        self::assertSame($this->class, $this->class->setId(null));
    }

    /**
     * Test the setId method.
     *
     * @return void
     */
    public function testSetId(): void
    {
        self::assertSame($this->class, $this->class->setId($this->value));
    }

    /**
     * Test the getName method's default value.
     *
     * @return void
     */
    public function testGetNameDefault(): void
    {
        self::assertNull($this->class->getName());
    }

    /**
     * Test the getName method.
     *
     * @return void
     */
    public function testGetName(): void
    {
        $this->class->setName($this->value);

        self::assertSame($this->value, $this->class->getName());
    }

    /**
     * Test the setName method with null value.
     *
     * @return void
     */
    public function testSetNameNull(): void
    {
        self::assertSame($this->class, $this->class->setName(null));
    }

    /**
     * Test the setName method.
     *
     * @return void
     */
    public function testSetName(): void
    {
        self::assertSame($this->class, $this->class->setName($this->value));
    }

    /**
     * Test the getClass method's default value.
     *
     * @return void
     */
    public function testGetClassDefault(): void
    {
        self::assertNull($this->class->getClass());
    }

    /**
     * Test the getClass method.
     *
     * @return void
     */
    public function testGetClass(): void
    {
        $this->class->setClass($this->value);

        self::assertSame($this->value, $this->class->getClass());
    }

    /**
     * Test the setClass method with null value.
     *
     * @return void
     */
    public function testSetClassNull(): void
    {
        self::assertSame($this->class, $this->class->setClass(null));
    }

    /**
     * Test the setClass method.
     *
     * @return void
     */
    public function testSetClass(): void
    {
        self::assertSame($this->class, $this->class->setClass($this->value));
    }

    /**
     * Test the getProperty method's default value.
     *
     * @return void
     */
    public function testGetPropertyDefault(): void
    {
        self::assertNull($this->class->getProperty());
    }

    /**
     * Test the getProperty method.
     *
     * @return void
     */
    public function testGetProperty(): void
    {
        $this->class->setProperty($this->value);

        self::assertSame($this->value, $this->class->getProperty());
    }

    /**
     * Test the setProperty method with null value.
     *
     * @return void
     */
    public function testSetPropertyNull(): void
    {
        self::assertSame($this->class, $this->class->setProperty(null));
    }

    /**
     * Test the setProperty method.
     *
     * @return void
     */
    public function testSetProperty(): void
    {
        self::assertSame($this->class, $this->class->setProperty($this->value));
    }

    /**
     * Test the getMethod method's default value.
     *
     * @return void
     */
    public function testGetMethodDefault(): void
    {
        self::assertNull($this->class->getMethod());
    }

    /**
     * Test the getMethod method.
     *
     * @return void
     */
    public function testGetMethod(): void
    {
        $this->class->setMethod($this->value);

        self::assertSame($this->value, $this->class->getMethod());
    }

    /**
     * Test the setMethod method with null value.
     *
     * @return void
     */
    public function testSetMethodNull(): void
    {
        self::assertSame($this->class, $this->class->setMethod(null));
    }

    /**
     * Test the setMethod method.
     *
     * @return void
     */
    public function testSetMethod(): void
    {
        self::assertSame($this->class, $this->class->setMethod($this->value));
    }

    /**
     * Test the isStatic method.
     *
     * @return void
     */
    public function testIsStatic(): void
    {
        $this->class->setStatic(true);

        self::assertTrue($this->class->isStatic());
    }

    /**
     * Test the setStatic method.
     *
     * @return void
     */
    public function testSetStatic(): void
    {
        self::assertSame($this->class, $this->class->setStatic(true));
    }

    /**
     * Test the getFunction method's default value.
     *
     * @return void
     */
    public function testGetFunctionDefault(): void
    {
        self::assertNull($this->class->getFunction());
    }

    /**
     * Test the getFunction method.
     *
     * @return void
     */
    public function testGetFunction(): void
    {
        $this->class->setFunction($this->value);

        self::assertSame($this->value, $this->class->getFunction());
    }

    /**
     * Test the setFunction method with null value.
     *
     * @return void
     */
    public function testSetFunctionNull(): void
    {
        self::assertSame($this->class, $this->class->setFunction(null));
    }

    /**
     * Test the setFunction method.
     *
     * @return void
     */
    public function testSetFunction(): void
    {
        self::assertSame($this->class, $this->class->setFunction($this->value));
    }

    /**
     * Test the getMatches method's default value.
     *
     * @return void
     */
    public function testGetMatchesDefault(): void
    {
        self::assertNull($this->class->getMatches());
    }

    /**
     * Test the getMatches method.
     *
     * @return void
     */
    public function testGetMatches(): void
    {
        $this->class->setMatches([$this->value]);

        self::assertSame([$this->value], $this->class->getMatches());
    }

    /**
     * Test the setMatches method with null value.
     *
     * @return void
     */
    public function testSetMatchesNull(): void
    {
        self::assertSame($this->class, $this->class->setMatches(null));
    }

    /**
     * Test the setMatches method.
     *
     * @return void
     */
    public function testSetMatches(): void
    {
        self::assertSame($this->class, $this->class->setMatches([$this->value]));
    }

    /**
     * Test the getArguments method's default value.
     *
     * @return void
     */
    public function testGetArgumentsDefault(): void
    {
        self::assertNull($this->class->getArguments());
    }

    /**
     * Test the getArguments method.
     *
     * @return void
     */
    public function testGetArguments(): void
    {
        $this->class->setArguments([$this->value]);

        self::assertSame([$this->value], $this->class->getArguments());
    }

    /**
     * Test the setArguments method with null value.
     *
     * @return void
     */
    public function testSetArgumentsNull(): void
    {
        self::assertSame($this->class, $this->class->setArguments(null));
    }

    /**
     * Test the setArguments method.
     *
     * @return void
     */
    public function testSetArguments(): void
    {
        self::assertSame($this->class, $this->class->setArguments([$this->value]));
    }

    /**
     * Test the getDependencies method's default value.
     *
     * @return void
     */
    public function testGetDependenciesDefault(): void
    {
        self::assertNull($this->class->getDependencies());
    }

    /**
     * Test the getDependencies method.
     *
     * @return void
     */
    public function testGetDependencies(): void
    {
        $this->class->setDependencies([$this->value]);

        self::assertSame([$this->value], $this->class->getDependencies());
    }

    /**
     * Test the setDependencies method with null value.
     *
     * @return void
     */
    public function testSetDependenciesNull(): void
    {
        self::assertSame($this->class, $this->class->setDependencies(null));
    }

    /**
     * Test the setDependencies method.
     *
     * @return void
     */
    public function testSetDependencies(): void
    {
        self::assertSame($this->class, $this->class->setDependencies([$this->value]));
    }
}
