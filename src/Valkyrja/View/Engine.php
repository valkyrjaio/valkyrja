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

namespace Valkyrja\View;

/**
 * Interface Engine.
 *
 * @author Melech Mizrachi
 */
interface Engine
{
    /**
     * Make a new engine.
     *
     * @param View $view The view
     *
     * @return static
     */
    public static function make(View $view): self;

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

    /**
     * Render a template.
     *
     * @param string $path      The path to render
     * @param array  $variables [optional] The variables to set
     *
     * @return string
     */
    public function render(string $path, array $variables = []): string;
}
