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

use Valkyrja\Console\Enums\ArgumentMode;

/**
 * Class Argument
 *
 * @package Valkyrja\Console\Input
 *
 * @author  Melech Mizrachi
 */
class Argument
{
    /**
     * The name.
     *
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * The mode.
     *
     * @var string
     */
    protected $mode;

    /**
     * Argument constructor.
     *
     * @param string                               $name        The name
     * @param string                               $description The description
     * @param \Valkyrja\Console\Enums\ArgumentMode $mode        [optional] The mode
     */
    public function __construct(string $name, string $description, ArgumentMode $mode = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->mode = $mode ? $mode->getValue() : ArgumentMode::OPTIONAL;
    }

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get the mode.
     *
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }
}
