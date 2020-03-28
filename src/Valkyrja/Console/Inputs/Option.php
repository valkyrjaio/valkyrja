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

namespace Valkyrja\Console\Inputs;

use Valkyrja\Console\Enums\OptionMode;

/**
 * Class Option.
 *
 * @author Melech Mizrachi
 */
class Option
{
    /**
     * The name.
     *
     * @var string
     */
    protected string $name;

    /**
     * The shortcut.
     *
     * @var string|null
     */
    protected ?string $shortcut = null;

    /**
     * The description.
     *
     * @var string
     */
    protected string $description;

    /**
     * The default value.
     *
     * @var string|null
     */
    protected ?string $default = null;

    /**
     * The mode.
     *
     * @var string
     */
    protected $mode;

    /**
     * Option constructor.
     *
     * @param string     $name        The name
     * @param string     $description The description
     * @param string     $shortcut    [optional] The shortcut
     * @param OptionMode $mode        [optional] The mode
     * @param string     $default     [optional] The default value
     */
    public function __construct(
        string $name,
        string $description,
        string $shortcut = null,
        OptionMode $mode = null,
        string $default = null
    ) {
        $this->name        = $name;
        $this->shortcut    = $shortcut;
        $this->description = $description;
        $this->mode        = $mode ? $mode->getValue() : OptionMode::NONE;
        $this->default     = $default;
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
     * Get the shortcut.
     *
     * @return string
     */
    public function getShortcut(): ?string
    {
        return $this->shortcut;
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
     * The default value.
     *
     * @return string
     */
    public function getDefault(): ?string
    {
        return $this->default;
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
