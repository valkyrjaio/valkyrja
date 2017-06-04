<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Console\Annotations;

use PHPUnit\Framework\TestCase;
use Valkyrja\Console\Annotations\Command;

/**
 * Test the command model.
 *
 * @author Melech Mizrachi
 */
class CommandTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var \Valkyrja\Console\Annotations\Command
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

        $this->class = new Command();
    }

    /**
     * Test the getPath method's default value.
     *
     * @return void
     */
    public function testGetPathDefault(): void
    {
        $this->assertEquals(null, $this->class->getPath());
    }

    /**
     * Test the getPath method.
     *
     * @return void
     */
    public function testGetPath(): void
    {
        $this->class->setPath($this->value);

        $this->assertEquals($this->value, $this->class->getPath());
    }

    /**
     * Test the setPath method.
     *
     * @return void
     */
    public function testSetPath(): void
    {
        $this->assertEquals(null, $this->class->setPath($this->value) ?? null);
    }

    /**
     * Test the getRegex method's default value.
     *
     * @return void
     */
    public function testGetRegexDefault(): void
    {
        $this->assertEquals(null, $this->class->getRegex());
    }

    /**
     * Test the getRegex method.
     *
     * @return void
     */
    public function testGetRegex(): void
    {
        $this->class->setRegex($this->value);

        $this->assertEquals($this->value, $this->class->getRegex());
    }

    /**
     * Test the setRegex method with null value.
     *
     * @return void
     */
    public function testSetRegexNull(): void
    {
        $this->assertEquals(null, $this->class->setRegex(null) ?? null);
    }

    /**
     * Test the setRegex method.
     *
     * @return void
     */
    public function testSetRegex(): void
    {
        $this->assertEquals(null, $this->class->setRegex($this->value) ?? null);
    }

    /**
     * Test the getParams method's default value.
     *
     * @return void
     */
    public function testGetParamsDefault(): void
    {
        $this->assertEquals(null, $this->class->getParams());
    }

    /**
     * Test the getParams method.
     *
     * @return void
     */
    public function testGetParams(): void
    {
        $this->class->setParams([$this->value]);

        $this->assertEquals([$this->value], $this->class->getParams());
    }

    /**
     * Test the setParams method with null value.
     *
     * @return void
     */
    public function testSetParamsNull(): void
    {
        $this->assertEquals(null, $this->class->setParams(null) ?? null);
    }

    /**
     * Test the setParams method.
     *
     * @return void
     */
    public function testSetParams(): void
    {
        $this->assertEquals(null, $this->class->setParams([$this->value]) ?? null);
    }

    /**
     * Test the getSegments method's default value.
     *
     * @return void
     */
    public function testGetSegmentsDefault(): void
    {
        $this->assertEquals(null, $this->class->getSegments());
    }

    /**
     * Test the getSegments method.
     *
     * @return void
     */
    public function testGetSegments(): void
    {
        $this->class->setSegments([$this->value]);

        $this->assertEquals([$this->value], $this->class->getSegments());
    }

    /**
     * Test the setSegments method with null value.
     *
     * @return void
     */
    public function testSetSegmentsNull(): void
    {
        $this->assertEquals(true, $this->class->setSegments(null) ?? null);
    }

    /**
     * Test the setSegments method.
     *
     * @return void
     */
    public function testSetSegments(): void
    {
        $this->assertEquals(null, $this->class->setSegments([$this->value]) ?? null);
    }

    /**
     * Test the getDescription method's default value.
     *
     * @return void
     */
    public function testGetDescriptionDefault(): void
    {
        $this->assertEquals(null, $this->class->getDescription());
    }

    /**
     * Test the getDescription method.
     *
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->class->setDescription($this->value);

        $this->assertEquals($this->value, $this->class->getDescription());
    }

    /**
     * Test the setDescription method with null value.
     *
     * @return void
     */
    public function testSetDescriptionNull(): void
    {
        $this->assertEquals(null, $this->class->setDescription(null) ?? null);
    }

    /**
     * Test the setDescription method.
     *
     * @return void
     */
    public function testSetDescription(): void
    {
        $this->assertEquals(null, $this->class->setDescription($this->value) ?? null);
    }
}
