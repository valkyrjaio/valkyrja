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

namespace Valkyrja\View;

/**
 * Interface PHPEngine.
 *
 * @author Melech Mizrachi
 */
interface PHPEngine extends Engine
{
    /**
     * Get the variables.
     *
     * @return array
     */
    public function getVariables(): array;

    /**
     * Set the variables.
     *
     * @param array $variables [optional] The variables to set
     *
     * @return static
     */
    public function setVariables(array $variables = []): self;

    /**
     * Get a variable.
     *
     * @param string $key The variable key to set
     *
     * @return mixed
     */
    public function getVariable(string $key);

    /**
     * Set a single variable.
     *
     * @param string $key   The variable key to set
     * @param mixed  $value The value to set
     *
     * @return static
     */
    public function setVariable(string $key, $value): self;

    /**
     * Escape a value for output.
     *
     * @param string $value The value to escape
     *
     * @return string
     */
    public function escape(string $value): string;

    /**
     * Set the layout for the view template.
     *
     * @param string $layout [optional]
     *
     * @return static
     */
    public function setLayout(string $layout = null): self;

    /**
     * Set no layout for this view.
     *
     * @return static
     */
    public function withoutLayout(): self;

    /**
     * Output a partial.
     *
     * @param string $partial   The partial
     * @param array  $variables [optional] The variables
     *
     * @return string
     */
    public function getPartial(string $partial, array $variables = []): string;

    /**
     * Output a block.
     *
     * @param string $name The name of the block
     *
     * @return string
     */
    public function getBlock(string $name): string;

    /**
     * Determine if a block exists.
     *
     * @param string $name The name of the block
     *
     * @return bool
     *  True if the block exists
     *  False if the block doesn't exist
     */
    public function hasBlock(string $name): bool;

    /**
     * Determine if a block has been ended.
     *
     * @param string $name The name of the block
     *
     * @return bool
     *  True if the block has been ended
     *  False if the block has not yet been ended
     */
    public function hasBlockEnded(string $name): bool;

    /**
     * Start a block.
     *
     * @param string $name The name of the block
     *
     * @return void
     */
    public function startBlock(string $name): void;

    /**
     * End a block.
     *
     * @param string $name The name of the block
     *
     * @return void
     */
    public function endBlock(string $name): void;
}
