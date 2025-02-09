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

namespace Valkyrja\Console\Input;

use Valkyrja\Console\Enum\ArgumentMode;

/**
 * Class Argument.
 *
 * @author Melech Mizrachi
 */
class Argument
{
    /**
     * The name.
     *
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $description;

    /**
     * The mode.
     *
     * @var ArgumentMode
     */
    protected ArgumentMode $mode;

    /**
     * Argument constructor.
     *
     * @param string            $name        The name
     * @param string            $description The description
     * @param ArgumentMode|null $mode        [optional] The mode
     */
    public function __construct(string $name, string $description, ?ArgumentMode $mode = null)
    {
        $this->name        = $name;
        $this->description = $description;
        $this->mode        = $mode ?? ArgumentMode::OPTIONAL;
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
     * @return ArgumentMode
     */
    public function getMode(): ArgumentMode
    {
        return $this->mode;
    }
}
