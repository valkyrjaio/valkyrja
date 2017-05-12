<?php

namespace Valkyrja\Tests\Unit\Console;

use PHPUnit\Framework\TestCase;
use Valkyrja\Console\Command;

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
     * @var \Valkyrja\Console\Command
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
        $set = $this->class->setPath($this->value);

        $this->assertEquals(true, $set instanceof Command);
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
        $set = $this->class->setRegex(null);

        $this->assertEquals(true, $set instanceof Command);
    }

    /**
     * Test the setRegex method.
     *
     * @return void
     */
    public function testSetRegex(): void
    {
        $set = $this->class->setRegex($this->value);

        $this->assertEquals(true, $set instanceof Command);
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
        $set = $this->class->setParams(null);

        $this->assertEquals(true, $set instanceof Command);
    }

    /**
     * Test the setParams method.
     *
     * @return void
     */
    public function testSetParams(): void
    {
        $set = $this->class->setParams([$this->value]);

        $this->assertEquals(true, $set instanceof Command);
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
        $set = $this->class->setSegments(null);

        $this->assertEquals(true, $set instanceof Command);
    }

    /**
     * Test the setSegments method.
     *
     * @return void
     */
    public function testSetSegments(): void
    {
        $set = $this->class->setSegments([$this->value]);

        $this->assertEquals(true, $set instanceof Command);
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
        $set = $this->class->setDescription(null);

        $this->assertEquals(true, $set instanceof Command);
    }

    /**
     * Test the setDescription method.
     *
     * @return void
     */
    public function testSetDescription(): void
    {
        $set = $this->class->setDescription($this->value);

        $this->assertEquals(true, $set instanceof Command);
    }

    /**
     * Test the getCommand method.
     *
     * @return void
     */
    public function testGetCommand(): void
    {
        $command = Command::getCommand(['path' => $this->value]);

        $this->assertEquals(true, $command instanceof Command);
    }

    /**
     * Test the __set_state magic method.
     *
     * @return void
     */
    public function testSetState(): void
    {
        /* @noinspection ImplicitMagicMethodCallInspection */
        $command = Command::__set_state(['path' => $this->value]);

        $this->assertEquals(true, $command instanceof Command);
    }
}