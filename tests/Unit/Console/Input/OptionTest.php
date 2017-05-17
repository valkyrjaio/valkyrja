<?php

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
use Valkyrja\Console\Enums\OptionMode;
use Valkyrja\Console\Input\Option;

/**
 * Test the Option input class.
 *
 * @author Melech Mizrachi
 */
class OptionTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var \Valkyrja\Console\Input\Option
     */
    protected $class;

    /**
     * The name.
     *
     * @var string
     */
    protected $name = 'Test Name';

    /**
     * The description.
     *
     * @var string
     */
    protected $description = 'Test Description';

    /**
     * The shortcut.
     *
     * @var string
     */
    protected $shortcut = 'S';

    /**
     * The default value.
     *
     * @var string
     */
    protected $default = 'Default Value';

    /**
     * Get the class to test with.
     *
     * @return \Valkyrja\Console\Input\Option
     */
    protected function getClass(): Option
    {
        return $this->class ?? $this->class = new Option($this->name, $this->description, $this->shortcut, null, $this->default);
    }

    /**
     * Test the construction of a new Option instance.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertEquals(true, $this->getClass() instanceof Option);
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
     * Test the getShortcut getter method.
     *
     * @return void
     */
    public function testGetShortcut(): void
    {
        $this->assertEquals($this->shortcut, $this->getClass()->getShortcut());
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
     * Test the getDefault getter method.
     *
     * @return void
     */
    public function testGetDefault(): void
    {
        $this->assertEquals($this->default, $this->getClass()->getDefault());
    }

    /**
     * Test the getMode getter method.
     *
     * @return void
     */
    public function testGetMode(): void
    {
        $this->assertEquals(OptionMode::NONE, $this->getClass()->getMode());
    }
}
