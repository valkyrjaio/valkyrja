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
use Valkyrja\Console\Enums\OptionMode;
use Valkyrja\Console\Inputs\Option;

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
     * @var Option
     */
    protected Option $class;

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
     * The shortcut.
     *
     * @var string
     */
    protected string $shortcut = 'S';

    /**
     * The default value.
     *
     * @var string
     */
    protected string $default = 'Default Value';

    /**
     * Get the class to test with.
     *
     * @return Option
     */
    protected function getClass(): Option
    {
        return $this->class ?? $this->class = new Option(
            $this->name,
            $this->description,
            $this->shortcut,
            null,
            $this->default
        );
    }

    /**
     * Test the construction of a new Option instance.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        self::assertEquals(true, $this->getClass() instanceof Option);
    }

    /**
     * Test the getName getter method.
     *
     * @return void
     */
    public function testGetName(): void
    {
        self::assertEquals($this->name, $this->getClass()->getName());
    }

    /**
     * Test the getShortcut getter method.
     *
     * @return void
     */
    public function testGetShortcut(): void
    {
        self::assertEquals($this->shortcut, $this->getClass()->getShortcut());
    }

    /**
     * Test the getDescription getter method.
     *
     * @return void
     */
    public function testGetDescription(): void
    {
        self::assertEquals($this->description, $this->getClass()->getDescription());
    }

    /**
     * Test the getDefault getter method.
     *
     * @return void
     */
    public function testGetDefault(): void
    {
        self::assertEquals($this->default, $this->getClass()->getDefault());
    }

    /**
     * Test the getMode getter method.
     *
     * @return void
     */
    public function testGetMode(): void
    {
        self::assertEquals(OptionMode::NONE, $this->getClass()->getMode());
    }
}
