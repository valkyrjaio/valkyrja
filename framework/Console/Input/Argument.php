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
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \Valkyrja\Console\Enums\ArgumentMode
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
        $this->mode = $mode ?? new ArgumentMode(ArgumentMode::OPTIONAL);
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return \Valkyrja\Console\Enums\ArgumentMode
     */
    public function getMode():? ArgumentMode
    {
        return $this->mode;
    }
}
