<?php
declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Console\Input;

use PHPUnit\Framework\TestCase;
use Valkyrja\Console\Enums\ArgumentMode;
use Valkyrja\Console\Inputs\Argument;

/**
 * Test the Argument input class.
 *
 * @author Melech Mizrachi
 */
class ArgumentTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var Argument
     */
    protected Argument $class;

    /**
     * The name.
     *
     * @var string
     */
    protected string $name = 'Test Name';

    /**
     * The description.
     *
     * @var string
     */
    protected string $description = 'Test Description';

    /**
     * Get the class to test with.
     *
     * @return Argument
     */
    protected function getClass(): Argument
    {
        return $this->class ?? $this->class = new Argument($this->name, $this->description);
    }

    /**
     * Test the construction of a new Argument instance.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertEquals(true, $this->getClass() instanceof Argument);
    }

    /**
     * Test the getName getter method.
     *
     * @return void
     */
    public function testGetName(): void
    {
        $this->assertEquals($this->name, $this->getClass()->getName());
    }

    /**
     * Test the getDescription getter method.
     *
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertEquals($this->description, $this->getClass()->getDescription());
    }

    /**
     * Test the getMode getter method.
     *
     * @return void
     */
    public function testGetMode(): void
    {
        $this->assertEquals(ArgumentMode::OPTIONAL, $this->getClass()->getMode());
    }
}
