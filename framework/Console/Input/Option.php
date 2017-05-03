<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Input;

use Valkyrja\Console\Enums\OptionMode;

/**
 * Class Option
 *
 * @package Valkyrja\Console\Input
 *
 * @author  Melech Mizrachi
 */
class Option
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $shortcut;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \Valkyrja\Console\Enums\OptionMode
     */
    protected $mode;

    /**
     * Option constructor.
     *
     * @param string                             $name        The name
     * @param string                             $description The description
     * @param string                             $shortcut    [optional] The shortcut
     * @param \Valkyrja\Console\Enums\OptionMode $mode        [optional] The mode
     */
    public function __construct(string $name, string $description, string $shortcut = null, OptionMode $mode = null)
    {
        $this->name = $name;
        $this->shortcut = $shortcut;
        $this->description = $description;
        $this->mode = $mode ?? new OptionMode(OptionMode::NONE);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getShortcut():? string
    {
        return $this->shortcut;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return \Valkyrja\Console\Enums\OptionMode
     */
    public function getMode(): OptionMode
    {
        return $this->mode;
    }
}
